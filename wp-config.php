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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         '=Z@~mGZ?wuz!M$6$&||T[i{,[]Vnh5`-[(h@l~f3CVRR@bm*jI)#uYR<2B D{_V]');
define('SECURE_AUTH_KEY',  'aR:r3qUP8&hm>s=}~lP%MuFZfY VW?4FlIS_ID:XYyd0MpCzSFj/o&_1~#+T:4mR');
define('LOGGED_IN_KEY',    'Sx/]cCuw_Gu1chm;SP%2Uq/F9P[9ju^le$eA[$RTi.tw[=b+z(2pA&RUfD-QGJWV');
define('NONCE_KEY',        'CyjfSg~u_[]4zt@Ev|W[HLi3MOf|xYmQ?JFl4B9-27yS#RkI|uqbQ$=%~2ve4AYc');
define('AUTH_SALT',        'FmD4/xntm`X~2WS[9l=L 9yG7>Y;j(f@Nd~WE5GVxrpllp< b<5c:FC[> yJ1=Cn');
define('SECURE_AUTH_SALT', 'h*fw/attQ(OUvefoCC0q<MI:ia$plL,HLZ0v g)mflW%Y{^3usB4*W4P%|29MOE2');
define('LOGGED_IN_SALT',   '*otq3GrE,Y#>N]LBRGH*;UB2m:ZV)(Cg`/(7{{m1W>Vpks5ELE)Bk9SA5S.1&v*A');
define('NONCE_SALT',       '`[Z.TgLIj9OJ}4.$<J3Ai=Wd)p&<F|}1kX%UW$(1Iyws/kD4 40}C=ShXGq>uIgO');

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
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', '');

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
