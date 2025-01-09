import './bootstrap';

// Disable Livewire's bundled Alpine.js
window.deferLoadingAlpine = function(callback) {
    window.addEventListener('alpine:init', callback);
};

// Initialize our Alpine instance
import Alpine from 'alpinejs';
window.Alpine = Alpine;

// Start Alpine after Livewire is loaded
document.addEventListener('livewire:init', () => {
    Alpine.start();
    
    // Initialize Livewire components with our Alpine instance
    window.Livewire.hook('element.initialized', (el, component) => {
        if (el.__x) return;
        Alpine.initializeComponent(el);
    });
});
