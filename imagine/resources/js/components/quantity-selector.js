export default function quantitySelector() {
    return {
        selectedQuantity: null,
        unitPrice: null,
        presetData: {},

        init() {
            // Get initial data from PHP
            const container = document.querySelector('[x-data="quantitySelector"]');
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
    };
}
