<?php
/**
 * Tabela porównawcza wariantów elektryki (wiązka/moduł 7-pin/13-pin).
 *
 * Wyświetlana na stronach produktów i kategorii haków holowniczych.
 * Anchor: #porownanie-wariantow
 */

defined('ABSPATH') || exit;

$data = get_variant_comparison();

if (empty($data['rows'])) {
    return;
}

$subtitles = $data['subtitles'];
$rows      = $data['rows'];

$columns = [
    'wiazka_7pin'  => 'Wiązka 7-pin',
    'wiazka_13pin' => 'Wiązka 13-pin',
    'modul_7pin'   => 'Moduł 7-pin',
    'modul_13pin'  => 'Moduł 13-pin',
];

// Wykryj popularny wariant na stronie produktu
$popular_col = '';
if (is_singular('product')) {
    $product_id = get_the_ID();
    $all_ids = [$product_id];

    // Dodaj powiązane warianty
    $rel = get_field('wielowariantowosc', $product_id);
    if (!empty($rel)) {
        foreach ((array) $rel as $item) {
            if (is_numeric($item)) {
                $all_ids[] = (int) $item;
            } elseif (is_object($item) && isset($item->ID)) {
                $all_ids[] = (int) $item->ID;
            }
        }
    }

    // Klucze kolumn pasujące do badge'ów w product_badges
    $badge_to_col = ['wiazka_7pin', 'wiazka_13pin', 'modul_7pin', 'modul_13pin'];

    foreach (array_unique($all_ids) as $vid) {
        if (get_field('product_popular', $vid)) {
            // Odczytaj typ wariantu z product_badges (pewniejsze niż pole wariant)
            $badges = get_field('product_badges', $vid);
            if (!empty($badges)) {
                foreach ((array) $badges as $badge) {
                    if (in_array($badge, $badge_to_col, true)) {
                        $popular_col = $badge;
                        break 2;
                    }
                }
            }
        }
    }
}
?>

<section class="vc-section" id="porownanie-wariantow">
    <div class="container">
        <h2 class="vc-section__title">Porównanie wariantów — który zestaw wybrać?</h2>
        <p class="vc-section__subtitle">Sprawdź różnice między typami elektryki do haków holowniczych</p>

        <div class="vc-table-wrap">
            <table class="vc-table">
                <thead>
                    <tr>
                        <th class="vc-table__corner"></th>
                        <?php foreach ($columns as $key => $label) :
                            $is_modul = str_starts_with($key, 'modul');
                            $is_popular = ($key === $popular_col);
                        ?>
                            <th class="vc-table__col-header <?php echo $is_modul ? 'vc-table__col-header--modul' : 'vc-table__col-header--wiazka'; ?><?php echo $is_popular ? ' vc-table__col--popular' : ''; ?>">
                                <?php if ($is_popular) : ?><span class="vc-popular-badge">Polecany wybór</span><?php endif; ?>
                                <span class="vc-table__col-name"><?php echo esc_html($label); ?></span>
                                <span class="vc-table__col-sub"><?php echo esc_html($subtitles[$key]); ?></span>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $i => $row) : ?>
                        <tr class="<?php echo $i % 2 === 0 ? 'vc-table__row--even' : ''; ?>">
                            <td class="vc-table__label"><?php echo esc_html($row['nazwa_cechy']); ?></td>
                            <?php foreach (array_keys($columns) as $key) :
                                $val = $row[$key] ?? '';
                                $lower = mb_strtolower(trim($val));
                                $is_popular = ($key === $popular_col);
                            ?>
                                <td class="vc-table__cell<?php echo $is_popular ? ' vc-table__col--popular' : ''; ?>">
                                    <?php if ($lower === 'tak') : ?>
                                        <span class="vc-yes">&#10003; Tak</span>
                                    <?php elseif ($lower === 'nie') : ?>
                                        <span class="vc-no">&#10007; Nie</span>
                                    <?php else : ?>
                                        <?php echo esc_html($val); ?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
