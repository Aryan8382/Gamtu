<?php
define( 'WP_CACHE', true );

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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u709643491_4cFJt' );

/** Database username */
define( 'DB_USER', 'u709643491_keGrb' );

/** Database password */
define( 'DB_PASSWORD', 'A166D3IuVj' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'dwWRv&I@v:i#;si4EmiC .)Dg~WR&;L-|L&6j%),uRV7_DsYjOb$u]|*A~eVVE`;' );
define( 'SECURE_AUTH_KEY',   'wJiAyGA! W~@$,&cN}=cGN$qtcRs[7uX{$k]=Zu]3ui:.2.0@gfH/52:/U>aP8S+' );
define( 'LOGGED_IN_KEY',     '9`ZD`[KPj1@R474K?rFs IUM[ TJ:Y{M$[/uZ`Z*BqW?BnuD3)Zp4-KF.6norD*;' );
define( 'NONCE_KEY',         ']{>J%HpOBQ@C!Q@bBp9apyR$g,+IG7@fzrjl~pVG[U5V&(:79G]q}]}KB9-[M:eI' );
define( 'AUTH_SALT',         'iR}NNj^z}lF Ad&S7A4_velr?leeTVl`Sk7Mad-*(k><d+vYprarW82ph0@6U9S;' );
define( 'SECURE_AUTH_SALT',  '}mK#V(/rn%q/r`)RA/!rFNJMt3}4f@D:,H]<GX`10)]e9]|hvX{);,mdeAF&Mr5M' );
define( 'LOGGED_IN_SALT',    '9N/[T&Q yqobaF`[,R0|m.mnH.27,_Or%#1X#ePTe=?~&`#;a|!**dUAx]~q[Fm!' );
define( 'NONCE_SALT',        '/SYL#~,2q-.b5,4yf`KMp^QbxZIQ@a|Kb_ z=6.vkh-q&=%UOYOKr8oqhPz6QnCD' );
define( 'WP_CACHE_KEY_SALT', 'E9I3kRe&s[%;--s9DV9%4RI0QlTiLQ_}y}+l#P?2m(;#0fgp&>@@f.xREt}Lw]m!' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', '19a697eb06476a473ff37cce0b26d2d5' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
