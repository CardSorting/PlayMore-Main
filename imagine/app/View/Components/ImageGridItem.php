<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ImageGridItem extends Component
{
    public $image;
    public $hasCard;

    public function __construct($image)
    {
        $this->image = $image;
        $this->hasCard = collect(session('cards', []))->contains(function ($card) {
            return $card['image_url'] === $this->image->image_url;
        });
    }

    public function render()
    {
        return view('components.image-grid-item');
    }
}
