@props(['categories'])

<div class="border-b border-gray-200">
    <nav class="-mb-px flex flex-wrap justify-center gap-2 sm:gap-4 max-w-4xl mx-auto px-4" aria-label="Size Categories">
        @foreach($categories as $categoryData)
            <button type="button"
                    @click="activeTab = '{{ $categoryData['category'] }}'"
                    :class="{ 
                        'border-indigo-500 text-indigo-600': activeTab === '{{ $categoryData['category'] }}',
                        'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== '{{ $categoryData['category'] }}'
                    }"
                    class="group inline-flex flex-col items-center border-b-2 py-3 px-3 text-center transition-all duration-200"
                    role="tab"
                    :aria-selected="activeTab === '{{ $categoryData['category'] }}'"
                    :aria-controls="'tab-{{ $categoryData['category'] }}'">
                <div class="flex items-center justify-center">
                    <svg class="mr-2 h-5 w-5" 
                         :class="{
                             'text-indigo-600': activeTab === '{{ $categoryData['category'] }}',
                             'text-gray-400 group-hover:text-gray-500': activeTab !== '{{ $categoryData['category'] }}'
                         }"
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke="currentColor" 
                         stroke-width="2" 
                         x-html="utils.getCategoryIcon('{{ $categoryData['category'] }}')">
                    </svg>
                    <span class="text-sm font-medium">{{ $categoryData['category'] }}</span>
                    <span class="ml-2 rounded-full px-2 py-0.5 text-xs font-medium"
                          :class="{
                              'bg-indigo-100 text-indigo-700': activeTab === '{{ $categoryData['category'] }}',
                              'bg-gray-100 text-gray-600': activeTab !== '{{ $categoryData['category'] }}'
                          }">
                        {{ $categoryData['sizes']->count() }}
                    </span>
                </div>
                <span class="mt-1 text-xs text-gray-500">{{ $categoryData['sizes']->keys()->first() }} - {{ $categoryData['sizes']->keys()->last() }}</span>
            </button>
        @endforeach
    </nav>
</div>
