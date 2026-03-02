<?php
/**
 * Development environment config
 */

use Roots\WPConfig\Config;

Config::define('SAVEQUERIES', true);
Config::define('WP_DEBUG', true);
Config::define('WP_DEBUG_DISPLAY', true);
Config::define('WP_DEBUG_LOG', true);
Config::define('SCRIPT_DEBUG', true);
Config::define('WP_DISABLE_FATAL_ERROR_HANDLER', true);

ini_set('display_errors', '1');
