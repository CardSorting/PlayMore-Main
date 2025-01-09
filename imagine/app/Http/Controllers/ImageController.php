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
        $images = auth()->user()->galleries()
            ->where('type', 'image')
            ->orderBy('created_at', 'desc')
            ->paginate(6);
        
        return view('images.gallery', compact('images'));
    }

    public function generate(Request $request)
    {
        // Check if there's already an active task
        if (session()->has('image_task')) {
            $taskId = session('image_task.task_id');
            return redirect()->route('images.status', $taskId)
                ->with('error', 'You already have an image generation in progress. Please wait for it to complete.');
        }

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

                session()->forget('image_task'); // Clean up session on failure
                return back()->with('error', 'Failed to start image generation: ' . $response->json('message'));
        } catch (\Exception $e) {
            session()->forget('image_task'); // Clean up session on error
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
                            'type' => 'image',
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

            session()->forget('image_task'); // Clean up session on error
            return back()->with('error', 'Failed to fetch task status');
        } catch (\Exception $e) {
            session()->forget('image_task'); // Clean up session on error
            return back()->with('error', 'Failed to connect to image generation service: ' . $e->getMessage());
        }
    }
}
