import './bootstrap';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

// Livewire 3 automatically starts Alpine if it finds it on the page.
// We only call Alpine.start() manually if Livewire is NOT present (e.g., in the Admin panel).
if (typeof window.Livewire === 'undefined') {
    Alpine.start();
}
