<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', $_SERVER['RDS_NAME']);

/** MySQL database username */
define('DB_USER', $_SERVER['RDS_USER']);

/** MySQL database password */
define('DB_PASSWORD', $_SERVER['RDS_PASS']);

/** MySQL hostname */
define('DB_HOST', $_SERVER['RDS_HOST']);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/** Let wordpress access server for updates **/
//define('FS_METHOD', 'direct');

/** Allow server to send Email **/
define('MG_DEBUG_SMTP', true );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'O$? -m-rw!~0F[/yoG`0?ll+;1r*l2jDlI+t*z-R_u.!%_W8*79gR`Ki%L,8;|EE');
define('SECURE_AUTH_KEY',  '0<w=8IKo+{ILr@#Y4m-[|8~.-9 -zc9@vy9=t&6*n6LK.GD(OT3-[ZZmE O}I{K.');
define('LOGGED_IN_KEY',    '=$4,7&>+ZuFvh>~^fF,_,M@G~@Ft9W @33xS`uO^N%0&~AXg-@vU^rJmp<Zax&wd');
define('NONCE_KEY',        'h5F8x0rQEXX2bbG.NCoY;Hn++z{ChO;Fp-S)RwHB3C*t?QyX]WMTGU]gX/t@|P6/');
define('AUTH_SALT',        '[<+H@8P*nk+NqUcP*Q%H.o.M<3i6=5(V=f6UYwyKVXy&yU3c0`#TD ?j|/X][.+Q');
define('SECURE_AUTH_SALT', '6@o8N@(L~ALL$,Z!_CcKOV3ooaMjj^tV|Sk$h4V(|Z9&|7|-}/Q~4tWx!N )g{yu');
define('LOGGED_IN_SALT',   'WHa:-&GO?Vz)e.mB$wY7%r/;tQ71aVr&ZCryAB?_u-!mej$|h?ylM&k$E{D{3hXt');
define('NONCE_SALT',       'T3&N?Kf+uUR7r`If0H2.D9#+3>)`abq$+u7JT=cM|nbz!Xc#FqIrj<A|&6+F/NYz');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
