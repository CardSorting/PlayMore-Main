<x-public-layout>
    <!-- Store Header -->
    <x-store.store-header 
        :user="$user"
        :sellerInfo="$sellerInfo"
        :stats="$stats"
    />

    <!-- Store Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Store Tabs -->
        <x-store.store-tabs 
            :activeTab="$activeTab"
            :totalPrints="$totalPrints"
            :totalCards="$totalCards"
        />

        <!-- Search and Filters -->
        <x-store.store-filters 
            :filters="$filters"
            :activeTab="$activeTab"
            :totalItems="$activeTab === 'prints' ? $totalPrints : $totalCards"
        />

        <!-- Items Grid -->
        <x-store.store-grid :items="$items" />

        <!-- Reviews Section -->
        <div class="mt-12">
            <x-store.store-reviews 
                :reviews="$reviews"
                :user="$user"
            />
        </div>
    </div>
</x-public-layout>
