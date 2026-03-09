<?php
require_once get_stylesheet_directory() . '/inc/vite.php';
require_once get_stylesheet_directory() . '/inc/enqueue.php';
require_once get_stylesheet_directory() . '/inc/acf-fields.php';
require_once get_stylesheet_directory() . '/inc/helpers/brand.php';
require_once get_stylesheet_directory() . '/inc/helpers/product.php';
require_once get_stylesheet_directory() . '/inc/woo-cleanup.php';
require_once get_stylesheet_directory() . '/inc/helpers/icons.php';
require_once get_stylesheet_directory() . '/inc/helpers/delivery-message.php';
require_once get_stylesheet_directory() . '/inc/widgets/vehicle-search-widget.php';
require_once get_stylesheet_directory() . '/inc/attributes.php';

/**
 * Usuń pole "part" z kaskady wyszukiwarki pojazdów.
 * Child theme używa 3 pól (rok → marka → model), nie 4.
 * Dzięki temu get_options() zwraca URL-e filtrujące po wybraniu modelu.
 */
add_filter('autozpro_admin_fields_filter_vehicle', function ($fields) {
    return array_values(array_filter($fields, function ($f) {
        return $f['slug'] !== 'part';
    }));
});
require_once get_stylesheet_directory() . '/inc/cpt-guide.php';
require_once get_stylesheet_directory() . '/inc/acf-spec-templates.php';
require_once get_stylesheet_directory() . '/inc/cpt-spec-katalog.php';
require_once get_stylesheet_directory() . '/inc/acf-variant-comparison.php';
require_once get_stylesheet_directory() . '/inc/b2b/settings.php';
require_once get_stylesheet_directory() . '/inc/b2b/role.php';
require_once get_stylesheet_directory() . '/inc/b2b/tracking.php';
require_once get_stylesheet_directory() . '/inc/b2b/discount.php';
require_once get_stylesheet_directory() . '/inc/b2b/my-account.php';

/**
 * Breadcrumbs on blog posts and guides.
 */
add_action( 'autozpro_single_post_top', function () {
    if ( in_array( get_post_type(), [ 'post', 'poradnik' ], true ) ) {
        $post_type = get_post_type();
        $crumbs = '<nav class="post-breadcrumbs"><a href="' . esc_url( home_url( '/' ) ) . '">Strona główna</a>';

        if ( $post_type === 'poradnik' ) {
            $crumbs .= ' / <a href="' . esc_url( get_post_type_archive_link( 'poradnik' ) ) . '">Poradniki</a>';
        } else {
            $blog_page_id = get_option( 'page_for_posts' );
            $blog_url = $blog_page_id ? get_permalink( $blog_page_id ) : home_url( '/blog/' );
            $crumbs .= ' / <a href="' . esc_url( $blog_url ) . '">Blog</a>';
        }

        $crumbs .= ' / <span>' . esc_html( get_the_title() ) . '</span></nav>';
        echo $crumbs;
    }
} );

/**
 * Breadcrumbs on blog/poradnik archive pages.
 */
add_action( 'loop_start', function ( $query ) {
    if ( ! $query->is_main_query() || is_admin() ) {
        return;
    }

    $home = '<a href="' . esc_url( home_url( '/' ) ) . '">Strona główna</a>';

    if ( is_home() ) {
        echo '<nav class="post-breadcrumbs">' . $home . ' / <span>Blog</span></nav>';
    } elseif ( is_post_type_archive( 'poradnik' ) ) {
        echo '<nav class="post-breadcrumbs">' . $home . ' / <span>Poradniki</span></nav>';
    } elseif ( is_category() ) {
        $cat = single_cat_title( '', false );
        $blog_page_id = get_option( 'page_for_posts' );
        $blog_url = $blog_page_id ? get_permalink( $blog_page_id ) : home_url( '/blog/' );
        echo '<nav class="post-breadcrumbs">' . $home . ' / <a href="' . esc_url( $blog_url ) . '">Blog</a> / <span>' . esc_html( $cat ) . '</span></nav>';
    } elseif ( is_tag() ) {
        $tag = single_tag_title( '', false );
        $blog_page_id = get_option( 'page_for_posts' );
        $blog_url = $blog_page_id ? get_permalink( $blog_page_id ) : home_url( '/blog/' );
        echo '<nav class="post-breadcrumbs">' . $home . ' / <a href="' . esc_url( $blog_url ) . '">Blog</a> / <span>' . esc_html( $tag ) . '</span></nav>';
    }
} );

/**
 * Author box under blog posts.
 */
add_action( 'autozpro_single_post_bottom', function () {
    if ( in_array( get_post_type(), [ 'post', 'poradnik' ], true ) ) {
        get_template_part( 'template-parts/post/author-box' );
    }
}, 8 );

/**
 * Google Analytics 4 + Google Ads + Tag Manager — only on production.
 */
add_action( 'wp_head', function () {
    if ( defined( 'WP_ENV' ) && WP_ENV !== 'production' ) {
        return;
    }
    ?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5J86RSGD');</script>
    <!-- End Google Tag Manager -->
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-479VCXMG0Y"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-479VCXMG0Y');
      gtag('config', 'AW-17876158382');
    </script>
    <?php
}, 1 );

/**
 * Google Tag Manager (noscript) — only on production.
 */
add_action( 'wp_body_open', function () {
    if ( defined( 'WP_ENV' ) && WP_ENV !== 'production' ) {
        return;
    }
    ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5J86RSGD"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php
}, 1 );

/**
 * Tłumaczenie stringów parent theme (autozpro) — header account dropdown.
 */
add_filter( 'gettext', function ( string $translation, string $text, string $domain ): string {
    if ( $domain !== 'autozpro' ) {
        return $translation;
    }

    static $map = [
        'Sign in'             => 'Zaloguj się',
        'Create an Account'   => 'Utwórz konto',
        'Register'            => 'Zarejestruj się',
        'Username or email'   => 'E-mail lub nazwa użytkownika',
        'Username'            => 'Nazwa użytkownika',
        'Password'            => 'Hasło',
        'Login'               => 'Zaloguj się',
        'Log in'              => 'Zaloguj się',
        'Lost your password?' => 'Nie pamiętasz hasła?',
        'Dashboard'           => 'Kokpit',
        'Orders'              => 'Zamówienia',
        'Downloads'           => 'Pobrania',
        'Edit Address'        => 'Edytuj adres',
        'Account Details'     => 'Szczegóły konta',
        'Log Out'             => 'Wyloguj się',
        'Log out'             => 'Wyloguj się',
        'My Account'          => 'Moje konto',
    ];

    return $map[ $text ] ?? $translation;
}, 10, 3 );

