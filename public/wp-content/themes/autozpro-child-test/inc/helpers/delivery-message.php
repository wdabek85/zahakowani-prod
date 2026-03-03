<?php
/**
 * Zwraca komunikat o dostawie w zależności od czasu zamówienia
 */
function get_delivery_message() {
    // Aktualny czas w timezone WordPress
    $now_ts = current_time('timestamp');
    
    // Dzień tygodnia: 1=Pn, 7=Nd
    $dow = (int) wp_date('N', $now_ts);
    
    // Godzina i minuta
    $hour = (int) wp_date('G', $now_ts);
    $min  = (int) wp_date('i', $now_ts);
    
    // Czy przed 14:00?
    $before_14 = ($hour < 14) || ($hour === 14 && $min === 0);
    
    $prefix = 'Kup do 14:00, ';
    $strong = 'dostawa następnego dnia.';
    
    // Sobota lub Niedziela
    if ($dow === 6 || $dow === 7) {
        $prefix = 'Kup dziś, ';
        $strong = 'dostawa we wtorek.';
    }
    
    // Piątek
    elseif ($dow === 5) {
        if ($before_14) {
            $prefix = 'Kup do 14:00, ';
            $strong = 'dostawa w poniedziałek.';
        } else {
            $prefix = 'Kup po 14:00, ';
            $strong = 'dostawa we wtorek.';
        }
    }
    
    // Poniedziałek–Czwartek
    else {
        if ($before_14) {
            $prefix = 'Kup do 14:00, ';
            $strong = 'dostawa następnego dnia.';
        } else {
            $prefix = 'Kup teraz, ';
            $strong = 'dostawa pojutrze.';
        }
    }
    
    return [
        'prefix' => $prefix,
        'strong' => $strong,
    ];
}