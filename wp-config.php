<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'blog9279_wp40');

/** MySQL database username */
define('DB_USER', 'blog9279_wp40');

/** MySQL database password */
define('DB_PASSWORD', 'hS3@P558!C');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'tml2moufb6vr9rgsw1qeufwgcxggack5tvnnlczfwfepk6kfajv000mrwcxz5lvp');
define('SECURE_AUTH_KEY',  'odqt6k0cuacztja4q7ofeho6drxtzqc92vzvgj39qhxmbnqsckbullq34gpqaw0t');
define('LOGGED_IN_KEY',    'twtoftesw6r59evrdvj7jfeos5ifrjotcyy55vnuwbge635hxlisxxumduereajf');
define('NONCE_KEY',        'x8ofgfrllj0ktrifq0i8igh0jshugxzuh0see9lcote8eqeq9bpcvkpsjv2ykw9j');
define('AUTH_SALT',        'bznpwk0zbw3cqfxkxrzmgty20hkru8uflrnl1ugukxchvdvwqx3wttufbrhbcfgv');
define('SECURE_AUTH_SALT', 'rkeijzhlflv7viwxqg494yjjrv2qvkrcrck3wwwg1ijqwv8tblfybnfookyctbrg');
define('LOGGED_IN_SALT',   'm7bufu10oyvql3pxvhwzdeeyfcgn4jtpsywbmfmfx3pny0lbbolipqm8yi2qf2cj');
define('NONCE_SALT',       'c14hqjyywrlakdk2gjzba5gcmdcgy9vwnhf8evgdkcuix4yl5bhrn7gwyewu5sck');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
define( 'WP_AUTO_UPDATE_CORE', false );
