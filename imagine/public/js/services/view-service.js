class ViewService {
    constructor() {
        this.currentView = 'grid';
        this.masonryService = null;
        this.filterService = null;
    }

    initialize(masonryService, filterService) {
        this.masonryService = masonryService;
        this.filterService = filterService;
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const view = e.currentTarget.getAttribute('data-view');
                if (view) this.switchView(view);
            });
        });
    }

    switchView(view) {
        if (view !== 'grid' && view !== 'list') return;

        const gridView = document.getElementById('gridView');
        const listView = document.getElementById('listView');
        const viewButtons = document.querySelectorAll('.view-btn');
        
        this.currentView = view;
        
        // Update button states
        viewButtons.forEach(btn => {
            const isActive = btn.getAttribute('data-view') === view;
            btn.classList.toggle('active', isActive);
            btn.classList.toggle('bg-gray-100', isActive);
        });
        
        // Update view visibility
        if (view === 'grid') {
            gridView.classList.remove('hidden');
            listView.classList.add('hidden');
            // Reinitialize masonry layout
            if (this.masonryService) {
                this.masonryService.layout();
            }
        } else {
            gridView.classList.add('hidden');
            listView.classList.remove('hidden');
        }

        // Update filter service view state
        if (this.filterService) {
            this.filterService.setView(view);
        }

        // Store preference
        this.saveViewPreference(view);
    }

    saveViewPreference(view) {
        try {
            localStorage.setItem('cardViewPreference', view);
        } catch (e) {
            console.warn('Could not save view preference:', e);
        }
    }

    loadViewPreference() {
        try {
            const savedView = localStorage.getItem('cardViewPreference');
            if (savedView && (savedView === 'grid' || savedView === 'list')) {
                this.switchView(savedView);
            }
        } catch (e) {
            console.warn('Could not load view preference:', e);
        }
    }

    getCurrentView() {
        return this.currentView;
    }
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ViewService;
}

// Make available globally
window.ViewService = ViewService;
