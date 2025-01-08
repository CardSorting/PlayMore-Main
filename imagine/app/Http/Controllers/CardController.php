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
            $cards = collect(session('cards', []));
            $data = $validator->getData();
            return !$cards->contains(function ($card) use ($data) {
                return $card['image_url'] === $data['image_url'];
            });
        }, 'You have already created a card for this image.');
    }

    /**
     * Display a listing of the cards.
     */
    public function index()
    {
        $cards = Gallery::where('type', 'card')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('dashboard.cards.index', [
            'cards' => $cards,
        ]);
    }

    /**
     * Show the form for creating a new card.
     */
    public function create()
    {
        return view('images.create-card');
    }

    /**
     * Store a newly created card in storage.
     */
    public function store(Request $request)
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

        // Format mana cost into comma-separated list
        $manaCost = implode(',', str_split($request->mana_cost));
        
        $card = auth()->user()->galleries()->create([
            'type' => 'card',
            'name' => $request->name,
            'mana_cost' => $manaCost,
            'card_type' => $request->card_type,
            'abilities' => $request->abilities,
            'flavor_text' => $request->flavor_text,
            'power_toughness' => $request->power_toughness,
            'rarity' => $selectedRarity,
            'image_url' => $request->image_url,
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
