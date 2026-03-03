/**
 * Mobile Filter Drawer — wysuwa sidebar na stronie kategorii
 * Klonuje zawartość #secondary do drawera od razu po załadowaniu,
 * zanim parent theme JS usunie widgety z ukrytego sidebara.
 */

import { reinitVehicleSearch } from './vehicle-search.js';

const toggle = document.getElementById('mobile-filter-toggle');
const overlay = document.getElementById('mobile-filter-overlay');
const sidebar = document.getElementById('secondary');

if (toggle && overlay && sidebar && sidebar.children.length > 0) {
    // Drawer wrapper na <body>
    const drawer = document.createElement('div');
    drawer.id = 'mobile-filter-drawer';
    drawer.className = 'mobile-filter-drawer';

    // Przycisk zamknięcia
    const closeBtn = document.createElement('button');
    closeBtn.type = 'button';
    closeBtn.className = 'mobile-filter-close';
    closeBtn.setAttribute('aria-label', 'Zamknij filtry');
    closeBtn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Zamknij';
    drawer.appendChild(closeBtn);

    // Kontener na sklonowaną zawartość
    const content = document.createElement('div');
    content.className = 'mobile-filter-content';
    content.innerHTML = sidebar.innerHTML;
    content.querySelectorAll('[data-vs-init]').forEach(el => el.removeAttribute('data-vs-init'));
    drawer.appendChild(content);

    document.body.appendChild(drawer);

    // Reinicjalizacja wyszukiwarki pojazdów na sklonowanym elemencie
    reinitVehicleSearch();

    function open() {
        requestAnimationFrame(function() {
            drawer.classList.add('mobile-filter-drawer--open');
            overlay.classList.add('mobile-filter-overlay--visible');
            document.body.classList.add('mobile-filter-open');
        });
    }

    function close() {
        drawer.classList.remove('mobile-filter-drawer--open');
        overlay.classList.remove('mobile-filter-overlay--visible');
        document.body.classList.remove('mobile-filter-open');
    }

    toggle.addEventListener('click', open);
    overlay.addEventListener('click', close);
    closeBtn.addEventListener('click', close);
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && drawer.classList.contains('mobile-filter-drawer--open')) {
            close();
        }
    });
}
