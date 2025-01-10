<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Services\CardService;
use App\ViewModels\CardViewModel;
use Illuminate\Http\Request;
use App\Http\Controllers\ImageController;

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

        $cards = $this->cardService->getUserCards(
            auth()->id(),
            [
                'newest' => $tab === 'newest',
                'sort' => request('sort', 'created_at')
            ]
        );

        // Transform each card using the ViewModel
        $transformedCards = $cards->map(function ($card) {
            return CardViewModel::fromArray($card->toArray())->toArray();
        });

        $cards = new \Illuminate\Pagination\LengthAwarePaginator(
            $transformedCards->forPage(request('page', 1), $perPage),
            $transformedCards->count(),
            $perPage,
            request('page', 1),
            ['path' => request()->url(), 'query' => request()->query()]
        );

        \Log::info('Cards data:', [
            'first_card' => $cards->first(),
            'total_cards' => $cards->count()
        ]);

        if ($request->has('opened')) {
            session()->flash('success', 'Pack opened successfully! The cards have been added to your collection.');
        }

        return view('dashboard.cards.index', [
            'cards' => $cards,
            'currentTab' => $tab,
            'currentView' => $view
        ]);
    }

    public function create(Request $request)
    {
        $image = Gallery::where('type', 'image')
            ->with('user')
            ->findOrFail($request->image_id);

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

            // Check for existing card
            if ($this->cardService->cardExistsForImage($image->image_url)) {
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'A card already exists for this image.']);
            }

            // Verify image URL matches
            if ($validatedData['image_url'] !== $image->image_url) {
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'Invalid image URL provided.']);
            }

            $card = $this->cardService->createCardFromImage($image, $validatedData);

            \Log::info('Card created successfully', [
                'card_id' => $card->id,
                'card_data' => $card->toArray(),
                'original_image_id' => $image->id
            ]);

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

    public function show(Gallery $card)
    {
        $viewModel = $this->cardService->getCardViewModel($card, optional($card->user)->name);
        
        return view('dashboard.cards.show', [
            'card' => $viewModel->toArray(),
        ]);
    }

    public function edit(Gallery $card)
    {
        $viewModel = $this->cardService->getCardViewModel($card, optional($card->user)->name);
        
        return view('dashboard.cards.edit', [
            'card' => $viewModel->toArray(),
        ]);
    }

    public function update(Request $request, Gallery $card)
    {
        return app(ImageController::class)->update($request, $card);
    }

    public function destroy(Gallery $card)
    {
        return app(ImageController::class)->destroy($card);
    }
}
