<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use App\Models\Gallery;
use App\Models\GlobalCard;
use App\Services\PulseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PackController extends Controller
{
    protected $pulseService;

    public function __construct(PulseService $pulseService)
    {
        $this->pulseService = $pulseService;
    }

    public function index()
    {
        $packs = Pack::where('user_id', Auth::id())
            ->withCount('cards')
            ->with(['cards' => function($query) {
                $query->inRandomOrder()->limit(1);
            }])
            ->get();
            
        return view('dashboard.packs.index', compact('packs'));
    }

    public function create()
    {
        return view('dashboard.packs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'card_limit' => 'required|integer|min:1|max:100'
        ]);

        $pack = Pack::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'card_limit' => $validated['card_limit'],
            'is_sealed' => false
        ]);

        return redirect()->route('packs.show', $pack)
            ->with('success', 'Pack created successfully');
    }

    public function show(Pack $pack)
    {
        try {
            $this->authorize('view', $pack);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            if ($pack->is_sealed) {
                return redirect()->route('packs.index')
                    ->with('error', 'This pack is sealed and cannot be viewed.');
            }
            throw $e;
        }
        
        $pack->load('cards');
        $availableCards = Gallery::where('user_id', Auth::id())
            ->where('type', 'card')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('dashboard.packs.show', compact('pack', 'availableCards'));
    }

    public function addCard(Request $request, Pack $pack)
    {
        $this->authorize('update', $pack);

        $validated = $request->validate([
            'card_id' => 'required|exists:galleries,id'
        ]);

        if ($pack->is_sealed) {
            return back()->with('error', 'Cannot modify a sealed pack');
        }

        if ($pack->cards()->count() >= $pack->card_limit) {
            return back()->with('error', 'Pack has reached its card limit');
        }

        try {
            DB::beginTransaction();

            $gallery = Gallery::findOrFail($validated['card_id']);
            
            // Create global card from gallery card
            GlobalCard::createFromGallery($gallery, $pack->id);
            
            // Remove card from user's gallery
            $gallery->delete();

            DB::commit();

            return back()->with('success', 'Card added to pack successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to add card to pack');
        }
    }

    public function seal(Pack $pack)
    {
        $this->authorize('update', $pack);

        if ($pack->cards()->count() < $pack->card_limit) {
            return back()->with('error', 'Pack must be full before sealing');
        }

        $pack->update(['is_sealed' => true]);

        return back()->with('success', 'Pack has been sealed');
    }

    public function marketplace(Request $request)
    {
        $tab = $request->get('tab', 'browse');
        
        // Browse tab - Available packs in marketplace
        $availablePacks = Pack::availableOnMarketplace()
            ->withCount('cards')
            ->with(['cards' => function($query) {
                $query->inRandomOrder()->limit(1);
            }])
            ->with('user')
            ->latest('listed_at')
            ->get();
            
        // Selling tab - User's listed packs and sales history
        $listedPacks = Pack::where('user_id', Auth::id())
            ->where('is_listed', true)
            ->withCount('cards')
            ->with(['cards' => function($query) {
                $query->inRandomOrder()->limit(1);
            }])
            ->latest('listed_at')
            ->get();
            
        $soldPacks = Pack::where('user_id', '!=', Auth::id())
            ->whereIn('id', function($query) {
                $query->select('pack_id')
                    ->from('credit_transactions')
                    ->where('user_id', Auth::id())
                    ->where('description', 'like', 'Sold pack #%');
            })
            ->with('user')
            ->latest()
            ->get();
            
        // Purchases tab - Packs purchased by user
        $purchasedPacks = Pack::where('user_id', Auth::id())
            ->whereIn('id', function($query) {
                $query->select('pack_id')
                    ->from('credit_transactions')
                    ->where('user_id', Auth::id())
                    ->where('description', 'like', 'Purchase pack #%');
            })
            ->withCount('cards')
            ->with(['cards' => function($query) {
                $query->inRandomOrder()->limit(1);
            }])
            ->with('user')
            ->latest()
            ->get();
            
        return view('dashboard.packs.marketplace', compact(
            'tab',
            'availablePacks',
            'listedPacks',
            'soldPacks',
            'purchasedPacks'
        ));
    }

    public function listOnMarketplace(Request $request, Pack $pack)
    {
        $this->authorize('listOnMarketplace', $pack);

        $validated = $request->validate([
            'price' => 'required|integer|min:1'
        ]);

        if (!$pack->is_sealed) {
            return back()->with('error', 'Pack must be sealed before listing');
        }

        $pack->listOnMarketplace($validated['price']);

        return back()->with('success', 'Pack listed on marketplace');
    }

    public function removeFromMarketplace(Pack $pack)
    {
        $this->authorize('removeFromMarketplace', $pack);

        $pack->removeFromMarketplace();

        return back()->with('success', 'Pack removed from marketplace');
    }

    public function purchase(Pack $pack)
    {
        $this->authorize('purchase', $pack);

        if (!$pack->canBePurchased()) {
            return back()->with('error', 'This pack is not available for purchase');
        }

        $buyer = Auth::user();
        if (!$this->pulseService->deductCredits($buyer, $pack->price, 'Purchase pack #' . $pack->id)) {
            return back()->with('error', 'Insufficient credits');
        }

        try {
            DB::beginTransaction();

            // Credit the seller
            $this->pulseService->addCredits(
                $pack->user, 
                $pack->price, 
                'Sold pack #' . $pack->id
            );

            // Transfer ownership
            $pack->update([
                'user_id' => $buyer->id,
                'is_listed' => false,
                'listed_at' => null
            ]);

            DB::commit();

            return redirect()->route('packs.show', $pack)
                ->with('success', 'Pack purchased successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            // Refund the buyer
            $this->pulseService->addCredits(
                $buyer, 
                $pack->price, 
                'Refund for failed purchase of pack #' . $pack->id
            );
            return back()->with('error', 'Failed to process purchase');
        }
    }
}
