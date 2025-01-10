import './bootstrap';
import Alpine from 'alpinejs';
import quantitySelector from './components/quantity-selector';

// Register Alpine components
Alpine.data('quantitySelector', quantitySelector);

window.Alpine = Alpine;
Alpine.start();
