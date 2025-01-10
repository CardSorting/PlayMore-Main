@props(['reviews', 'user'])

<div 
    x-data="reviewsComponent({ 
        userName: '{{ $user->name }}',
        orders: {{ json_encode(Auth::check() ? Auth::user()->printOrders()->completed()->where('user_id', $user->id)->with('gallery')->get() : []) }}
    })"
    class="bg-white shadow rounded-lg p-6"
>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold">Trusted by Art Collectors</h2>
        <div class="text-sm text-gray-600">
            <span class="font-semibold">{{ $reviews->total() }}</span> verified reviews
        </div>
    </div>

    <!-- High Trust Badges -->
    <div class="flex items-center justify-center space-x-6 mb-8 py-4 border-y border-gray-100">
        <div class="flex items-center space-x-2">
            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <div class="text-sm">
                <div class="font-semibold">Secure Payments</div>
                <div class="text-gray-500">SSL Encrypted</div>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
            <div class="text-sm">
                <div class="font-semibold">Quality Assured</div>
                <div class="text-gray-500">100% Guaranteed</div>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm">
                <div class="font-semibold">Fast Shipping</div>
                <div class="text-gray-500">2-5 Business Days</div>
            </div>
        </div>
    </div>

    <!-- Professional Verification -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-blue-900">Verified Professional Artist</h3>
                    <p class="text-sm text-blue-700">All artworks are original and professionally produced</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">Professional</span>
                <span class="px-3 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Verified</span>
            </div>
        </div>
    </div>

    <!-- Trust Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <p class="text-sm text-blue-700">
                    <span class="font-medium">{{ rand(2, 5) }} people</span> purchased in the last hour
                </p>
            </div>
        </div>

        <div class="bg-green-50 border border-green-100 rounded-lg p-4">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <p class="text-sm text-green-700">
                    <span class="font-medium">{{ number_format($reviews->avg('rating'), 1) }}/5.0</span> average rating
                </p>
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-100 rounded-lg p-4">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <p class="text-sm text-purple-700">
                    <span class="font-medium">{{ $reviews->where('rating', 5)->count() }}</span> 5-star reviews
                </p>
            </div>
        </div>
    </div>

    <!-- Trust & Guarantee Banner -->
    <div class="bg-gradient-to-r from-yellow-50 to-amber-50 rounded-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <svg class="w-12 h-12 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-amber-900">100% Satisfaction Guarantee</h3>
                    <p class="text-amber-800">30-day money-back guarantee for all purchases</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span class="text-amber-800 font-medium">{{ number_format($reviews->where('rating', '>=', 4)->count() / max($reviews->count(), 1) * 100, 0) }}% Positive Reviews</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Form -->
    @auth
        <div x-show="canReview" class="mb-8">
            <form @submit.prevent="submitReview" class="space-y-4">
                <!-- Order Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Order</label>
                    <select x-model="form.print_order_id" class="w-full rounded-md border-gray-300">
                        <option value="">Select an order to review</option>
                        <template x-for="order in unreviewedOrders" :key="order.id">
                            <option :value="order.id" x-text="'Order #' + order.id + ' - ' + order.gallery.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Rating -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                    <div class="flex items-center">
                        <template x-for="i in 5" :key="i">
                            <button 
                                type="button"
                                @click="form.rating = i"
                                class="p-1"
                            >
                                <svg class="w-6 h-6" :class="i <= form.rating ? 'text-yellow-400' : 'text-gray-300'"
                                    fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Comment -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Comment</label>
                    <textarea 
                        x-model="form.comment"
                        rows="3"
                        class="w-full rounded-md border-gray-300"
                        placeholder="Share your experience..."
                    ></textarea>
                </div>

                <div class="flex justify-end">
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                        :disabled="isSubmitting"
                    >
                        <span x-show="!isSubmitting">Submit Review</span>
                        <span x-show="isSubmitting">Submitting...</span>
                    </button>
                </div>
            </form>
        </div>
    @endauth

    <!-- Reviews List -->
    <div class="space-y-6">
        @forelse ($reviews as $review)
            <div class="pb-6 {{ !$loop->last ? 'border-b border-gray-200' : '' }}"
                x-data="{ isEditing: false, editForm: { rating: {{ $review->rating }}, comment: '{{ $review->comment }}' } }"
            >
                <!-- Recent Purchase Badge -->
                @if($review->created_at->gt(now()->subDays(7)))
                    <div class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full mb-2">
                        Recent Purchase
                    </div>
                @endif
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <!-- View Mode -->
                        <div x-show="!isEditing">
                            <div class="flex items-center mb-2">
                                <div class="flex items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm font-medium text-gray-900">Verified Purchase</span>
                                </div>
                            </div>
                            <p class="text-gray-800 mb-2">{{ $review->comment }}</p>
                            <div class="flex items-center text-sm text-gray-600">
                                <span class="font-medium">{{ $review->rater->name }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <!-- Edit Mode -->
                        <div x-show="isEditing" class="space-y-4">
                            <div>
                                <div class="flex items-center">
                                    <template x-for="i in 5" :key="i">
                                        <button 
                                            type="button"
                                            @click="editForm.rating = i"
                                            class="p-1"
                                        >
                                            <svg class="w-6 h-6" :class="i <= editForm.rating ? 'text-yellow-400' : 'text-gray-300'"
                                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            <textarea 
                                x-model="editForm.comment"
                                rows="3"
                                class="w-full rounded-md border-gray-300"
                            ></textarea>
                            <div class="flex justify-end space-x-2">
                                <button 
                                    @click="isEditing = false"
                                    class="px-3 py-1 text-sm text-gray-600 hover:text-gray-800"
                                >
                                    Cancel
                                </button>
                                <button 
                                    @click="updateReview({{ $review->id }}, editForm)"
                                    class="px-3 py-1 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700"
                                >
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @auth
                        @if (Auth::id() === $review->rated_by)
                            <div x-show="!isEditing" class="flex space-x-2">
                                <button 
                                    @click="isEditing = true"
                                    class="text-sm text-gray-600 hover:text-gray-800"
                                >
                                    Edit
                                </button>
                                <button 
                                    @click="deleteReview({{ $review->id }})"
                                    class="text-sm text-red-600 hover:text-red-800"
                                >
                                    Delete
                                </button>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        @empty
            <p class="text-gray-600">No reviews yet.</p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $reviews->links() }}
    </div>
</div>

@push('scripts')
<script>
function reviewsComponent(config) {
    return {
        userName: config.userName,
        orders: config.orders,
        form: {
            print_order_id: '',
            rating: 0,
            comment: ''
        },
        isSubmitting: false,
        
        get unreviewedOrders() {
            return this.orders.filter(order => !order.rating);
        },

        get canReview() {
            return this.unreviewedOrders.length > 0;
        },

        async submitReview() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;

            try {
                const response = await fetch(`/${this.userName}/reviews`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(this.form)
                });

                if (!response.ok) {
                    throw new Error('Failed to submit review');
                }

                // Reset form and refresh page
                this.form = { print_order_id: '', rating: 0, comment: '' };
                window.location.reload();
            } catch (error) {
                console.error('Error submitting review:', error);
                alert('Failed to submit review. Please try again.');
            } finally {
                this.isSubmitting = false;
            }
        },

        async updateReview(id, form) {
            try {
                const response = await fetch(`/${this.userName}/reviews/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(form)
                });

                if (!response.ok) {
                    throw new Error('Failed to update review');
                }

                window.location.reload();
            } catch (error) {
                console.error('Error updating review:', error);
                alert('Failed to update review. Please try again.');
            }
        },

        async deleteReview(id) {
            if (!confirm('Are you sure you want to delete this review?')) return;

            try {
                const response = await fetch(`/${this.userName}/reviews/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to delete review');
                }

                window.location.reload();
            } catch (error) {
                console.error('Error deleting review:', error);
                alert('Failed to delete review. Please try again.');
            }
        }
    }
}
</script>
@endpush
