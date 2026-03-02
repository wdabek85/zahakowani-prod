# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Sklep internetowy WordPress/WooCommerce z hakami holowniczymi i akcesoriami samochodowymi, uruchomiony lokalnie przez LocalWP. Motyw nadrzędny to **Autozpro** (v1.0.17, ThemeLexus) z integracją WooCommerce + Elementor.

**Architektura: Bedrock (Roots)** — WP core w `public/wp/`, content w `public/wp-content/`, config w `config/`.

- **App root (git root)**: `app/` (E:/LocalSites/autohakiautozpro/app/)
- **Web root**: `public/`
- **WP core**: `public/wp/` (managed by Composer — nie edytować)
- **Content dir**: `public/wp-content/` (classic path, no DB migration needed)
- **Active child theme**: `public/wp-content/themes/autozpro-child-test/` — wszystkie customizacje tutaj
- **Parent theme**: `public/wp-content/themes/autozpro/` (symlink from `premium/themes/autozpro/`)
- **Premium packages**: `premium/` (plugins/themes not on wpackagist)
- **Config**: `config/application.php` + `config/environments/development.php`
- **Environment**: `.env` (DB credentials, URLs, salts)
- **Composer**: `composer.json` + `composer.lock`
- **Secondary install**: `zahakowani/` — oddzielna instalacja WP (testowa)

## Bedrock Commands

```bash
# PHP binary (LocalWP)
PHP="C:/Users/wdabe/AppData/Roaming/Local/lightning-services/php-8.2.27+1/bin/win64/php.exe"

# Composer (from app/ directory)
cd E:/LocalSites/autohakiautozpro/app
"$PHP" -c php-cli.ini composer.phar install
"$PHP" -c php-cli.ini composer.phar require wpackagist-plugin/PLUGIN_NAME

# MySQL (port 10064)
MYSQL="C:/Users/wdabe/AppData/Roaming/Local/lightning-services/mysql-8.0.16+8/bin/win64/bin/mysql.exe"
"$MYSQL" --host=127.0.0.1 --port=10064 --user=root --password=root local
```

## Local Environment

- **PHP**: 8.2.27 (CLI) / 8.1.23 (FPM via LocalWP), **MySQL**: 8.0.16 (port 10064), **Nginx**: 1.16.0
- **Database**: `local` / `root` / `root`, table prefix `wp_`
- **Domain**: `autohakiautozpro.local`
- **WP Admin**: `http://autohakiautozpro.local/wp/wp-admin/`
- **Language**: Polish (PL)

Brak systemu budowania (no bundler/Webpack/Vite). CSS ładowany przez `@import` w `main.css`, JS enqueue'owany bezpośrednio.

## Child Theme Architecture (autozpro-child-test)

Modularny motyw potomny z separacją: helpery, template parts, WooCommerce overrides, assets.

### Bootstrap

`functions.php` wymaga 8 modułów:
```
inc/enqueue.php           — rejestracja CSS/JS
inc/acf-fields.php        — pusty (ACF zarządzane z admin UI)
inc/woo-cleanup.php       — usuwa domyślną galerię WC, flexslider, wymusza 1 kolumnę
inc/helpers/brand.php     — get_product_brand()
inc/helpers/product.php   — get_product_variants()
inc/helpers/icons.php     — get_icon($name, $class) — inline SVG
inc/helpers/delivery-message.php — get_delivery_message()
```

### Template Parts (`template-parts/product/`)

Strona produktu (`woocommerce/single-product.php`) ładuje:
- `product-breadcrumbs.php` — okruszki
- `product-brand.php` — nazwa marki
- `product-badges.php` — etykiety (bestseller, nowy, promocja)
- `product-gallery.php` — custom galeria (zastępuje WC default)
- `product-specs.php` — tabela parametrów z ACF
- `product-variants.php` — chipy wariantów z ACF
- `sidebar/product-sidebar.php` — orkiestrator sidebara:
  - `sidebar/promo-banner.php` — baner promocyjny
  - `sidebar/buy-box.php` — cena, dostawa, add-to-cart
  - `sidebar/trust-icons.php` — ikony zaufania
- Taby: `tabs/opis.php`, `tabs/specyfikacja.php`, `tabs/faq.php`, `tabs/certyfikaty.php`, `tabs/dostawa.php`

### WooCommerce Overrides (`woocommerce/`)

- `single-product.php` — master layout strony produktu
- `archive-product.php` — strona kategorii/sklepu
- `content-product.php` — router: archiwum→horizontal card, reszta→default WC loop
- `content-product-archive.php` — pozioma karta produktu z parametrami technicznymi

### Assets

- `assets/css/main.css` — 23 @importów (base, components, tabs, sidebar, archive)
- `assets/js/components/product-gallery.js` — obsługa kliknięć thumbnailów
- `assets/js/components/product-description.js` — rozwijanie/zwijanie opisu
- `assets/icons/` — 14 ikon SVG (heroicons)
- `assets/images/` — certyfikaty, loga kurierów

## ACF Fields

| Pole | Typ | Kontekst |
|------|-----|----------|
| `product_badges` | Checkbox/array | Etykiety na karcie/stronie produktu |
| `wariant` | Select | Aktualna etykieta wariantu |
| `wielowariantowosc` | Relationship | Powiązane warianty produktów |
| `uciag` | Text | Uciąg (parametr techniczny) |
| `nacisk_na_kule_haka` | Text | Nacisk na kulę |
| `montaz_bez_ciecia_zderzaka` | Text | Info o montażu |
| `homologacja_haka` | Text | Typ homologacji |
| `gwarancja` | Text | Okres gwarancji |
| `kula_haka` | Text | Typ kuli |
| `pasuje_do_aut` | Text | Kompatybilne auta |
| `certyfikat_pdf` | URL/File | Plik certyfikatu |
| `autoryzowany_dystrybutor` | True/False | Na taksonomii product_brand |
| `specyfikacja_rozszerzona` | Nested repeater | Rozszerzone specyfikacje |
| `faq_field` | Repeater | Pytania/odpowiedzi FAQ |
| `certyfikaty` | Repeater | Karty certyfikatów |

## Parent Theme (autozpro) — reference only

Nie edytować bezpośrednio. Pliki w `premium/themes/autozpro/` (symlinked to `public/wp-content/themes/autozpro/`).
- `inc/class-main.php` — singleton, theme support, menus, TGMPA
- `inc/template-hooks.php` — action/filter hooks renderowania
- `inc/template-functions.php` — funkcje wywoływane przez hooki
- `inc/elementor/` — 50+ custom widgetów Elementor
- `inc/woocommerce/` — klasy WooCommerce (brand, autoparts, settings)

## Installed Plugins

Managed by Composer (`composer.json`):
WooCommerce, Elementor, ACF Pro, RevSlider, Contact Form 7, Mailchimp for WP, SVG Support, Header Footer Elementor, Loco Translate, InPost Pay, Omnibus, WPC Smart Compare/Wishlist/Quick View, WPC Bought Together, WPC Product FAQs, WP Migrate DB, WP Reviews for Google.

## Customization Rules

1. **Zawsze edytuj `autozpro-child-test/`** — nigdy motyw nadrzędny ani legacy child themes
2. WooCommerce template overrides → `autozpro-child-test/woocommerce/`
3. Nowe helpery → `autozpro-child-test/inc/helpers/` + require w `functions.php`
4. Nowe template parts → `autozpro-child-test/template-parts/`
5. CSS komponentów → osobny plik w `assets/css/components/` + @import w `main.css`
6. Ikony SVG → `assets/icons/` + użycie przez `get_icon()`
7. **Nowe pluginy** → `composer require wpackagist-plugin/PLUGIN_NAME` (nie ręcznie)
8. **Premium plugins/themes** → dodaj do `premium/`, utwórz stub `composer.json`, dodaj path repo
