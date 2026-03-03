<?php
/**
 * Production environment config — zahakowani.pl
 */

use Roots\WPConfig\Config;

Config::define('WP_DEBUG', false);
Config::define('WP_DEBUG_DISPLAY', false);
Config::define('WP_DEBUG_LOG', false);
Config::define('SCRIPT_DEBUG', false);

ini_set('display_errors', '0');

Config::define('DISALLOW_FILE_MODS', true);
Config::define('FORCE_SSL_ADMIN', true);
Config::define('WP_POST_REVISIONS', 5);
Config::define('WP_MEMORY_LIMIT', '256M');
