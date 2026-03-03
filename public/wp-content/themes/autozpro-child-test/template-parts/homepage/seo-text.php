<?php
/**
 * Homepage — SEO Text section
 *
 * Two WYSIWYG blocks. Reads ACF fields "hp_seo_blok_1" and "hp_seo_blok_2"
 * from the front page. If ACF fields are empty, renders hardcoded fallback.
 *
 * To use ACF: create a field group scoped to "Front Page", with two
 * WYSIWYG fields named hp_seo_blok_1 and hp_seo_blok_2.
 *
 * @package autozpro-child-test
 */

$front_id  = get_option( 'page_on_front' );
$seo_1     = function_exists( 'get_field' ) ? get_field( 'hp_seo_blok_1', $front_id ) : '';
$seo_2     = function_exists( 'get_field' ) ? get_field( 'hp_seo_blok_2', $front_id ) : '';
$has_acf   = ( $seo_1 || $seo_2 );
?>
<section class="hp-seo">
    <?php if ( $has_acf ) : ?>

        <?php if ( $seo_1 ) : ?>
            <div class="hp-seo__block"><?php echo wp_kses_post( $seo_1 ); ?></div>
        <?php endif; ?>
        <?php if ( $seo_2 ) : ?>
            <div class="hp-seo__block"><?php echo wp_kses_post( $seo_2 ); ?></div>
        <?php endif; ?>

    <?php else : ?>

        <div class="hp-seo__block">
            <h3>Bagażniki rowerowe na hak</h3>
            <p>Znane również jako platformy rowerowe montowane na kuli haka, to najwygodniejszy i najbezpieczniejszy sposób przewożenia rowerów samochodem. Dzięki nim załadunek i rozładunek jest szybki, stabilny i nie wymaga dużego wysiłku, a same rowery są chronione przed uszkodzeniem w trakcie transportu.</p>
            <p>Nasza oferta obejmuje sprawdzone bagażniki rowerowe na hak holowniczy od renomowanych producentów, takich jak Spinder, Pro-User, Hapro, Peruzzo, Aguri i wielu innych. To marki cenione w całej Europie za innowacyjne rozwiązania, trwałość i komfort użytkowania.</p>
        </div>

        <div class="hp-seo__block">
            <h3>Auto części i akcesoria samochodowe online — wszystko do Twojego samochodu na AUTODOC</h3>
            <p>Dzień dobry, witamy w AUTODOC! Już niebawem dołączysz do społeczności ponad 21 milionów zadowolonych klientów, od pasjonatów samochodów po profesjonalnych mechaników i ludzi, którzy po prostu kochają wszystko, co związane z samochodami.</p>
            <p>Kiedy zaczynaliśmy 15 lat temu, nasz cel był prosty: uczynić serwisowanie samochodu tak łatwym, jak to tylko możliwe. Zaczęliśmy od sklepu internetowego, oferującego największy wybór części w cenach na każdą kieszeń.</p>
        </div>

    <?php endif; ?>
</section>
