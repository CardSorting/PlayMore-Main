<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use App\Models\Gallery;
use App\Models\GlobalCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PackController extends Controller
{
    public function index()
    {
        $packs = Pack::where('user_id', Auth::id())
            ->withCount('cards')
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
        $this->authorize('view', $pack);
        
        $pack->load('cards');
        $availableCards = Gallery::where('user_id', Auth::id())->get();
        
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
}
