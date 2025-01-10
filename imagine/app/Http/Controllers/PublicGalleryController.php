<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Gallery;
use App\Services\StoreService;
use App\ViewModels\StoreViewModel;
use App\ViewModels\StoreItemViewModel;
use Illuminate\Http\Request;

class PublicGalleryController extends Controller
{
    private StoreViewModel $storeViewModel;
    private StoreItemViewModel $storeItemViewModel;

    public function __construct(StoreViewModel $storeViewModel, StoreItemViewModel $storeItemViewModel)
    {
        $this->storeViewModel = $storeViewModel;
        $this->storeItemViewModel = $storeItemViewModel;
    }

    public function store(Request $request, User $user)
    {
        // Update user's last active timestamp
        $user->updateLastActive();

        // Return view with data from view model
        return view('public.gallery.store', $this->storeViewModel->forStore($request, $user));
    }

    public function show(User $user, Gallery $gallery)
    {
        return view('public.gallery.show', $this->storeItemViewModel->forShow($user, $gallery));
    }
}
