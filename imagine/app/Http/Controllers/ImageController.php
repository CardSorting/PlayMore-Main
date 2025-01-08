<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ImageController extends Controller
{
    /**
     * Custom validation rule for unique cards
     */
    public function __construct()
    {
        \Validator::extend('unique_card', function ($attribute, $value, $parameters, $validator) {
            $cards = collect(session('cards', []));
            $data = $validator->getData();
            return !$cards->contains(function ($card) use ($data) {
                return $card['image_url'] === $data['image_url'];
            });
        }, 'You have already created a card for this image.');
    }

    public function index()
    {
        return view('images.create');
    }

    public function gallery()
    {
        $images = auth()->user()->galleries()->paginate(6);
        
        // Manually paginate the cards collection and convert to array format
        $cards = collect(session('cards', []))->map(function($card) {
            // Ensure all required fields are present
            return [
                'name' => $card['name'] ?? 'Unnamed Card',
                'mana_cost' => $card['mana_cost'] ?? '',
                'card_type' => $card['card_type'] ?? 'Unknown Type',
                'abilities' => $card['abilities'] ?? 'No abilities',
                'flavor_text' => $card['flavor_text'] ?? '',
                'power_toughness' => $card['power_toughness'] ?? null,
                'rarity' => $card['rarity'] ?? 'Common',
                'image_url' => $card['image_url'] ?? '/static/images/placeholder.png',
            ];
        });
        
        $page = request()->get('page', 1);
        $perPage = 6;
        
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
        
        // Check if a card already exists for this image
        $cards = collect(session('cards', []));
        $cardExists = $cards->contains(function ($card) use ($image) {
            return $card['image_url'] === $image->image_url;
        });
        
        return view('images.create-card', [
            'image' => $image,
            'cardExists' => $cardExists
        ]);
    }

    public function storeCard(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mana_cost' => ['required', 'string', 'max:50'],
            'card_type' => ['required', 'string', 'max:255'],
            'abilities' => ['required', 'string'],
            'flavor_text' => ['nullable', 'string'],
            'power_toughness' => ['nullable', 'string', 'max:10'],
            'image_url' => ['required', 'url', 'unique_card']
        ]);

        // Randomly select rarity with weighted probabilities
        $rarities = [
            'Common' => 50,      // 50% chance
            'Uncommon' => 30,    // 30% chance
            'Rare' => 15,        // 15% chance
            'Mythic Rare' => 5   // 5% chance
        ];
        
        $total = array_sum($rarities);
        $roll = rand(1, $total);
        $selectedRarity = 'Common';
        
        foreach ($rarities as $rarity => $weight) {
            if ($roll <= $weight) {
                $selectedRarity = $rarity;
                break;
            }
            $roll -= $weight;
        }

        // Store card in session for now (you might want to create a proper cards table later)
        // Format mana cost into comma-separated list
        $manaCost = implode(',', str_split($request->mana_cost));
        
        $cardData = [
            'name' => $request->name,
            'mana_cost' => $manaCost,
            'card_type' => $request->card_type,
            'abilities' => $request->abilities,
            'flavor_text' => $request->flavor_text,
            'power_toughness' => $request->power_toughness,
            'rarity' => $selectedRarity,
            'image_url' => $request->image_url,
        ];
        
        $cards = collect(session('cards', []));
        $cards->push($cardData);
        session(['cards' => $cards->all()]);

        // Return JSON response with card data for animation
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'card' => $cardData,
                'message' => 'Card created successfully!'
            ]);
        }

        return redirect()->route('images.gallery')
            ->with('success', 'Card created successfully!')
            ->with('last_card', $cardData);
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
