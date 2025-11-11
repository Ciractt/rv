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
define( 'DB_NAME', 'beta' );

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
define( 'AUTH_KEY',         '7ABr/x&~)ySTHo_|$1;yIC#,SWEI67R7DPE,2-XaC/s?wCJo4~LH1`C@Li^t`(V$' );
define( 'SECURE_AUTH_KEY',  'WC#!Q/Tud=T1kYQ{8c6oE[C;}x02q%?0=jJncJ,L@fTszGKy#P}bdj:~=*7nVn=X' );
define( 'LOGGED_IN_KEY',    'nw,%;0<mfL_KyHmb.^ B:-b(QtPN=rwOJeaZ|G1$I&JNx8X@G;dCh>5z.PMS?4!0' );
define( 'NONCE_KEY',        'qTUEt5M_?5/>U:PB Gzz3m]2G>qPG2hSEm(H7!]hu!CM=Wh UvjRMI2!$J3}wga|' );
define( 'AUTH_SALT',        'sR8)aX[}hD>]P;,a1I~uVsQ3?9Q4pNzhKQ5!h$()dau>?$P5A8t-U^rZMI88Rn_r' );
define( 'SECURE_AUTH_SALT', 'sKE9M0R|Np3gg0Nc{0Lu/k`$]+2<~zY_q-vWb1>FVJ=3*M/vI) )Rh$<xhi9(Vb2' );
define( 'LOGGED_IN_SALT',   'hmq0|`4`;qbI_Z|dRr#,!.n YF:UQL)_]GM[|jIh?u?F}K-a:@N@TyH2z:kankl+' );
define( 'NONCE_SALT',       '3Vd03fPu~_I.?dQ.JjD^@Ar5_.mCrn^.kT.Oa#}$g%6TbF;5<JT~dg_!p^-V)+KE' );

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
define('FS_METHOD', 'direct');

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
