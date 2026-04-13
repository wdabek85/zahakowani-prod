/**
 * Category Filter — accordion toggle (desktop + mobile)
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Delegated — works on original sidebar AND cloned mobile drawer
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.cat-filter__toggle');
            if (!btn) return;

            e.preventDefault();
            var item = btn.closest('.cat-filter__item');
            var children = item.querySelector(':scope > .cat-filter__children');
            if (!children) return;

            var expanded = btn.getAttribute('aria-expanded') === 'true';

            if (expanded) {
                children.style.display = 'none';
                btn.setAttribute('aria-expanded', 'false');
                item.classList.remove('is-expanded');
            } else {
                children.style.display = '';
                btn.setAttribute('aria-expanded', 'true');
                item.classList.add('is-expanded');
            }
        });
    });
})();
