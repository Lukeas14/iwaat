<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home";
$route['404_override'] = '';

$route['admin'] = "admin/index";
$route['admin/(.*)'] = "admin/$1";

$route['search'] = "search";

$route['cron/(.*)'] = "cron/$1";

$route['app/(.*)'] = "apps/app";
$route['suggest_app'] = "apps/suggest_app";
$route['add_app'] = "apps/add_app";

//Contact pages
$route['contact_us'] = "contact/contact_us";

$route['login_register'] = "user/login_register";
$route['account/profile'] = "user/account_profile";
$route['account/add_app'] = "user/account_add_app";
$route['login'] = "user/login";
$route['register'] = "user/register";
$route['logout'] = "user/logout";

/* End of file routes.php */
/* Location: ./application/config/routes.php */