<?php

namespace App\Marketplace\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Marketplace\Services\Seller\SellerDashboardService;
use App\Models\Pack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerDashboardController extends Controller
{
    protected $sellerService;

    public function __construct(SellerDashboardService $sellerService)
    {
        $this->sellerService = $sellerService;
    }

    public function index()
    {
        $listedPacks = $this->sellerService->getListedPacks(Auth::user());
        $availablePacks = $this->sellerService->getAvailablePacks(Auth::user());
        $totalSales = $this->sellerService->getTotalSales(Auth::user());
        $recentSales = $this->sellerService->getRecentSales(Auth::user());

        return view('marketplace.seller.dashboard', compact(
            'listedPacks',
            'availablePacks',
            'totalSales',
            'recentSales'
        ));
    }

    public function listPack(Request $request, Pack $pack)
    {
        $this->authorize('listOnMarketplace', $pack);

        $validated = $request->validate([
            'price' => 'required|integer|min:1'
        ]);

        if (!$this->sellerService->listPack($pack, $validated['price'])) {
            return back()->with('error', 'Pack must be sealed before listing');
        }

        return back()->with('success', 'Pack listed on marketplace');
    }

    public function unlistPack(Pack $pack)
    {
        $this->authorize('removeFromMarketplace', $pack);

        if (!$this->sellerService->unlistPack($pack)) {
            return back()->with('error', 'Failed to remove pack from marketplace');
        }

        return back()->with('success', 'Pack removed from marketplace');
    }

    public function salesHistory()
    {
        $soldPacks = $this->sellerService->getSoldPacks(Auth::user());
        $totalSales = $this->sellerService->getTotalSales(Auth::user());

        return view('marketplace.seller.sales', compact('soldPacks', 'totalSales'));
    }
}
