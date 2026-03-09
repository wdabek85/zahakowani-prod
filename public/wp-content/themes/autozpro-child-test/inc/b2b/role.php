<?php
/**
 * B2B: Rola b2b_mechanic + pola profilu w admin
 *
 * Rejestruje rolę "Mechanik B2B" opartą na customer.
 * Dodaje pola B2B w profilu użytkownika (tylko admin).
 * Kolumna "B2B" w liście Users.
 */

defined('ABSPATH') || exit;

/**
 * Rejestracja roli b2b_mechanic (na bazie customer).
 */
add_action('after_setup_theme', function () {
    if (get_option('azp_b2b_role_version') === '1.0') {
        return;
    }

    $customer = get_role('customer');
    if (! $customer) {
        return;
    }

    remove_role('b2b_mechanic');
    add_role('b2b_mechanic', 'Mechanik B2B', $customer->capabilities);

    update_option('azp_b2b_role_version', '1.0');
});

/**
 * Pola B2B w profilu użytkownika (edit_user / show_user).
 */
add_action('edit_user_profile', 'azp_b2b_user_fields');
add_action('show_user_profile', 'azp_b2b_user_fields');

function azp_b2b_user_fields(WP_User $user): void {
    if (! current_user_can('manage_woocommerce')) {
        return;
    }

    $is_b2b   = in_array('b2b_mechanic', $user->roles, true);
    $company  = get_user_meta($user->ID, '_b2b_company', true);
    $nip      = get_user_meta($user->ID, '_b2b_nip', true);
    ?>
    <h2>Dane B2B</h2>
    <table class="form-table">
        <tr>
            <th><label for="b2b_active">Konto B2B</label></th>
            <td>
                <label>
                    <input type="checkbox" name="b2b_active" id="b2b_active" value="1" <?php checked($is_b2b); ?>>
                    Aktywne konto mechanika B2B
                </label>
            </td>
        </tr>
        <tr>
            <th><label for="b2b_company">Nazwa firmy</label></th>
            <td><input type="text" name="b2b_company" id="b2b_company" value="<?= esc_attr($company) ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="b2b_nip">NIP</label></th>
            <td><input type="text" name="b2b_nip" id="b2b_nip" value="<?= esc_attr($nip) ?>" class="regular-text"></td>
        </tr>
    </table>
    <?php
}

/**
 * Zapis pól B2B — zmiana roli customer ↔ b2b_mechanic.
 */
add_action('edit_user_profile_update', 'azp_b2b_save_user_fields');
add_action('personal_options_update', 'azp_b2b_save_user_fields');

function azp_b2b_save_user_fields(int $user_id): void {
    if (! current_user_can('manage_woocommerce')) {
        return;
    }

    $user     = get_userdata($user_id);
    $activate = ! empty($_POST['b2b_active']);
    $is_b2b   = in_array('b2b_mechanic', $user->roles, true);

    if ($activate && ! $is_b2b) {
        $user->set_role('b2b_mechanic');
    } elseif (! $activate && $is_b2b) {
        $user->set_role('customer');
    }

    update_user_meta($user_id, '_b2b_company', sanitize_text_field($_POST['b2b_company'] ?? ''));
    update_user_meta($user_id, '_b2b_nip', sanitize_text_field($_POST['b2b_nip'] ?? ''));
}

/**
 * Kolumna "B2B" w liście Users.
 */
add_filter('manage_users_columns', function (array $columns): array {
    $columns['b2b_status'] = 'B2B';
    return $columns;
});

add_filter('manage_users_custom_column', function (string $output, string $column, int $user_id): string {
    if ($column !== 'b2b_status') {
        return $output;
    }

    $user = get_userdata($user_id);
    if ($user && in_array('b2b_mechanic', $user->roles, true)) {
        return '<span style="color:#16a34a;font-weight:600;">&#10003; B2B</span>';
    }
    return '&mdash;';
}, 10, 3);
