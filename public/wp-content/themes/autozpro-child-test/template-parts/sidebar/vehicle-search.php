<?php
/**
 * Vehicle Search — sidebar template (Figma: "Wyszukiwarka Po modelu" / NavBar)
 *
 * Server-renders Year options so the first dropdown is immediately usable.
 * Subsequent cascades (Make > Model > Part) handled via AJAX in vehicle-search.js.
 */

$autoparts    = Autozpro_Woocommerce_AutoParts::get_instance();
$year_options = $autoparts->get_options( [] );

$ajax_url = admin_url( 'admin-ajax.php' );
$nonce    = wp_create_nonce( 'autozpro_sputnik_vehicle_select_load_data' );
?>
<div class="vehicle-search"
     data-ajax-url="<?php echo esc_url( $ajax_url ); ?>"
     data-nonce="<?php echo esc_attr( $nonce ); ?>">

    <p class="vehicle-search__title">Wybierz model pojazdu, aby wyszukać część</p>

    <div class="vehicle-search__fields">

        <div class="vehicle-search__field vehicle-search__field--active" data-slug="produced" data-step="1">
            <span class="vehicle-search__step">1</span>
            <select name="produced">
                <option value="">Wybierz rok</option>
                <?php foreach ( $year_options as $opt ) : ?>
                    <option value="<?php echo esc_attr( $opt['value'] ); ?>">
                        <?php echo esc_html( $opt['title'] ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="vehicle-search__field vehicle-search__field--disabled" data-slug="make" data-step="2">
            <span class="vehicle-search__step">2</span>
            <select name="make" disabled>
                <option value="">Wybierz markę</option>
            </select>
        </div>

        <div class="vehicle-search__field vehicle-search__field--disabled" data-slug="model" data-step="3">
            <span class="vehicle-search__step">3</span>
            <select name="model" disabled>
                <option value="">Wybierz model</option>
            </select>
        </div>

    </div>

    <button class="vehicle-search__button" type="button" disabled>SZUKAJ</button>
</div>
