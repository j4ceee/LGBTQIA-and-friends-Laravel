import './bootstrap';

import Alpine from 'alpinejs';

import.meta.glob([
    '../img/**',
    '../fonts/**',
]);

window.Alpine = Alpine;

Alpine.start();
