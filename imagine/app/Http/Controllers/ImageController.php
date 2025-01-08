<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ImageController extends Controller
{
    public function index()
    {
        return view('images.create');
    }

    public function gallery()
    {
        $images = auth()->user()->galleries()->paginate(12);
        
        // Manually paginate the cards collection
        $cards = collect(session('cards', []));
        $page = request()->get('page', 1);
        $perPage = 12;
        
        $cards = new \Illuminate\Pagination\LengthAwarePaginator(
            $cards->forPage($page, $perPage),
            $cards->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return view('images.gallery', compact('images', 'cards'));
    }

    public function createCard($id)
    {
        $image = auth()->user()->galleries()->findOrFail($id);
        return view('images.create-card', compact('image'));
    }

    public function storeCard(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mana_cost' => 'required|string|max:50',
            'card_type' => 'required|string|max:255',
            'abilities' => 'required|string',
            'flavor_text' => 'nullable|string',
            'power_toughness' => 'nullable|string|max:10',
            'rarity' => 'required|string|in:Common,Uncommon,Rare,Mythic Rare',
            'image_url' => 'required|url'
        ]);

        // Store card in session for now (you might want to create a proper cards table later)
        session()->push('cards', $request->all());

        return redirect()->route('images.gallery')
            ->with('success', 'Card created successfully!');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:1000',
            'aspect_ratio' => 'nullable|string|in:1:1,16:9,4:3',
            'process_mode' => 'nullable|string|in:relax,fast,turbo'
        ]);

        try {
            $response = Http::withHeaders([
                'x-api-key' => env('GOAPI_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.goapi.ai/api/v1/task', [
                'model' => 'midjourney',
                'task_type' => 'imagine',
                'input' => [
                    'prompt' => $request->prompt,
                    'aspect_ratio' => $request->aspect_ratio ?? '1:1',
                    'process_mode' => $request->process_mode ?? 'relax',
                    'skip_prompt_check' => false
                ]
            ]);

            if ($response->successful()) {
                $taskId = $response->json('data.task_id');
                
                // Store task information in session for later use
                session()->put('image_task', [
                    'task_id' => $taskId,
                    'prompt' => $request->prompt,
                    'aspect_ratio' => $request->aspect_ratio ?? '1:1',
                    'process_mode' => $request->process_mode ?? 'relax'
                ]);
                
                return redirect()->route('images.status', $taskId)->with('success', 'Image generation started');
            }

            return back()->with('error', 'Failed to start image generation: ' . $response->json('message'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to connect to image generation service: ' . $e->getMessage());
        }
    }

    public function status($taskId)
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => env('GOAPI_KEY'),
            ])->get("https://api.goapi.ai/api/v1/task/{$taskId}");

            if ($response->successful()) {
                $data = $response->json('data');
                
                // If the task is completed, save the images to gallery
                if ($data['status'] === 'completed' && isset($data['output']['image_urls'])) {
                    $taskInfo = session('image_task');
                    
                    foreach ($data['output']['image_urls'] as $imageUrl) {
                        auth()->user()->galleries()->create([
                            'image_url' => str_replace('\\', '', $imageUrl),
                            'prompt' => $taskInfo['prompt'],
                            'aspect_ratio' => $taskInfo['aspect_ratio'],
                            'process_mode' => $taskInfo['process_mode'],
                            'task_id' => $data['task_id'],
                            'metadata' => [
                                'created_at' => $data['meta']['created_at'] ?? now(),
                                'completed_at' => now()
                            ]
                        ]);
                    }
                    
                    session()->forget('image_task');
                }
                
                return view('images.status', compact('data'));
            }

            return back()->with('error', 'Failed to fetch task status');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to connect to image generation service: ' . $e->getMessage());
        }
    }
}
