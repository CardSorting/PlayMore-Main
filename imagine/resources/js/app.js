import './bootstrap';
import Alpine from 'alpinejs';
import { utils } from './utils/print-dimensions';

window.Alpine = Alpine;
window.utils = utils;

// Register Alpine components before starting
document.addEventListener('alpine:init', () => {
    Alpine.data('sizeSelectorData', (initialCategory = '', initialSize = '') => ({
        activeTab: initialCategory,
        selectedSize: initialSize,
        
        init() {
            // Initialize with current size from hidden input if it exists
            const sizeInput = document.querySelector('input[name="size"]');
            if (sizeInput && sizeInput.value) {
                this.selectedSize = sizeInput.value;
            }
        }
    }));

    Alpine.data('quantitySelector', () => ({
        selectedQuantity: null,
        unitPrice: null,
        presetData: {},

        init() {
            const container = this.$el;
            this.selectedQuantity = parseInt(container.dataset.initialQuantity) || 1;
            this.unitPrice = parseInt(container.dataset.unitPrice);
            this.presetData = JSON.parse(container.dataset.presets || '{}');
        },

        getPresetData() {
            const preset = Object.entries(this.presetData).find(([amount]) => parseInt(amount) === parseInt(this.selectedQuantity));
            return preset ? preset[1] : null;
        },

        calculateFinalPrice() {
            const preset = this.getPresetData();
            if (preset) {
                return preset.discountedPrice;
            }
            return parseInt(this.selectedQuantity) * parseInt(this.unitPrice);
        }
    }));
});

Alpine.start();
