@extends('layouts.gallery')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Page Header -->
        <div class="bg-white border-b shadow-sm">
            <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <x-gallery-header />
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-xl shadow-lg ring-1 ring-black/5 overflow-hidden">
                <div class="px-8 py-6">
                    <x-gallery-tabs />
                    <div class="mt-4 pb-6">
                        <x-gallery-filter />
                    </div>

                    @if($images->isEmpty())
                        <x-empty-state type="images" />
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 transition-all duration-200 ease-in-out">
                            @foreach($images as $image)
                                <x-image-grid-item :image="$image" />
                            @endforeach
                        </div>

                        <div class="mt-12 pt-8 border-t border-gray-100">
                            {{ $images->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <x-image-details-modal />
@endsection
