<div id="detailsModal" 
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50"
     role="dialog"
     aria-labelledby="modalTitle"
     aria-modal="true">
    <div class="bg-white rounded-lg p-6 max-w-3xl w-full mx-4 relative shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h2 id="modalTitle" class="text-2xl font-bold text-gray-900">Image Details</h2>
            <button onclick="ImageDetailsModal.close()" 
                    class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-lg p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="rounded-lg overflow-hidden">
                <img id="modalImage" 
                     src="" 
                     alt="Selected image" 
                     class="w-full h-auto rounded-lg">
            </div>
            <div class="space-y-8">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-3">Prompt</h3>
                    <p id="modalPrompt" class="text-gray-900 text-lg leading-relaxed"></p>
                </div>
                <div class="space-y-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Settings</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-xs font-medium text-gray-500 mb-1">Aspect Ratio</p>
                            <p id="modalAspectRatio" class="text-sm font-semibold text-gray-900"></p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-xs font-medium text-gray-500 mb-1">Process Mode</p>
                            <p id="modalProcessMode" class="text-sm font-semibold text-gray-900"></p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg col-span-2">
                            <p class="text-xs font-medium text-gray-500 mb-1">Task ID</p>
                            <p id="modalTaskId" class="text-sm font-semibold text-gray-900 break-all"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ImageDetailsModal = {
        modal: null,
        modalImage: null,
        modalPrompt: null,
        modalAspectRatio: null,
        modalProcessMode: null,
        modalTaskId: null,

        initialize() {
            this.modal = document.getElementById('detailsModal');
            this.modalImage = document.getElementById('modalImage');
            this.modalPrompt = document.getElementById('modalPrompt');
            this.modalAspectRatio = document.getElementById('modalAspectRatio');
            this.modalProcessMode = document.getElementById('modalProcessMode');
            this.modalTaskId = document.getElementById('modalTaskId');

            // Close modal when clicking outside
            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal) {
                    this.close();
                }
            });

            // Close modal with escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.close();
                }
            });
        },

        show(prompt, imageUrl, aspectRatio, processMode, taskId) {
            this.modalImage.src = imageUrl;
            this.modalPrompt.textContent = prompt;
            this.modalAspectRatio.textContent = aspectRatio;
            this.modalProcessMode.textContent = processMode;
            this.modalTaskId.textContent = taskId;

            this.modal.classList.remove('hidden');
            this.modal.classList.add('flex');
            
            const focusableElements = this.modal.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            const firstFocusableElement = focusableElements[0];
            firstFocusableElement.focus();
        },

        close() {
            this.modal.classList.add('hidden');
            this.modal.classList.remove('flex');
        }
    };

    // Initialize modal when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        ImageDetailsModal.initialize();
    });

    // Make showDetails function globally available
    window.showDetails = (prompt, imageUrl, aspectRatio, processMode, taskId) => {
        ImageDetailsModal.show(prompt, imageUrl, aspectRatio, processMode, taskId);
    };
</script>
@endpush
