import './bootstrap';
import Alpine from 'alpinejs';
import { initPayPalButtons } from './components/paypal-buttons';

// Initialize Alpine.js once
if (!window.Alpine) {
    window.Alpine = Alpine;
    
    // Start Alpine after DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => Alpine.start());
    } else {
        Alpine.start();
    }
}

// Expose PayPal buttons globally
window.initPayPalButtons = initPayPalButtons;
