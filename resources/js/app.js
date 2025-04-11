import './bootstrap';

import Alpine from 'alpinejs';

import.meta.glob([
    '../img/**',
    '../fonts/**',
]);

window.Alpine = Alpine;

Alpine.start();

window.onload = function() {
    let logoutBtns = [document.getElementById('logoutBtnDesktop'), document.getElementById('logoutBtnMobile')];

    logoutBtns.forEach(function (btn) {
        if (btn) {
            btn.addEventListener('click', function (event) {
                event.preventDefault();
                this.closest('form').submit();
            });
        }
    });
}
