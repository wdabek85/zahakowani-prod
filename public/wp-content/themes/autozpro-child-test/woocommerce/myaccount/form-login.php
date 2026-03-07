<?php
/**
 * Login / Registration Form — child theme override
 *
 * Based on parent autozpro template (WC 9.2.0).
 * Changes: Polish labels, first/last name fields in registration.
 *
 * @package autozpro-child-test
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

<div class="u-columns col2-set" id="customer_login">

	<div class="u-column1 login-form-col col-1">

<?php endif; ?>

		<form class="woocommerce-form woocommerce-form-login login" method="post">

			<?php do_action( 'woocommerce_login_form_start' ); ?>
			<div class="woocommerce-form-login-wrap">
				<h2 class="login-form-title">Logowanie</h2>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="username">Adres e-mail&nbsp;<span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" placeholder="Wpisz swój adres e-mail..." /><?php // @codingStandardsIgnoreLine ?>
				</p>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="password">Has&lstrok;o&nbsp;<span class="required">*</span></label>
					<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" placeholder="Wpisz has&lstrok;o..." />
				</p>

				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
					<span>Zapami&eogon;taj mnie</span>
				</label>

				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>

				<p class="woocommerce-LostPassword lost_password">
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>">Nie pami&eogon;tasz has&lstrok;a?</a>
				</p>

				<?php do_action( 'woocommerce_login_form' ); ?>

				<button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="Zaloguj si&eogon;">Zaloguj si&eogon;</button>

				<?php if ( shortcode_exists( 'nextend_social_login' ) ) : ?>
					<div class="login-divider"><span>lub</span></div>
					<div class="social-login-buttons">
						<?php echo do_shortcode( '[nextend_social_login]' ); ?>
					</div>
				<?php endif; ?>
			</div>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

	</div>

	<div class="u-column2 col-2">

		<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?>>

			<?php do_action( 'woocommerce_register_form_start' ); ?>
			<h2 class="register-from-title">Zarejestruj si&eogon;</h2>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_billing_first_name">Imi&eogon;&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_first_name" id="reg_billing_first_name" autocomplete="given-name" value="<?php echo ( ! empty( $_POST['billing_first_name'] ) ) ? esc_attr( wp_unslash( $_POST['billing_first_name'] ) ) : ''; ?>" placeholder="Wpisz swoje imi&eogon;..." /><?php // @codingStandardsIgnoreLine ?>
			</p>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_billing_last_name">Nazwisko&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_last_name" id="reg_billing_last_name" autocomplete="family-name" value="<?php echo ( ! empty( $_POST['billing_last_name'] ) ) ? esc_attr( wp_unslash( $_POST['billing_last_name'] ) ) : ''; ?>" placeholder="Wpisz swoje nazwisko..." /><?php // @codingStandardsIgnoreLine ?>
			</p>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_username">Nazwa u&zdot;ytkownika&nbsp;<span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" placeholder="Wpisz nazw&eogon; u&zdot;ytkownika..." /><?php // @codingStandardsIgnoreLine ?>
				</p>

			<?php endif; ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_email">Adres e-mail&nbsp;<span class="required">*</span></label>
				<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" placeholder="Wpisz swój adres e-mail..." /><?php // @codingStandardsIgnoreLine ?>
			</p>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_password">Has&lstrok;o&nbsp;<span class="required">*</span></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" placeholder="Wpisz has&lstrok;o..." />
				</p>

			<?php else : ?>

				<p>Link do ustawienia has&lstrok;a zostanie wys&lstrok;any na Twój adres e-mail.</p>

			<?php endif; ?>

			<h3 class="register-section-title">Adres rozliczeniowy</h3>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_billing_phone">Telefon&nbsp;<span class="required">*</span></label>
				<input type="tel" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_phone" id="reg_billing_phone" autocomplete="tel" value="<?php echo ( ! empty( $_POST['billing_phone'] ) ) ? esc_attr( wp_unslash( $_POST['billing_phone'] ) ) : ''; ?>" placeholder="Wpisz numer telefonu..." /><?php // @codingStandardsIgnoreLine ?>
			</p>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_billing_address_1">Ulica i nr domu&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_address_1" id="reg_billing_address_1" autocomplete="address-line1" value="<?php echo ( ! empty( $_POST['billing_address_1'] ) ) ? esc_attr( wp_unslash( $_POST['billing_address_1'] ) ) : ''; ?>" placeholder="Wpisz ulic&eogon; i numer..." /><?php // @codingStandardsIgnoreLine ?>
			</p>

			<div class="register-row-half">
				<p class="woocommerce-form-row form-row form-row-first">
					<label for="reg_billing_postcode">Kod pocztowy&nbsp;<span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_postcode" id="reg_billing_postcode" autocomplete="postal-code" value="<?php echo ( ! empty( $_POST['billing_postcode'] ) ) ? esc_attr( wp_unslash( $_POST['billing_postcode'] ) ) : ''; ?>" placeholder="00-000" /><?php // @codingStandardsIgnoreLine ?>
				</p>
				<p class="woocommerce-form-row form-row form-row-last">
					<label for="reg_billing_city">Miasto&nbsp;<span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_city" id="reg_billing_city" autocomplete="address-level2" value="<?php echo ( ! empty( $_POST['billing_city'] ) ) ? esc_attr( wp_unslash( $_POST['billing_city'] ) ) : ''; ?>" placeholder="Wpisz miasto..." /><?php // @codingStandardsIgnoreLine ?>
				</p>
			</div>

			<?php do_action( 'woocommerce_register_form' ); ?>

			<?php
			$terms_page_id   = wc_terms_and_conditions_page_id();
			$privacy_page_id = wc_privacy_policy_page_id();
			$terms_url       = $terms_page_id ? get_permalink( $terms_page_id ) : '#';
			$privacy_url     = $privacy_page_id ? get_permalink( $privacy_page_id ) : '#';
			?>

			<div class="register-legal-checkboxes">
				<p class="woocommerce-form-row form-row">
					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox">
						<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox" name="terms" id="reg_terms" value="1" <?php checked( ! empty( $_POST['terms'] ) ); ?> />
						<span>Akceptuj&eogon; <a href="<?php echo esc_url( $terms_url ); ?>" target="_blank" rel="noopener">regulamin sklepu</a>&nbsp;<span class="required">*</span></span>
					</label>
				</p>

				<p class="woocommerce-form-row form-row">
					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox">
						<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox" name="privacy_policy" id="reg_privacy_policy" value="1" <?php checked( ! empty( $_POST['privacy_policy'] ) ); ?> />
						<span>Zapozna&lstrok;em/am si&eogon; z <a href="<?php echo esc_url( $privacy_url ); ?>" target="_blank" rel="noopener">polityk&aogon; prywatno&sacute;ci</a>&nbsp;<span class="required">*</span></span>
					</label>
				</p>
			</div>

			<p class="woocommerce-FormRow form-row">
				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="Zarejestruj si&eogon;">Zarejestruj si&eogon;</button>
			</p>

			<?php if ( shortcode_exists( 'nextend_social_login' ) ) : ?>
				<div class="login-divider"><span>lub zarejestruj się przez</span></div>
				<div class="social-login-buttons">
					<?php echo do_shortcode( '[nextend_social_login]' ); ?>
				</div>
			<?php endif; ?>

			<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>

	</div>

</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
