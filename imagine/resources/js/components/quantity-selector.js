export default function quantitySelector(unitPrice) {
    return {
        selectedQuantity: null,
        unitPrice: unitPrice,
        customQuantityValid: false,

        init() {
            // Initialize with current quantity from hidden input
            const quantityInput = document.querySelector('input[name="quantity"]');
            this.selectedQuantity = parseInt(quantityInput?.value) || 1;
            
            // Update price display immediately
            this.$nextTick(() => {
                document.getElementById('final_price').value = Math.round(this.calculateTotal() * 100);
            });

            // Handle Enter key in custom quantity input
            document.getElementById('custom_quantity').addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (this.customQuantityValid) {
                        this.applyCustomQuantity();
                    }
                }
            });

            // Handle form submission
            this.$refs.quantityForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const finalPrice = Math.round(this.calculateTotal() * 100);
                document.getElementById('final_price').value = finalPrice;
                // Small delay to ensure Alpine state is updated
                setTimeout(() => e.target.submit(), 0);
            });

            // Add highlight effect when quantity changes
            this.$watch('selectedQuantity', () => {
                this.addHighlightEffect();
                document.getElementById('custom_quantity').value = ''; // Clear custom input
            });
        },

        calculateTotal() {
            // Calculate price based on selected quantity and unit price
            const total = (this.selectedQuantity * this.unitPrice) / 100;
            return Math.round(total * 100) / 100;
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
            summary.classList.add('ring-2', 'ring-indigo-200');
            setTimeout(() => {
                summary.classList.remove('ring-2', 'ring-indigo-200');
            }, 800);
        }
    };
}
