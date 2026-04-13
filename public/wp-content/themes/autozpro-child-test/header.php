<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link rel="profile" href="//gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php do_action('autozpro_before_site'); ?>

<div id="page" class="hfeed site">
    <?php do_action('autozpro_before_header'); ?>

    <header id="site-header" class="site-header">

        <?php
        // ─── Przygotuj dane ───
        $account_link = '#';
        if (function_exists('autozpro_is_woocommerce_activated') && autozpro_is_woocommerce_activated()) {
            $account_link = get_permalink(get_option('woocommerce_myaccount_page_id'));
        } elseif (function_exists('wp_login_url')) {
            $account_link = wp_login_url();
        }

        $cart_count = 0;
        $cart_total = '';
        $cart_url   = '#';
        if (function_exists('WC') && WC()->cart) {
            $cart_count = WC()->cart->get_cart_contents_count();
            $cart_total = WC()->cart->get_cart_subtotal();
            $cart_url   = wc_get_cart_url();
        }

        $wishlist_count = 0;
        $wishlist_url   = '#';
        if (function_exists('woosw_init')) {
            $wl_key         = WPCleverWoosw::get_key();
            $wishlist_count = WPCleverWoosw::get_count($wl_key);
            $wishlist_url   = WPCleverWoosw::get_url($wl_key, true);
        } elseif (function_exists('yith_wcwl_count_all_products')) {
            $wishlist_count = yith_wcwl_count_all_products();
            $wishlist_url   = get_permalink(get_option('yith_wcwl_wishlist_page_id'));
        }

        $logo_id  = get_theme_mod('custom_logo');
        $logo_img = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : '';
        if (!$logo_img) {
            $logo_img = home_url('/wp-content/uploads/2022/04/Logo-black-zahakowani.png');
        }
        ?>

        <?php // ═══════ SEKCJA 1: TOPBAR ═══════ ?>
        <div class="site-header-topbar">
            <div class="topbar-inner">
                <div class="topbar-left">
                    <span class="topbar-welcome">Witamy w sklepie Zahakowani</span>
                </div>
                <div class="topbar-right">
                    <nav class="topbar-links" aria-label="Linki pomocnicze">
                        <ul>
                            <li><a href="<?php echo esc_url(home_url('/shop/')); ?>">Katalog</a></li>
                            <li><a href="<?php echo esc_url(home_url('/faq/')); ?>">FAQ</a></li>
                            <li><a href="<?php echo esc_url(home_url('/kontakt/')); ?>">Kontakt</a></li>
                        </ul>
                    </nav>
                    <span class="topbar-divider"></span>
                    <div class="topbar-account">
                        <?php if (is_user_logged_in()) : $user = wp_get_current_user(); ?>
                            <a href="<?php echo esc_url($account_link); ?>">
                                Witaj, <strong><?php echo esc_html($user->display_name); ?></strong>
                            </a>
                        <?php else : ?>
                            <a href="<?php echo esc_url($account_link); ?>">
                                <span>Zaloguj się</span> lub <span>Zarejestruj</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php // ═══════ SEKCJA 2: MAIN HEADER ═══════ ?>
        <div class="site-header-main">
            <div class="header-main-inner">

                <?php // Logo ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="header-logo" rel="home">
                    <img src="<?php echo esc_url($logo_img); ?>" alt="<?php bloginfo('name'); ?>" class="custom-logo">
                </a>

                <?php // Mega menu "Produkty" ?>
                <div class="header-mega-trigger-wrap desktop-only">
                    <button class="mega-menu-trigger" aria-expanded="false" aria-controls="mega-menu">
                        <span class="trigger-icon"><?php echo get_icon('menu', 'icon-sm'); ?></span>
                        <span class="trigger-label">Produkty</span>
                        <?php echo get_icon('chevron-down', 'icon-xs'); ?>
                    </button>
                    <?php child_render_mega_menu(); ?>
                </div>

                <?php // ─── Search bar ─── ?>
                <div class="header-search desktop-only">
                    <form role="search" method="get" class="header-search-form" action="<?php echo esc_url(home_url('/')); ?>">
                        <input type="search" class="search-input" name="s" placeholder="Szukaj produktów…" autocomplete="off" value="<?php echo get_search_query(); ?>">
                        <button type="submit" class="search-submit" aria-label="Szukaj">
                            <?php echo get_icon('search', 'icon-sm'); ?>
                        </button>
                        <input type="hidden" name="post_type" value="product">
                    </form>
                    <div class="live-search-results"></div>
                </div>

                <?php // Help / Contact ?>
                <div class="header-help desktop-only">
                    <button class="help-trigger" aria-expanded="false">
                        <?php echo get_icon('headset', 'icon-md'); ?>
                        <span class="help-label">Pomoc</span>
                        <?php echo get_icon('chevron-down', 'icon-xs'); ?>
                    </button>
                    <div class="help-dropdown">
                        <div class="help-dropdown-header">
                            <img src="<?php echo esc_url(home_url('/wp-content/uploads/2022/04/adam-kaminski-avatar.jpg')); ?>" alt="" width="48" height="48" class="help-avatar">
                            <div class="help-dropdown-info">
                                <strong>Potrzebujesz pomocy?</strong>
                                <span class="help-sub">Zadzwoń do nas</span>
                                <a href="tel:+48536731515" class="help-phone">(+48) 536 731 515</a>
                            </div>
                        </div>
                        <div class="help-dropdown-footer">
                            <a href="<?php echo esc_url(home_url('/kontakt/')); ?>" class="help-cta">
                                <?php echo get_icon('chat', 'icon-sm'); ?>
                                Napisz do nas
                            </a>
                            <span class="help-hours">Pon – Pt: 6:00 – 18:00</span>
                        </div>
                    </div>
                </div>

                <?php // ─── Icons group: account + wishlist + cart ─── ?>
                <div class="header-icons">
                    <a href="<?php echo esc_url($account_link); ?>" class="header-icon header-icon--account" aria-label="Moje konto">
                        <?php echo get_icon('user', 'icon-md'); ?>
                    </a>

                    <a href="<?php echo esc_url($wishlist_url); ?>" class="header-icon header-icon--wishlist" aria-label="Lista życzeń">
                        <?php echo get_icon('heart', 'icon-md'); ?>
                        <?php if ($wishlist_count > 0) : ?>
                            <span class="header-icon-badge"><?php echo (int) $wishlist_count; ?></span>
                        <?php endif; ?>
                    </a>

                    <a href="<?php echo esc_url($cart_url); ?>" class="header-icon header-icon--cart js-open-cart" aria-label="Koszyk">
                        <span class="cart-icon-wrap">
                            <?php echo get_icon('cart', 'icon-md'); ?>
                            <span class="header-icon-badge cart-count"><?php echo (int) $cart_count; ?></span>
                        </span>
                        <span class="cart-label desktop-only">
                            <span class="cart-label-title">Koszyk</span>
                            <span class="cart-label-total"><?php echo $cart_total ?: '0,00 zł'; ?></span>
                        </span>
                    </a>
                </div>

                <?php // Mobile hamburger — pushed right ?>
                <button class="mobile-menu-toggle mobile-only" aria-label="Menu" aria-expanded="false">
                    <?php echo get_icon('menu', 'icon-lg'); ?>
                </button>

            </div>
        </div>

        <?php // ═══════ SEKCJA 3: DESKTOP NAV MENU ═══════ ?>
        <nav class="site-header-nav desktop-only" aria-label="Menu główne">
            <div class="header-nav-inner">
                <?php
                wp_nav_menu([
                    'theme_location'  => 'primary',
                    'fallback_cb'     => '__return_empty_string',
                    'container_class' => 'primary-navigation',
                    'menu_class'      => 'nav-menu',
                ]);
                ?>
                <?php child_render_haki_mega_dropdown(); ?>
            </div>
        </nav>

        <?php // ═══════ SEKCJA 4: MOBILE BAR ═══════ ?>
        <div class="site-header-mobile-bar mobile-only">
            <div class="mobile-bar-inner">
                <button class="mobile-search-trigger" aria-label="Szukaj">
                    <?php echo get_icon('search', 'icon-md'); ?>
                </button>
                <a href="tel:+48536731515" class="header-icon" aria-label="Zadzwoń">
                    <?php echo get_icon('phone-ring', 'icon-md'); ?>
                </a>
                <a href="<?php echo esc_url($account_link); ?>" class="header-icon" aria-label="Konto">
                    <?php echo get_icon('user', 'icon-md'); ?>
                </a>
                <a href="<?php echo esc_url($wishlist_url); ?>" class="header-icon" aria-label="Wishlist">
                    <?php echo get_icon('heart', 'icon-md'); ?>
                    <?php if ($wishlist_count > 0) : ?>
                        <span class="header-icon-badge"><?php echo (int) $wishlist_count; ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?php echo esc_url($cart_url); ?>" class="header-icon js-open-cart" aria-label="Koszyk">
                    <?php echo get_icon('cart', 'icon-md'); ?>
                    <span class="header-icon-badge cart-count"><?php echo (int) $cart_count; ?></span>
                </a>
            </div>
        </div>

    </header>

    <?php // ─── Mobile search overlay ─── ?>
    <div class="mobile-search-overlay" aria-hidden="true">
        <div class="mobile-search-overlay-inner">
            <div class="mobile-search-top">
                <form role="search" method="get" class="mobile-search-overlay-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="search" class="search-input" name="s" placeholder="Szukaj produktów…" autocomplete="off" value="">
                    <button type="submit" aria-label="Szukaj"><?php echo get_icon('search', 'icon-md'); ?></button>
                    <input type="hidden" name="post_type" value="product">
                </form>
                <button class="mobile-search-close" aria-label="Zamknij">
                    <?php echo get_icon('x-mark', 'icon-lg'); ?>
                </button>
            </div>
            <div class="mobile-search-results"></div>
        </div>
    </div>

    <?php
    // ─── Mobile nav — our own drill-down (replaces parent theme) ───
    get_template_part('template-parts/mobile-nav');

    // ─── Account dropdown (parent theme AJAX login) ───
    if (function_exists('autozpro_template_account_dropdown')) {
        add_action('wp_footer', 'autozpro_template_account_dropdown', 5);
    }

    // ─── Cart side panel ───
    if (function_exists('autozpro_header_cart_side')) {
        add_action('wp_footer', 'autozpro_header_cart_side', 5);
    }
    ?>

    <?php do_action('autozpro_before_content'); ?>

    <div id="content" class="site-content" tabindex="-1">
        <div class="col-full">

<?php do_action('autozpro_content_top'); ?>
