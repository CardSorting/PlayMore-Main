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

    public function storeMaterial(Request $request, Gallery $gallery)
    {
        $request->validate([
            'material' => 'required|string|in:' . implode(',', array_keys(config('prints.materials')))
        ]);

        // Store material in session for next step
        session(['print_order.material' => $request->material]);

        return redirect()->route('prints.checkout', ['gallery' => $gallery]);
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

    public function selectMaterial(Gallery $gallery)
    {
        // Check if size was selected
        if (!session()->has('print_order.size')) {
            return redirect()->route('prints.select-size', ['gallery' => $gallery])
                ->withErrors(['size' => 'Please select a size first']);
        }

        $selectedSize = session('print_order.size');

        // Get size details including price and features
        $sizeDetails = collect(config('prints.sizes'))->flatMap(function ($category) {
            return collect($category['sizes']);
        })->get($selectedSize);

        // Get materials from config with features
        $materials = collect(config('prints.materials'))->map(function ($material, $key) {
            return array_merge($material, [
                'id' => $key,
                'features' => [
                    'Professional-grade print quality',
                    'Archival-quality materials',
                    'UV-resistant inks',
                    'Color-calibrated reproduction'
                ]
            ]);
        });

        return view('prints.select-material', [
            'gallery' => $gallery,
            'materials' => $materials,
            'selectedSize' => $selectedSize,
            'sizeDetails' => $sizeDetails
        ]);
    }
}
