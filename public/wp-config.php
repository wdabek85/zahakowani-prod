<?php
define( 'WP_CACHE', true );

/**
 * Bedrock wp-config shim.
 *
 * Loads Composer autoloader and Bedrock application config,
 * then hands off to roots/wp-config which defines all WP constants.
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/config/application.php';

require_once ABSPATH . 'wp-settings.php';
