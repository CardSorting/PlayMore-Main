@extends('layouts.gallery')

@section('header')
    <x-gallery-header />
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-gallery-tabs 
                :images-count="$images->total()"
                :cards-count="$cards->total()"
                :active-tab="request()->query('tab', 'images')">
                <!-- Images Tab Content -->
                <x-slot name="images">
                    @if($images->isEmpty())
                        <x-empty-state type="images" />
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($images as $image)
                                <x-image-grid-item :image="$image" />
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $images->links() }}
                        </div>
                    @endif
                </x-slot>

                <!-- Cards Tab Content -->
                <x-slot name="cards">
                    @if(isset($cards) && $cards->isNotEmpty())
                        <x-collection-stats :cards="$cards" />
                        <x-card-filters />
                        
                        <!-- Card Views Container -->
                        <div class="relative">
                            <!-- Grid View -->
                            <div id="gridView" class="cards-masonry grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" 
                                 style="opacity: 0; transition: opacity 0.3s ease-in-out;"
                                 x-data="{ hoveredCard: null }">
                                @foreach($cards as $card)
                                    <x-card-grid-item :card="$card" />
                                @endforeach
                            </div>

                            <!-- List View -->
                            <div id="listView" class="hidden space-y-4">
                                @foreach($cards as $card)
                                    <x-card-list-item :card="$card" />
                                @endforeach
                            </div>
                        </div>

                        @if(isset($cards))
                            <div class="mt-8">
                                {{ $cards->links() }}
                            </div>
                        @endif
                    @else
                        <x-empty-state type="cards" />
                    @endif
                </x-slot>
            </x-gallery-tabs>
        </div>
    </div>

    <!-- Modals -->
    <x-image-details-modal />
    <x-card-details-modal />
@endsection
