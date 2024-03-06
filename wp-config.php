<?php
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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'copa' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         'upU+js(0Nxw3? U^pfyJYXY|vS.XIU.:8SmcUBzYFLlrj0dVhTlfw79(:awFfxf$' );
define( 'SECURE_AUTH_KEY',  '<+Y<)4=~<q>$2N>a+Zq;D&FQ^Q{, Zr!ikU.*uSl]8k%wq}Ju#!5:5Jyl8Mkq[3U' );
define( 'LOGGED_IN_KEY',    'Gb5&Vw|8S~dA:*e)0!mHniK,~d2#{g.VAzZpuS}Xz5/D<Bs>=HaE%Y4YxEu5+$FD' );
define( 'NONCE_KEY',        'cV#;u{N~jHLRZ*V}zfF$LF]m(>]!S82[LbGA9-5J%.&/Zr^hu8z4c_UDDC^-kfIz' );
define( 'AUTH_SALT',        ' ePYh=H]A24(,Gpk/K7um=`RHrZxG>S8g[<i9rfvNR>4fjuy6!q&(* l@R{f89o0' );
define( 'SECURE_AUTH_SALT', 'c*F/huirUSGaU]!t&XVH4t:7GC*s ;6sBK.QDFiA;7*-(!m]xU#z@s(?g~cB2n+m' );
define( 'LOGGED_IN_SALT',   'j?Ls`!UFqA&c@!L@Vfn*57+<y}rgGg^3Q`:LV~fTAX|/[YwDmiOC3=nt`1a;y59]' );
define( 'NONCE_SALT',       '?s<FI48/F]fyX ?-:Mf913oPAb]510QQmg`n-aH4f#Y(!)rG9y;oHnXjsmp{[ jo' );

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
