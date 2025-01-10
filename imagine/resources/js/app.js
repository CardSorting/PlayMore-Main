import './bootstrap';
import Alpine from 'alpinejs';
import sizeSelectorData from './components/size-selector.js';

// Register Alpine components
window.Alpine = Alpine;
window.sizeSelectorData = sizeSelectorData;

// Start Alpine
Alpine.start();
