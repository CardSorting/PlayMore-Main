import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Register Alpine components before starting
document.addEventListener('alpine:init', () => {
    Alpine.data('quantitySelector', (unitPrice, presetData = {}) => ({
        selectedQuantity: null,
        unitPrice: unitPrice,
        presetData: presetData,
        customQuantityValid: false,

        init() {
            // Initialize with default quantity
            this.selectedQuantity = 1;
            
            this.$nextTick(() => {
                // Initialize with current quantity from hidden input if it exists
                const quantityInput = document.querySelector('input[name="quantity"]');
                if (quantityInput) {
                    this.selectedQuantity = parseInt(quantityInput.value) || 1;
                }

                // Update price display
                const finalPriceInput = document.getElementById('final_price');
                if (finalPriceInput) {
                    finalPriceInput.value = Math.round(this.calculateTotal() * 100);
                }

                // Setup custom quantity input handler
                const customQuantityInput = document.getElementById('custom_quantity');
                if (customQuantityInput) {
                    customQuantityInput.addEventListener('keypress', (e) => {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            if (this.customQuantityValid) {
                                this.applyCustomQuantity();
                            }
                        }
                    });
                }

                // Setup form submission handler
                if (this.$refs.quantityForm) {
                    this.$refs.quantityForm.addEventListener('submit', (e) => {
                        e.preventDefault();
                        const finalPrice = Math.round(this.calculateTotal() * 100);
                        const finalPriceInput = document.getElementById('final_price');
                        if (finalPriceInput) {
                            finalPriceInput.value = finalPrice;
                        }
                        setTimeout(() => e.target.submit(), 0);
                    });
                }
            });

            // Watch for quantity changes
            this.$watch('selectedQuantity', () => {
                this.addHighlightEffect();
                const customQuantityInput = document.getElementById('custom_quantity');
                if (customQuantityInput) {
                    customQuantityInput.value = '';
                }
            });
        },

        calculateTotal() {
            if (this.presetData && this.presetData[this.selectedQuantity]) {
                return this.presetData[this.selectedQuantity].discountedPrice / 100;
            }
            return (this.selectedQuantity * this.unitPrice) / 100;
        },

        validateCustomQuantity(input) {
            const value = parseInt(input.value);
            const maxQuantity = parseInt(input.max);
            
            if (!isNaN(value) && value >= 1 && value <= maxQuantity) {
                input.classList.remove('border-red-300');
                input.classList.add('border-green-300');
                this.customQuantityValid = true;
                return true;
            }
            
            input.classList.remove('border-green-300');
            input.classList.add('border-red-300');
            this.customQuantityValid = false;
            return false;
        },

        applyCustomQuantity() {
            if (!this.customQuantityValid) return;

            const input = document.getElementById('custom_quantity');
            const value = parseInt(input.value);
            
            if (value && value >= 1 && value <= input.max) {
                this.selectedQuantity = value;
                
                // Success feedback
                const btn = document.getElementById('apply_custom');
                btn.classList.add('bg-green-500');
                btn.querySelector('svg').classList.add('animate-bounce');
                setTimeout(() => {
                    btn.classList.remove('bg-green-500');
                    btn.querySelector('svg').classList.remove('animate-bounce');
                }, 1000);
            }
        },

        addHighlightEffect() {
            const summary = document.querySelector('.bg-gray-50');
            if (summary) {
                summary.classList.add('ring-2', 'ring-indigo-200');
                setTimeout(() => {
                    summary.classList.remove('ring-2', 'ring-indigo-200');
                }, 800);
            }
        }
    }));
});

Alpine.start();
