<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PrintOrderController extends Controller
{
    public function selectSize(Gallery $gallery)
    {
        // Get sizes from config and transform into collection
        $sizes = collect(config('prints.sizes'))->map(function ($categoryData) {
            return [
                'category' => $categoryData['category'],
                'sizes' => collect($categoryData['sizes'])->map(function ($size, $name) use ($categoryData) {
                    return array_merge($size, [
                        'name' => $name,
                        'category' => $categoryData['category']
                    ]);
                })
            ];
        });
        
        return view('prints.select-size', [
            'gallery' => $gallery,
            'sizes' => $sizes
        ]);
    }

    public function storeSize(Request $request, Gallery $gallery)
    {
        $request->validate([
            'size' => 'required|string'
        ]);

        // Validate size exists in config
        $sizeExists = collect(config('prints.sizes'))->some(function ($category) use ($request) {
            return collect($category['sizes'])->has($request->size);
        });

        if (!$sizeExists) {
            return back()->withErrors(['size' => 'Invalid size selected']);
        }

        // Store size in session for next step
        session(['print_order.size' => $request->size]);

        return redirect()->route('prints.select-material', ['gallery' => $gallery]);
    }
}
