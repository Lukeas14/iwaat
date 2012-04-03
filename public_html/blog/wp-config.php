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

include_once(dirname(__FILE__) . '/../../application/config/env_constants.php');

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
/** MySQL database password */

define('DB_PASSWORD', DB_PASS);

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
define('AUTH_KEY',         'oKQd-@rL0E!j K!cqybB/^qKn1S[OdQ|Ts|bwc<je#+.H9$4X/?SXWei,]^P+oFy');
define('SECURE_AUTH_KEY',  '62VPq8(7$qq8[8n?{5+F]|Z1L{&4 _d&` =Ld,[rF Lx0:Ok?f><?MF`}WjL+o`}');
define('LOGGED_IN_KEY',    '?d8pYhQKVjs[hf]?mn`Fu~U ?c/:mM@@i{*O0,|$~yqZ_{<PhDpYT&j+9KMe;|bq');
define('NONCE_KEY',        '{Et+U,+{aruTN~@#|`;o{`Sc$d}Uzp3[;s<:V4s->eej+r#nTX+s9r<c!NI:]B$K');
define('AUTH_SALT',        '8X&Ofa<h`dJX@48kEI^S@%^hLE~CR>6Mz JIUm mYONF40r|Xv*&F*J&eK2LN4/0');
define('SECURE_AUTH_SALT', '#Wwx{Q},o19*O)?bvE+WCV7OSNR-.-}>_RU_bi0x<M}poh-*zP/7T QYV$!TFBB[');
define('LOGGED_IN_SALT',   'cge;HY-R)ug/(L+|zF$Ek]C#drx0!6bDZvlxNmD26..*{Edd.4uTvv-{QD+w<g-;');
define('NONCE_SALT',       'n*4&jCOT(IZdb!O{Q|0,~@rP+M{I|O ^q~KoSkH^Bjl!?_6Ocu JFH^x$#h~#+o~');

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
