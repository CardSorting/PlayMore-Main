class MasonryService {
    constructor(gridSelector = '.cards-masonry') {
        this.grid = document.querySelector(gridSelector);
        this.instance = null;
        this.options = {
            itemSelector: '.card-item',
            columnWidth: '.card-item',
            percentPosition: true,
            transitionDuration: '0.3s'
        };
    }

    initialize() {
        if (!this.grid) return;
        
        this.instance = new Masonry(this.grid, this.options);
        this.grid.style.opacity = '1';
        
        // Initialize 3D effects for cards after masonry layout
        this.initializeCardEffects();
    }

    layout() {
        if (!this.instance) return;
        this.instance.layout();
    }

    reloadItems() {
        if (!this.instance) return;
        this.instance.reloadItems();
    }

    destroy() {
        if (!this.instance) return;
        this.instance.destroy();
    }

    initializeCardEffects() {
        const cards = document.querySelectorAll('.mtg-card');
        cards.forEach(card => {
            if (card.closest('[data-rarity="Rare"]') || card.closest('[data-rarity="Mythic Rare"]')) {
                new MTGCard3DTiltEffect(card);
            }
        });
    }

    updateLayout(items) {
        if (!this.instance) return;
        
        items.forEach(item => this.grid.appendChild(item));
        this.reloadItems();
        this.layout();
    }
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = MasonryService;
}

// Make available globally
window.MasonryService = MasonryService;
