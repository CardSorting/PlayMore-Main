<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold">Create New Card Pack</h2>
                    </div>

                    <form method="POST" action="{{ route('packs.store') }}" class="max-w-2xl">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="name" value="Pack Name" />
                            <x-text-input id="name" 
                                         name="name" 
                                         type="text" 
                                         class="mt-1 block w-full" 
                                         required 
                                         autofocus
                                         value="{{ old('name') }}" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" value="Description (Optional)" />
                            <textarea id="description"
                                      name="description"
                                      class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                      rows="3">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="card_limit" value="Card Limit" />
                            <select id="card_limit"
                                    name="card_limit"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ old('card_limit', 10) == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ Str::plural('Card', $i) }}
                                    </option>
                                @endfor
                            </select>
                            <x-input-error :messages="$errors->get('card_limit')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>Create Pack</x-primary-button>
                            <a href="{{ route('packs.index') }}" class="text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
