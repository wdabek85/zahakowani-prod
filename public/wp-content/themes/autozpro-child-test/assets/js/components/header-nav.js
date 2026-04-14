/**
 * Header Navigation — child theme
 * Sticky header, dropdown toggles, mobile menu bridge
 */
(function () {
    'use strict';

    /* ─── Dropdown helper ─── */
    function setupDropdown(triggerSelector, dropdownSelector, opts) {
        var wrappers = document.querySelectorAll(triggerSelector);
        wrappers.forEach(function (trigger) {
            var dropdown = trigger.parentElement.querySelector(dropdownSelector);
            if (!dropdown) return;

            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var isOpen = dropdown.classList.contains('is-open');
                closeAllDropdowns();
                if (!isOpen) {
                    dropdown.classList.add('is-open');
                    trigger.setAttribute('aria-expanded', 'true');
                }
            });
        });
    }

    function closeAllDropdowns() {
        document.querySelectorAll('.is-open').forEach(function (el) {
            if (el.closest('.site-header')) {
                el.classList.remove('is-open');
            }
        });
        document.querySelectorAll('[aria-expanded="true"]').forEach(function (el) {
            if (el.closest('.site-header')) {
                el.setAttribute('aria-expanded', 'false');
            }
        });
    }


    /* ─── Mobile drill-down nav ─── */
    function initMobileNav() {
        var overlay = document.getElementById('mobile-nav-overlay');
        var toggle = document.querySelector('.mobile-menu-toggle');
        if (!overlay || !toggle) return;

        var closeBtn = overlay.querySelector('.mobile-nav-close-btn');
        var currentSlide = overlay.querySelector('.mobile-nav-slide.is-active');

        function open() {
            overlay.classList.add('is-open');
            overlay.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function close() {
            overlay.classList.remove('is-open');
            overlay.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            // Reset to root after close
            setTimeout(function () {
                goToSlide('mobile-slide-root', false);
            }, 300);
        }

        function goToSlide(targetId, animate) {
            var target = document.getElementById(targetId);
            if (!target) return;

            // Remove all states
            overlay.querySelectorAll('.mobile-nav-slide').forEach(function (s) {
                s.classList.remove('is-active', 'is-exiting-left', 'is-exiting-right');
            });

            if (animate && currentSlide) {
                var currentLevel = parseInt(currentSlide.getAttribute('data-level') || 0);
                var targetLevel = parseInt(target.getAttribute('data-level') || 0);

                if (targetLevel > currentLevel) {
                    // Going deeper — current exits left
                    currentSlide.classList.add('is-exiting-left');
                } else {
                    // Going back — current exits right
                    currentSlide.classList.add('is-exiting-right');
                }
            }

            target.classList.add('is-active');
            target.scrollTop = 0;
            currentSlide = target;
        }

        // Open / close
        toggle.addEventListener('click', open);
        closeBtn.addEventListener('click', close);

        // Click outside panel
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) close();
        });

        // Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.classList.contains('is-open')) close();
        });

        // Drill-down: click link with data-slide
        overlay.addEventListener('click', function (e) {
            var link = e.target.closest('[data-slide]');
            if (!link) return;
            e.preventDefault();
            goToSlide(link.getAttribute('data-slide'), true);
        });

        // Back button
        overlay.addEventListener('click', function (e) {
            var back = e.target.closest('.mobile-nav-back');
            if (!back) return;
            e.preventDefault();
            goToSlide(back.getAttribute('data-back'), true);
        });
    }

    /* ─── Mega menu ─── */
    function initMegaMenu() {
        var trigger = document.querySelector('.mega-menu-trigger');
        var menu = document.getElementById('mega-menu');
        if (!trigger || !menu) return;

        // Toggle on click
        trigger.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var isOpen = menu.classList.contains('is-open');
            closeAllDropdowns();
            if (!isOpen) {
                menu.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');
            }
        });

        // Sidebar hover → switch panels
        var sidebarItems = menu.querySelectorAll('.mega-sidebar-item');
        sidebarItems.forEach(function (item) {
            item.addEventListener('mouseenter', function () {
                // Deactivate all
                menu.querySelectorAll('.mega-sidebar-item.is-active').forEach(function (el) {
                    el.classList.remove('is-active');
                });
                menu.querySelectorAll('.mega-content-panel.is-active').forEach(function (el) {
                    el.classList.remove('is-active');
                });

                // Activate hovered
                item.classList.add('is-active');
                var panelId = item.getAttribute('data-panel');
                var panel = document.getElementById(panelId);
                if (panel) panel.classList.add('is-active');
            });
        });
    }

    /* ─── Nav mega dropdown (Kompletne Haki hover) ─── */
    function initNavMega() {
        var dropdown = document.getElementById('nav-mega-haki');
        if (!dropdown) return;

        // Find the nav menu item that contains "Kompletne Haki" or similar rich category
        var navItems = document.querySelectorAll('.nav-menu > li');
        var triggerItem = null;

        navItems.forEach(function (li) {
            var link = li.querySelector('a');
            if (!link) return;
            var href = link.getAttribute('href') || '';
            // Match by URL containing the category slug
            if (href.indexOf('kompletne-haki') !== -1 || href.indexOf('haki-holownicze') !== -1) {
                triggerItem = li;
            }
        });

        if (!triggerItem) return;

        var hideTimer = null;

        function show() {
            clearTimeout(hideTimer);
            // Hide default submenu
            var subMenu = triggerItem.querySelector('.sub-menu');
            if (subMenu) subMenu.style.display = 'none';
            dropdown.classList.add('is-open');
        }

        function hide() {
            hideTimer = setTimeout(function () {
                dropdown.classList.remove('is-open');
            }, 200);
        }

        triggerItem.addEventListener('mouseenter', show);
        triggerItem.addEventListener('mouseleave', hide);
        dropdown.addEventListener('mouseenter', function () { clearTimeout(hideTimer); });
        dropdown.addEventListener('mouseleave', hide);

        // ─── Brand hover with intent detection (Amazon-style) ───
        var brands = dropdown.querySelectorAll('.nav-mega-brand');
        var cardsWrap = dropdown.querySelector('.nav-mega-cards-wrap');
        var modelsCol = dropdown.querySelector('.nav-mega-models');
        var brandsCol = dropdown.querySelector('.nav-mega-brands');

        // Initialize default card as active
        if (cardsWrap) {
            var defaultCardKey = cardsWrap.getAttribute('data-default-card');
            var defaultCard = cardsWrap.querySelector('[data-card-for="' + defaultCardKey + '"]');
            if (defaultCard) defaultCard.classList.add('is-active');
        }

        var switchTimer = null;
        var lastMouseX = 0;
        var lastMouseY = 0;
        var lastMouseTime = 0;

        // Track mouse position globally while menu is open
        dropdown.addEventListener('mousemove', function (e) {
            lastMouseX = e.clientX;
            lastMouseY = e.clientY;
            lastMouseTime = Date.now();
        });

        function switchBrand(brand) {
            dropdown.querySelectorAll('.nav-mega-brand.is-active').forEach(function (el) {
                el.classList.remove('is-active');
            });
            brand.classList.add('is-active');

            var brandId = brand.getAttribute('data-brand-id');
            dropdown.querySelectorAll('.nav-mega-models-panel.is-active').forEach(function (el) {
                el.classList.remove('is-active');
            });
            var targetPanel = dropdown.querySelector('[data-brand-panel="' + brandId + '"]');
            if (targetPanel) targetPanel.classList.add('is-active');

            // Switch featured card
            if (cardsWrap) {
                var featuredId = brand.getAttribute('data-featured-id');
                dropdown.querySelectorAll('.nav-mega-card-item.is-active').forEach(function (el) {
                    el.classList.remove('is-active');
                });
                var targetCard = cardsWrap.querySelector('[data-card-for="card-' + featuredId + '"]');
                if (targetCard) targetCard.classList.add('is-active');
            }
        }

        // Detect if user is moving toward the models column
        function isMovingToward(brand, mouseX, mouseY) {
            if (!modelsCol) return false;

            var modelsRect = modelsCol.getBoundingClientRect();
            var brandRect = brand.getBoundingClientRect();

            // User is already inside models column area? Keep current brand
            if (mouseX >= modelsRect.left) return true;

            // Calculate angle/direction from last mouse position to models column
            // If mouse is moving right toward models (positive X velocity) — intent detected
            var mouseMovedRight = mouseX > lastMouseX - 5;
            var elapsed = Date.now() - lastMouseTime;

            return mouseMovedRight && elapsed < 100;
        }

        brands.forEach(function (brand) {
            brand.addEventListener('mouseenter', function (e) {
                clearTimeout(switchTimer);

                var mouseX = e.clientX;
                var mouseY = e.clientY;

                // If mouse is clearly moving toward models column, delay the switch
                if (isMovingToward(brand, mouseX, mouseY)) {
                    switchTimer = setTimeout(function () {
                        switchBrand(brand);
                    }, 250);
                } else {
                    // Normal hover — quick switch
                    switchTimer = setTimeout(function () {
                        switchBrand(brand);
                    }, 80);
                }
            });

            brand.addEventListener('mouseleave', function () {
                // Don't cancel — let the timer complete if user settled on this brand
            });
        });

        // When hovering over models column, cancel pending switch
        if (modelsCol) {
            modelsCol.addEventListener('mouseenter', function () {
                clearTimeout(switchTimer);
            });
        }

    }

    /* ─── Close dropdowns on outside click ─── */
    function initOutsideClick() {
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.header-help, .mobile-help-trigger, .help-dropdown, .header-mega-trigger-wrap')) {
                closeAllDropdowns();
            }
        });

        // Close on Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeAllDropdowns();
            }
        });
    }

    /* ─── Live search ─── */
    function initLiveSearch() {
        var input = document.querySelector('.header-search-form .search-input');
        var results = document.querySelector('.live-search-results');
        if (!input || !results || typeof headerSearch === 'undefined') return;

        var timer = null;
        var lastQuery = '';
        var controller = null;

        input.addEventListener('input', function () {
            var q = input.value.trim();
            clearTimeout(timer);

            if (q.length < 2) {
                results.classList.remove('is-open');
                results.innerHTML = '';
                lastQuery = '';
                return;
            }

            if (q === lastQuery) return;

            timer = setTimeout(function () {
                lastQuery = q;
                if (controller) controller.abort();
                controller = new AbortController();

                fetch(headerSearch.ajaxUrl + '?action=header_live_search&q=' + encodeURIComponent(q), {
                    signal: controller.signal
                })
                .then(function (r) { return r.json(); })
                .then(function (items) {
                    if (!items.length) {
                        results.innerHTML = '<div class="ls-empty">Brak wyników dla „' + q + '"</div>';
                        results.classList.add('is-open');
                        return;
                    }

                    var html = '';
                    items.forEach(function (item) {
                        html += '<a href="' + item.url + '" class="ls-item">';
                        if (item.image) {
                            html += '<img src="' + item.image + '" alt="" class="ls-thumb" width="40" height="40">';
                        }
                        html += '<div class="ls-info">';
                        html += '<span class="ls-title">' + item.title + '</span>';
                        html += '<span class="ls-price">' + item.price + '</span>';
                        html += '</div></a>';
                    });

                    results.innerHTML = html;
                    results.classList.add('is-open');
                })
                .catch(function (e) {
                    if (e.name !== 'AbortError') console.error(e);
                });
            }, 250);
        });

        // Close on click outside
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.header-search')) {
                results.classList.remove('is-open');
            }
        });

        // Close on Escape
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                results.classList.remove('is-open');
            }
        });
    }

    /* ─── Mobile search overlay + live results ─── */
    function initMobileSearch() {
        var trigger = document.querySelector('.mobile-search-trigger');
        var overlay = document.querySelector('.mobile-search-overlay');
        if (!trigger || !overlay) return;

        var closeBtn = overlay.querySelector('.mobile-search-close');
        var input = overlay.querySelector('.search-input');
        var results = overlay.querySelector('.mobile-search-results');

        trigger.addEventListener('click', function () {
            overlay.classList.add('is-open');
            overlay.setAttribute('aria-hidden', 'false');
            document.body.classList.add('mobile-search-active');
            if (input) { input.value = ''; input.focus(); }
            if (results) results.innerHTML = '';
        });

        function close() {
            overlay.classList.remove('is-open');
            overlay.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('mobile-search-active');
        }

        if (closeBtn) closeBtn.addEventListener('click', close);

        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) close();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.classList.contains('is-open')) close();
        });

        // Live search in mobile overlay
        if (!input || !results || typeof headerSearch === 'undefined') return;

        var timer = null;
        var lastQ = '';
        var ctrl = null;

        input.addEventListener('input', function () {
            var q = input.value.trim();
            clearTimeout(timer);

            if (q.length < 2) {
                results.innerHTML = '';
                lastQ = '';
                return;
            }
            if (q === lastQ) return;

            timer = setTimeout(function () {
                lastQ = q;
                if (ctrl) ctrl.abort();
                ctrl = new AbortController();

                fetch(headerSearch.ajaxUrl + '?action=header_live_search&q=' + encodeURIComponent(q), {
                    signal: ctrl.signal
                })
                .then(function (r) { return r.json(); })
                .then(function (items) {
                    if (!items.length) {
                        results.innerHTML = '<div class="ls-empty">Brak wyników dla „' + q + '"</div>';
                        return;
                    }
                    var html = '';
                    items.forEach(function (item) {
                        html += '<a href="' + item.url + '" class="ls-item">';
                        if (item.image) html += '<img src="' + item.image + '" alt="" class="ls-thumb" width="40" height="40">';
                        html += '<div class="ls-info"><span class="ls-title">' + item.title + '</span>';
                        html += '<span class="ls-price">' + item.price + '</span></div></a>';
                    });
                    results.innerHTML = html;
                })
                .catch(function (e) { if (e.name !== 'AbortError') console.error(e); });
            }, 250);
        });
    }

    /* ─── Cart side panel ─── */
    function initCartSide() {
        document.addEventListener('click', function (e) {
            var cartLink = e.target.closest('.js-open-cart');
            if (!cartLink) return;
            e.preventDefault();
            var panel = document.querySelector('.site-header-cart-side');
            if (panel) panel.classList.toggle('active');
        });
    }


    /* ─── Init ─── */
    document.addEventListener('DOMContentLoaded', function () {
        initCartSide();
        initMobileSearch();

        initMegaMenu();
        setupDropdown('.help-trigger', '.help-dropdown');
        setupDropdown('.mobile-help-trigger', '.mobile-help-dropdown');

        initMobileNav();
        initNavMega();
        initOutsideClick();
        initLiveSearch();
    });
})();
