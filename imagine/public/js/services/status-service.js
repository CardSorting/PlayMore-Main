class StatusService {
    constructor() {
        this.modal = document.getElementById('imageModal');
        this.modalImage = document.getElementById('modalImage');
        this.modalPrompt = document.getElementById('modalPrompt');
        this.modalAspectRatio = document.getElementById('modalAspectRatio');
        this.modalProcessMode = document.getElementById('modalProcessMode');
        this.modalTaskId = document.getElementById('modalTaskId');
        this.modalCreated = document.getElementById('modalCreated');
        this.progressBar = document.getElementById('refreshProgress');
        this.newImageButton = document.querySelector('[data-new-image-button]');
        
        this.refreshInterval = 5000; // 5 seconds
        this.refreshTimer = null;
        this.progressTimer = null;
        
        this.initialize();
    }

    initialize() {
        // Handle modal events
        this.modal?.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.closeModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModal();
            }
        });

        // Start auto-refresh if status is pending or processing
        const status = document.querySelector('[data-status]')?.dataset.status;
        if (status === 'pending' || status === 'processing') {
            this.startAutoRefresh();
            if (this.newImageButton) {
                this.newImageButton.disabled = true;
                this.newImageButton.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }
    }

    handleImageClick(event, imageUrl, data) {
        event.preventDefault();
        event.stopPropagation();
        this.openModal(imageUrl, data);
        return false;
    }

    openModal(imageUrl, data) {
        if (!this.modal) return;

        this.modalImage.src = imageUrl;
        this.modalPrompt.textContent = data.input.prompt || 'Not available';
        this.modalAspectRatio.textContent = data.input.aspect_ratio || '1:1';
        this.modalProcessMode.textContent = data.input.process_mode || 'relax';
        this.modalTaskId.textContent = data.task_id || 'Not available';
        
        const createdDate = new Date(data.meta.created_at || null);
        this.modalCreated.textContent = createdDate.toLocaleString() || 'Not available';

        this.modal.classList.remove('hidden');
        this.modal.classList.add('flex');
    }

    closeModal() {
        if (!this.modal) return;
        
        this.modal.classList.add('hidden');
        this.modal.classList.remove('flex');
    }

    startAutoRefresh() {
        // Clear any existing timers
        if (this.refreshTimer) clearTimeout(this.refreshTimer);
        if (this.progressTimer) clearInterval(this.progressTimer);

        // Start progress bar animation
        if (this.progressBar) {
            this.progressBar.style.width = '0%';
            this.progressBar.style.transition = `width ${this.refreshInterval}ms linear`;
            requestAnimationFrame(() => {
                this.progressBar.style.width = '100%';
            });
        }

        // Set up the refresh timer
        this.refreshTimer = setTimeout(async () => {
            try {
                const response = await fetch(window.location.href);
                const html = await response.text();
                const parser = new DOMParser();
                const newDoc = parser.parseFromString(html, 'text/html');
                
                // Update only the main content area to prevent page flicker
                const currentContent = document.querySelector('[data-status-content]');
                const newContent = newDoc.querySelector('[data-status-content]');
                
                if (currentContent && newContent) {
                    currentContent.innerHTML = newContent.innerHTML;
                    
                    // Reinitialize the service for the new content
                    this.initialize();
                }
            } catch (error) {
                console.error('Failed to refresh status:', error);
                // Retry on error
                this.startAutoRefresh();
            }
        }, this.refreshInterval);
    }
}

// Initialize the service
document.addEventListener('DOMContentLoaded', () => {
    window.statusService = new StatusService();
});