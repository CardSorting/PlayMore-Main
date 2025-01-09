<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ImageController extends Controller
{
    private const STAGES = [
        'pending' => [
            'message' => 'Initializing image generation...',
            'progress' => 0,
            'substages' => ['Validating prompt', 'Preparing request', 'Initializing task']
        ],
        'processing' => [
            'message' => 'Generating your image...',
            'progress' => 33,
            'substages' => ['Processing prompt', 'Creating initial image', 'Refining details', 'Applying final touches']
        ],
        'completed' => [
            'message' => 'Image generation completed!',
            'progress' => 100,
            'substages' => []
        ],
        'failed' => [
            'message' => 'Image generation failed',
            'progress' => 100,
            'substages' => []
        ]
    ];

    public function index(): View
    {
        return view('images.create');
    }

    public function gallery(): View
    {
        $images = auth()->user()->galleries()
            ->where('type', 'image')
            ->orderBy('created_at', 'desc')
            ->paginate(6);
        
        return view('images.gallery', compact('images'));
    }

    public function generate(Request $request): RedirectResponse
    {
        // Check if there's already an active task
        if (session()->has('image_task')) {
            $taskId = session('image_task.task_id');
            return redirect()
                ->route('images.status', $taskId)
                ->with('error', 'You already have an image generation in progress. Please wait for it to complete.')
                ->setStatusCode(409);
        }

        $request->validate([
            'prompt' => 'required|string|max:1000',
            'aspect_ratio' => 'nullable|string|in:1:1,16:9,4:3',
            'process_mode' => 'nullable|string|in:relax,fast,turbo'
        ]);

        try {
            // Update session with initial status
            session()->put('image_task', [
                'status' => 'pending',
                'stage_info' => self::STAGES['pending'],
                'current_substage' => 0,
                'started_at' => now(),
                'last_updated' => now(),
                'prompt' => $request->prompt,
                'aspect_ratio' => $request->aspect_ratio ?? '1:1',
                'process_mode' => $request->process_mode ?? 'relax'
            ]);

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
                
                // Update session with task ID and move to next stage
                session()->put('image_task.task_id', $taskId);
                session()->put('image_task.status', 'processing');
                session()->put('image_task.stage_info', self::STAGES['processing']);
                session()->put('image_task.current_substage', 0);
                session()->put('image_task.last_updated', now());
                
                return redirect()
                    ->route('images.status', $taskId)
                    ->with('success', 'Image generation started successfully');
            }

            session()->forget('image_task');
            $errorMessage = $response->json('message') ?? 'Unknown API error occurred';
            return back()
                ->with('error', 'Failed to start image generation: ' . $errorMessage)
                ->setStatusCode(500);
        } catch (\Exception $e) {
            session()->forget('image_task');
            return back()
                ->with('error', 'Failed to connect to image generation service: ' . $e->getMessage())
                ->setStatusCode(500);
        }
    }

    public function status($taskId): View|RedirectResponse
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => env('GOAPI_KEY'),
            ])->get("https://api.goapi.ai/api/v1/task/{$taskId}");

            if ($response->successful()) {
                $data = $response->json('data');
                $taskInfo = session('image_task');
                
                // Update progress based on time elapsed and status
                if ($data['status'] === 'processing' && $taskInfo['status'] === 'processing') {
                    $currentSubstage = $taskInfo['current_substage'];
                    $totalSubstages = count(self::STAGES['processing']['substages']);
                    $timeElapsed = now()->diffInSeconds($taskInfo['last_updated']);
                    
                    // Advance substage every 15 seconds
                    if ($timeElapsed >= 15 && $currentSubstage < $totalSubstages - 1) {
                        session()->put('image_task.current_substage', $currentSubstage + 1);
                        session()->put('image_task.last_updated', now());
                    }
                }
                
                // If the task is completed, save the images to gallery
                if ($data['status'] === 'completed' && isset($data['output']['image_urls'])) {
                    session()->put('image_task.status', 'completed');
                    session()->put('image_task.stage_info', self::STAGES['completed']);
                    
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
                } elseif ($data['status'] === 'failed') {
                    session()->put('image_task.status', 'failed');
                    session()->put('image_task.stage_info', self::STAGES['failed']);
                    session()->forget('image_task');
                }
                
                return view('images.status', [
                    'data' => $data,
                    'taskInfo' => session('image_task')
                ]);
            }

            session()->forget('image_task');
            return back()
                ->with('error', 'Failed to fetch task status: ' . ($response->json('message') ?? 'Unknown error'))
                ->setStatusCode(500);
        } catch (\Exception $e) {
            session()->forget('image_task');
            return back()
                ->with('error', 'Failed to connect to image generation service: ' . $e->getMessage())
                ->setStatusCode(500);
        }
    }
}
