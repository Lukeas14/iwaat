<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('ADMIN_EMAIL_ADDRESS', 'justin@iwaat.com');
define('ADMIN_EMAIL_NAME', 'I Want An App That...');

define('CACHE_TIME', 60 * 60);

define('BASE_DIR', '/var/www/iwaat');
define('APP_IMAGE_DIR', BASE_DIR . '/public_html/images/apps');
define('APP_IMAGE_TMP_DIR', './tmp/app_images');
define('SCREENSHOT_API_URL', 'http://images.shrinktheweb.com/xino.php?stwembed=1&stwu=aa00b&stwaccesskeyid=ca6b061948c9f8b&stwxmax=%s&stwymax=%s&stwurl=%s');
define('SCREENSHOT_LARGE_WIDTH', 1024);
define('SCREENSHOT_LARGE_HEIGHT', 500);
define('SCREENSHOT_SMALL_WIDTH', 400);
define('SCREENSHOT_SMALL_HEIGHT', 300);

//Sendgrid
define('SENDGRID_HOST', 'smtp.sendgrid.net');
define('SENDGRID_PORT', 587);
define('SENDGRID_USER', 'Lukeas14');
define('SENDGRID_PASS', 'Scott2');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */