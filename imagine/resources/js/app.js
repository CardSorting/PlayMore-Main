import './bootstrap';
import Alpine from 'alpinejs';

// Prevent multiple Alpine instances
if (!window.Alpine) {
    window.Alpine = Alpine;
    
    // Wait for document to be ready
    document.addEventListener('DOMContentLoaded', () => {
        // Start Alpine only if not already started
        if (!document.querySelector('[x-data]')?.__x) {
            Alpine.start();
        }
    });
}
