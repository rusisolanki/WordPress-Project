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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'FS_METHOD', 'direct' );


define( 'DB_NAME', 'spectacles' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         '$(I_ G)lp<i47oVy40?Dulvrm0uQ6}he=:#6h6$@&9H[Kv/Ws*s4v3]ZFSV{?9;H' );
define( 'SECURE_AUTH_KEY',  'HL$ZTQ20A/wHy+G(wX+>bFERgA>xj|4 FPIzRHtEkKi9{{y+I7udB9P3>>mP%w`_' );
define( 'LOGGED_IN_KEY',    '&dj4TtIU%uDEj74e4O{TR<1(%WX;wAG|bBgnJ5Z|6?eS`ccaiM9WR(UfSW7Ir$~ ' );
define( 'NONCE_KEY',        'i*&yxWLG-TOSGU:knJd,v%zvFW8[mD-_XjM.9wv-np2^umq:Wb^[QB~m2&qRt&c#' );
define( 'AUTH_SALT',        '3fvQ+,e)D}&??ett1aNV}fcL13SZl8~m9sMMPm[kc/dRXzO+L!R6&Ht63)e(yJ#Z' );
define( 'SECURE_AUTH_SALT', '%2.A+yP(7zcfR;*kSxB()o44bZj,vq?zXqJ[RSW*u:Q=rAAZgg)v|aj) -! v=Je' );
define( 'LOGGED_IN_SALT',   ',@n3kj Dlde:Robt&ZJhv`+d$ZiCtoYv#*x=wo>qiUUGk#fCB,7lohuY0y1)^/>T' );
define( 'NONCE_SALT',       'O)y-+Y246js1wy/W LnaaiUD2*}J,qKd>h8&iNHa5!P;r($LWa>JcaC-7.qw;l@G' );

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
