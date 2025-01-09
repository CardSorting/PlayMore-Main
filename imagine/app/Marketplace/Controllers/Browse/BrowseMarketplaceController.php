<?php

namespace App\Marketplace\Controllers\Browse;

use App\Http\Controllers\Controller;
use App\Marketplace\Services\Browse\BrowseMarketplaceService;
use App\Models\Pack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrowseMarketplaceController extends Controller
{
    protected $browseService;

    public function __construct(BrowseMarketplaceService $browseService)
    {
        $this->browseService = $browseService;
    }

    public function index(Request $request)
    {
        $filters = $request->validate([
            'min_price' => 'nullable|integer|min:0',
            'max_price' => 'nullable|integer|min:0',
            'min_cards' => 'nullable|integer|min:1',
            'sort' => 'nullable|string|in:price_asc,price_desc,cards_asc,cards_desc,latest'
        ]);

        $availablePacks = $this->browseService->getAvailablePacks(
            filters: $filters,
            page: $request->integer('page', 1)
        );

        $marketplaceStats = $this->browseService->getMarketplaceStats();

        if ($request->wantsJson()) {
            return response()->json([
                'packs' => $availablePacks,
                'stats' => $marketplaceStats
            ]);
        }

        return view('marketplace.browse.index', [
            'availablePacks' => $availablePacks,
            'marketplaceStats' => $marketplaceStats,
            'filters' => $filters
        ]);
    }

    public function purchasePack(Pack $pack)
    {
        $this->authorize('purchase', $pack);

        $result = $this->browseService->purchasePack($pack, Auth::user());

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('packs.show', $pack)
            ->with('success', $result['message']);
    }

    public function filter(Request $request)
    {
        $filters = $request->validate([
            'min_price' => 'nullable|integer|min:0',
            'max_price' => 'nullable|integer|min:0',
            'min_cards' => 'nullable|integer|min:1',
            'sort' => 'nullable|string|in:price_asc,price_desc,cards_asc,cards_desc,latest'
        ]);

        $availablePacks = $this->browseService->getAvailablePacks(
            filters: $filters,
            page: $request->integer('page', 1)
        );

        return response()->json([
            'html' => view('marketplace.browse.components.pack-grid', [
                'availablePacks' => $availablePacks
            ])->render(),
            'pagination' => view('components.pagination', [
                'paginator' => $availablePacks
            ])->render()
        ]);
    }
}
