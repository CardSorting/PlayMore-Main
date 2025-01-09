<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\ImageController;

class CardController extends Controller
{
    /**
     * Custom validation rule for unique cards
     */
    public function __construct()
    {
        \Validator::extend('unique_card', function ($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            return !(
                Gallery::where('type', 'card')
                    ->where('image_url', $data['image_url'])
                    ->exists() ||
                \App\Models\GlobalCard::where('image_url', $data['image_url'])
                    ->exists()
            );
        }, 'You have already created a card for this image.');
    }

    /**
     * Display a listing of the cards.
     */
    public function index(Request $request)
    {
        $cards = Gallery::where('type', 'card')
            ->where('user_id', auth()->id())
            ->with('user')  // Eager load user information
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Show success message if redirected from pack opening
        if ($request->has('opened')) {
            session()->flash('success', 'Pack opened successfully! The cards have been added to your collection.');
        }

        return view('dashboard.cards.index', [
            'cards' => $cards,
        ]);
    }

    /**
     * Show the form for creating a new card.
     */
    public function create(Request $request)
    {
        $image = Gallery::where('type', 'image')
            ->with('user')
            ->findOrFail($request->image_id);

        return view('dashboard.cards.create', [
            'image' => $image
        ]);
    }

    /**
     * Store a newly created card in storage.
     */
    public function store(Request $request)
    {
        try {
            \Log::info('Card creation attempt', [
                'request' => $request->all(),
                'user_id' => auth()->id()
            ]);
            
            // Validate all data upfront
            try {
                $validatedData = $request->validate([
                    'image_id' => ['required', 'exists:galleries,id'],
                    'name' => ['required', 'string', 'max:255'],
                    'mana_cost' => ['required', 'string', 'max:50'],
                    'card_type' => ['required', 'string', 'max:255'],
                    'abilities' => ['required', 'string'],
                    'flavor_text' => ['nullable', 'string'],
                    'power_toughness' => ['nullable', 'string', 'max:10'],
                    'image_url' => ['required', 'url']
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                \Log::error('Card creation validation failed', [
                    'errors' => $e->errors(),
                    'request_data' => $request->all()
                ]);
                return back()
                    ->withErrors($e->errors())
                    ->withInput();
            }

            // Get the original image
            $image = Gallery::where('id', $validatedData['image_id'])
                ->where('type', 'image')
                ->first();

            if (!$image) {
                \Log::error('Invalid image selected', [
                    'image_id' => $validatedData['image_id'],
                    'user_id' => auth()->id()
                ]);
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'Invalid image selected.']);
            }

            // Check if a card already exists for this image in either Gallery or GlobalCard
            $existingGalleryCard = Gallery::where('type', 'card')
                ->where('image_url', $image->image_url)
                ->first();
            
            $existingGlobalCard = \App\Models\GlobalCard::where('image_url', $image->image_url)
                ->first();
                
            if ($existingGalleryCard || $existingGlobalCard) {
                $existingCard = $existingGalleryCard ?? $existingGlobalCard;
                \Log::warning('Attempted to create duplicate card', [
                    'image_id' => $image->id,
                    'image_url' => $image->image_url,
                    'existing_card_id' => $existingCard->id,
                    'existing_card_type' => $existingGalleryCard ? 'gallery' : 'global',
                    'user_id' => auth()->id()
                ]);
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'A card already exists for this image.']);
            }

            // Verify image URL matches
            if ($validatedData['image_url'] !== $image->image_url) {
                \Log::error('Image URL mismatch', [
                    'provided_url' => $validatedData['image_url'],
                    'actual_url' => $image->image_url,
                    'image_id' => $image->id,
                    'user_id' => auth()->id()
                ]);
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'Invalid image URL provided.']);
            }

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

            // Format mana cost into comma-separated list
            $manaCost = implode(',', str_split($request->mana_cost));
            
            \Log::info('Creating card with data', [
                'type' => 'card',
                'name' => $request->name,
                'mana_cost' => $manaCost,
                'card_type' => $request->card_type,
                'abilities' => $request->abilities,
                'flavor_text' => $request->flavor_text,
                'power_toughness' => $request->power_toughness,
                'image_url' => $request->image_url
            ]);

            // Create the card using the original image's data
            $card = auth()->user()->galleries()->create([
                'type' => 'card',
                'name' => $validatedData['name'],
                'mana_cost' => implode(',', str_split($validatedData['mana_cost'])),
                'card_type' => $validatedData['card_type'],
                'abilities' => $validatedData['abilities'],
                'flavor_text' => $validatedData['flavor_text'],
                'power_toughness' => $validatedData['power_toughness'],
                'rarity' => $selectedRarity,
                'image_url' => $image->image_url,
                'metadata' => [
                    'original_image_id' => $image->id,
                    'created_from' => 'image',
                    'created_at' => now()->toISOString(),
                    'original_metadata' => $image->metadata,
                    'original_author' => [
                        'id' => $image->user->id,
                        'name' => $image->user->name
                    ]
                ]
            ]);

            \Log::info('Card created successfully', [
                'card_id' => $card->id,
                'card_data' => $card->toArray(),
                'original_image_id' => $image->id
            ]);

            // Return JSON response with card data for animation
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'card' => $card,
                    'message' => 'Card created successfully!'
                ]);
            }

            return redirect()->route('cards.index')
                ->with('success', 'Card created successfully!')
                ->with('last_card', $card);

        } catch (\Exception $e) {
            \Log::error('Validation or pre-creation check failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified card.
     */
    public function show(Gallery $card)
    {
        return view('dashboard.cards.show', [
            'card' => $card,
        ]);
    }

    /**
     * Show the form for editing the specified card.
     */
    public function edit(Gallery $card)
    {
        return view('dashboard.cards.edit', [
            'card' => $card,
        ]);
    }

    /**
     * Update the specified card in storage.
     */
    public function update(Request $request, Gallery $card)
    {
        // Reuse existing update logic from ImageController
        return app(ImageController::class)->update($request, $card);
    }

    /**
     * Remove the specified card from storage.
     */
    public function destroy(Gallery $card)
    {
        // Reuse existing delete logic from ImageController
        return app(ImageController::class)->destroy($card);
    }
}
