<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

// Disable Wordpress auto-update checks.
// These can cause significant delays, especially with Wasmer Edge InstaBoot.
define('WP_AUTO_UPDATE_CORE', false);
define('AUTOMATIC_UPDATER_DISABLED', true);

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - Wasmer Edge auto-injects DB_NAME, DB_USER, DB_PASSWORD, DB_HOST, DB_PORT ** //
define( 'DB_NAME', getenv('DB_NAME') ?: (getenv('MYSQL_DATABASE') ?: 'wordpress') );
define( 'DB_USER', getenv('DB_USER') ?: (getenv('MYSQL_USER') ?: 'wordpress') );
define( 'DB_PASSWORD', getenv('DB_PASSWORD') ?: (getenv('MYSQL_PASSWORD') ?: '') );

$db_host = getenv('DB_HOST') ?: (getenv('MYSQL_HOST') ?: 'localhost');
$db_port = getenv('DB_PORT') ?: getenv('MYSQL_PORT');
if ($db_port && !str_contains($db_host, ':')) {
    $db_host .= ':' . $db_port;
}
define( 'DB_HOST', $db_host );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define('DB_DIR', dirname(dirname(__FILE__)) . '/db/');

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         getenv('AUTH_KEY') ?: 'wasmer-wp-auth-key-secret-12345' );
define( 'SECURE_AUTH_KEY',  getenv('SECURE_AUTH_KEY') ?: 'wasmer-wp-secure-auth-key-secret-12345' );
define( 'LOGGED_IN_KEY',    getenv('LOGGED_IN_KEY') ?: 'wasmer-wp-logged-in-key-secret-12345' );
define( 'NONCE_KEY',        getenv('NONCE_KEY') ?: 'wasmer-wp-nonce-key-secret-12345' );
define( 'AUTH_SALT',        getenv('AUTH_SALT') ?: 'wasmer-wp-auth-salt-secret-12345' );
define( 'SECURE_AUTH_SALT', getenv('SECURE_AUTH_SALT') ?: 'wasmer-wp-secure-auth-salt-secret-12345' );
define( 'LOGGED_IN_SALT',   getenv('LOGGED_IN_SALT') ?: 'wasmer-wp-logged-in-salt-secret-12345' );
define( 'NONCE_SALT',       getenv('NONCE_SALT') ?: 'wasmer-wp-nonce-salt-secret-12345' );


$scheme = isset( $_SERVER['HTTPS'] ) && '1' === (string) $_SERVER['HTTPS'] ? "https://" : "http://";

define( 'WP_HOME',  $scheme . $_SERVER['HTTP_HOST'] );
define( 'WP_SITEURL', $scheme . $_SERVER['HTTP_HOST'] . '/' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
