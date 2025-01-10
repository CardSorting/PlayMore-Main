<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Create Card') }}
            </h2>
            <a href="{{ route('dashboard.cards.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                &larr; Back to Cards
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(request()->has('image_id'))
                        <livewire:card-creator.components.card-creator :image="App\Models\Gallery::findOrFail(request('image_id'))" />
                    @else
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 mb-6">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Image Selected</h3>
                            <p class="text-sm text-gray-500 mb-6">Please select an image from your gallery first.</p>
                            <a href="{{ route('dashboard.gallery.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Go to Gallery
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Container -->
    <div id="notification-container"
        x-data="{ notifications: [] }"
        @notify.window="notifications.push($event.detail); setTimeout(() => { notifications.shift() }, $event.detail.duration || 3000)"
        class="fixed bottom-0 right-0 z-50 p-4 space-y-4">
        <template x-for="notification in notifications" :key="notification.id">
            <div x-show="true"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="transform translate-x-full opacity-0"
                x-transition:enter-end="transform translate-x-0 opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="transform translate-x-0 opacity-100"
                x-transition:leave-end="transform translate-x-full opacity-0"
                class="flex items-center p-4 space-x-3 text-white rounded-lg shadow-lg"
                :class="{
                    'bg-green-500': notification.type === 'success',
                    'bg-red-500': notification.type === 'error',
                    'bg-yellow-500': notification.type === 'warning',
                    'bg-blue-500': notification.type === 'info'
                }">
                <div class="flex-1" x-text="notification.message"></div>
                <button x-show="notification.action"
                    @click="$dispatch('notify-action', notification)"
                    class="px-3 py-1 text-sm font-medium bg-white bg-opacity-25 rounded-lg hover:bg-opacity-40"
                    x-text="notification.action">
                </button>
            </div>
        </template>
    </div>

    <!-- Unsaved Changes Warning -->
    <div x-data="{ show: false }"
        x-init="window.onbeforeunload = () => show ? true : null"
        @unsaved-changes.window="show = $event.detail"
        class="fixed inset-0 z-50 hidden"
        :class="{ 'hidden': !show }">
        <div class="absolute inset-0 bg-gray-500 bg-opacity-75"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">Unsaved Changes</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    You have unsaved changes that will be lost if you leave this page. Are you sure you want to leave?
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button type="button"
                            @click="window.onbeforeunload = null; window.location.href = '{{ route('dashboard.cards.index') }}'"
                            class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                            Leave Page
                        </button>
                        <button type="button"
                            @click="show = false"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Stay on Page
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
