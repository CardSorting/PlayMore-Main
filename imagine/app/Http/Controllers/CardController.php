<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Gallery;
use App\Services\CardService;
use App\ViewModels\CardViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    public function __construct(
        private CardService $cardService
    )
    {
        \Validator::extend('unique_card', function ($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            return !$this->cardService->cardExistsForImage($data['image_url']);
        }, 'You have already created a card for this image.');
    }

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'all');
        $view = $request->get('view', 'grid');
        $perPage = $view === 'grid' ? 15 : 20;

        // Get cards with eager loaded relationships
        try {
            $cards = $this->cardService->getUserCards(
                auth()->id(),
                [
                    'newest' => $tab === 'newest',
                    'sort' => request('sort', 'created_at')
                ]
            );

            // Transform each card using the ViewModel
            $transformedCards = $cards->map(function ($card) {
                $viewModel = CardViewModel::fromCard(
                    $card,
                    $card->user?->name ?? 'Unknown Author'
                );

                \Log::info('Card transformed:', [
                    'card_id' => $card->id,
                    'name' => $card->name,
                    'abilities' => $card->abilities->pluck('ability_text'),
                    'metadata' => $viewModel->toArray()
                ]);

                return $viewModel->toArray();
            });

            // Create paginator
            $paginatedCards = new \Illuminate\Pagination\LengthAwarePaginator(
                $transformedCards->forPage(request('page', 1), $perPage),
                $transformedCards->count(),
                $perPage,
                request('page', 1),
                ['path' => request()->url(), 'query' => request()->query()]
            );

            \Log::info('Cards data:', [
                'first_card' => $paginatedCards->first(),
                'total_cards' => $paginatedCards->count(),
                'page' => request('page', 1),
                'per_page' => $perPage
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading cards:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'Failed to load cards. Please try again.']);
        }

        if ($request->has('opened')) {
            session()->flash('success', 'Pack opened successfully! The cards have been added to your collection.');
        }

        return view('dashboard.cards.index', [
            'cards' => $paginatedCards,
            'currentTab' => $tab,
            'currentView' => $view
        ]);
    }

    public function create(Request $request)
    {
        $image = Gallery::where('type', 'image')
            ->with('user')
            ->findOrFail($request->image_id);

        \Log::info('Creating card from image', [
            'image_id' => $image->id,
            'user_id' => $image->user_id,
            'image_url' => $image->image_url
        ]);

        return view('dashboard.cards.create', [
            'image' => $image
        ]);
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Card creation attempt', [
                'request' => $request->all(),
                'user_id' => auth()->id()
            ]);

            // Get the image first
            $image = Gallery::where('id', $request->image_id)
                ->where('type', 'image')
                ->first();

            if (!$image) {
                \Log::error('Invalid image selected', [
                    'image_id' => $request->image_id,
                    'user_id' => auth()->id()
                ]);
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'Invalid image selected.']);
            }

            // Check for existing card
            if ($this->cardService->cardExistsForImage($image->image_url)) {
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'A card already exists for this image.']);
            }

            // Validate the data
            $validatedData = $request->validate([
                'image_id' => ['required', 'exists:galleries,id'],
                'name' => ['required', 'string', 'max:255'],
                'manaCost' => ['required', 'string', 'max:50'],
                'cardType' => ['required', 'string', 'max:255'],
                'abilities' => ['required', 'string'],
                'flavorText' => ['nullable', 'string'],
                'powerToughness' => ['nullable', 'string', 'max:10'],
            ]);

            // Map the data to match the service expectations
            $cardData = [
                'image_id' => $validatedData['image_id'],
                'name' => $validatedData['name'],
                'mana_cost' => $validatedData['manaCost'],
                'card_type' => $validatedData['cardType'],
                'abilities' => $validatedData['abilities'],
                'flavor_text' => $validatedData['flavorText'],
                'power_toughness' => $validatedData['powerToughness'],
                'image_url' => $image->image_url
            ];

            $card = $this->cardService->createCardFromImage($image, $cardData);

            \Log::info('Card created successfully', [
                'card_id' => $card->id,
                'card_data' => [
                    'name' => $card->name,
                    'abilities_count' => $card->abilities->count(),
                    'metadata' => $card->getCardMetadata()->toArray()
                ],
                'original_image_id' => $image->id
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'card' => CardViewModel::fromCard($card, $card->user?->name)->toArray(),
                    'message' => 'Card created successfully!'
                ]);
            }

            return redirect()->route('cards.index')
                ->with('success', 'Card created successfully!')
                ->with('last_card', CardViewModel::fromCard($card, $card->user?->name)->toArray());

        } catch (\Exception $e) {
            \Log::error('Card creation failed', [
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

    public function show(Card $card)
    {
        $viewModel = CardViewModel::fromCard($card, $card->user?->name);
        
        return view('dashboard.cards.show', [
            'card' => $viewModel->toArray(),
        ]);
    }

    public function edit(Card $card)
    {
        $viewModel = CardViewModel::fromCard($card, $card->user?->name);
        
        return view('dashboard.cards.edit', [
            'card' => $viewModel->toArray(),
        ]);
    }

    public function update(Request $request, Card $card)
    {
        try {
            DB::transaction(function () use ($request, $card) {
                // Update basic card info
                $card->update([
                    'name' => $request->name,
                    'mana_cost' => $request->mana_cost,
                    'card_type' => $request->card_type,
                    'flavor_text' => $request->flavor_text,
                    'power_toughness' => $request->power_toughness,
                    'rarity' => $request->rarity
                ]);

                // Update abilities
                $card->abilities()->delete();
                $abilities = array_filter(array_map('trim', explode("\n", $request->abilities)));
                foreach ($abilities as $index => $ability) {
                    $card->abilities()->create([
                        'ability_text' => $ability,
                        'order' => $index
                    ]);
                }
            });

            \Log::info('Card updated successfully', [
                'card_id' => $card->id,
                'updates' => $request->except(['_token', '_method'])
            ]);

            return redirect()->route('cards.show', $card)
                ->with('success', 'Card updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Failed to update card', [
                'card_id' => $card->id,
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update card: ' . $e->getMessage()]);
        }
    }

    public function destroy(Card $card)
    {
        try {
            DB::transaction(function () use ($card) {
                // Delete abilities first due to foreign key constraint
                $card->abilities()->delete();
                $card->delete();
            });

            \Log::info('Card deleted successfully', [
                'card_id' => $card->id
            ]);

            return redirect()->route('cards.index')
                ->with('success', 'Card deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Failed to delete card', [
                'card_id' => $card->id,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Failed to delete card: ' . $e->getMessage()]);
        }
    }
}
