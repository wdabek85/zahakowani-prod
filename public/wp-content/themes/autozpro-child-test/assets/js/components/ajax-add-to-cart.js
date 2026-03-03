/**
 * AJAX Add to Cart — single product page
 *
 * Intercepts the buy-box form submit and sticky-bar link click,
 * sends AJAX request, updates mini-cart, shows confirmation.
 * No page reload needed.
 */

(function () {
    const form = document.querySelector('.buy-box__actions .cart');
    const stickyBtn = document.querySelector('.sticky-buy-bar__cta');

    if (!form) return;

    const submitBtn = form.querySelector('.btn-add-to-cart');
    const productId = submitBtn ? submitBtn.value : null;

    if (!productId) return;

    function addToCart(triggerBtn) {
        if (triggerBtn.classList.contains('is-loading')) return;

        const originalText = triggerBtn.textContent;
        triggerBtn.classList.add('is-loading');
        triggerBtn.textContent = 'Dodawanie...';

        const data = new URLSearchParams();
        data.append('product_id', productId);
        data.append('quantity', 1);

        fetch('/?wc-ajax=add_to_cart', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: data,
        })
            .then(function (res) { return res.json(); })
            .then(function (json) {
                if (json.error) {
                    triggerBtn.textContent = originalText;
                    triggerBtn.classList.remove('is-loading');
                    return;
                }

                // Update WC fragments (mini-cart in header)
                if (json.fragments) {
                    Object.keys(json.fragments).forEach(function (key) {
                        var els = document.querySelectorAll(key);
                        els.forEach(function (el) {
                            el.outerHTML = json.fragments[key];
                        });
                    });
                }

                // Update cart count badge if parent theme uses one
                if (json.cart_hash) {
                    document.cookie = 'woocommerce_cart_hash=' + json.cart_hash + ';path=/';
                }

                // Success feedback
                triggerBtn.textContent = 'Dodano do koszyka!';
                triggerBtn.classList.remove('is-loading');
                triggerBtn.classList.add('is-added');

                // Also update the other button
                var otherBtn = triggerBtn === submitBtn ? stickyBtn : submitBtn;
                if (otherBtn) {
                    otherBtn.textContent = 'Dodano do koszyka!';
                    otherBtn.classList.add('is-added');
                }

                setTimeout(function () {
                    triggerBtn.textContent = originalText;
                    triggerBtn.classList.remove('is-added');
                    if (otherBtn) {
                        otherBtn.textContent = originalText;
                        otherBtn.classList.remove('is-added');
                    }
                }, 2000);

                // Trigger WC event for other plugins
                document.body.dispatchEvent(new Event('wc_fragment_refresh'));
                document.body.dispatchEvent(new CustomEvent('added_to_cart', {
                    detail: { fragments: json.fragments, cart_hash: json.cart_hash }
                }));

                // jQuery event for parent theme cart widget
                if (window.jQuery) {
                    jQuery(document.body).trigger('wc_fragment_refresh');
                    jQuery(document.body).trigger('added_to_cart', [json.fragments, json.cart_hash]);
                }
            })
            .catch(function () {
                triggerBtn.textContent = originalText;
                triggerBtn.classList.remove('is-loading');
            });
    }

    // Buy-box form submit
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        addToCart(submitBtn);
    });

    // Sticky bar link click
    if (stickyBtn) {
        stickyBtn.addEventListener('click', function (e) {
            e.preventDefault();
            addToCart(stickyBtn);
        });
    }
})();
