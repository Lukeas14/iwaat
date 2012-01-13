<?php

$http_host = $_SERVER['HTTP_HOST'];
switch($http_host){
	case 'dev.iwaat.com':
		define('ENVIRONMENT', 'development');
		error_reporting(E_ALL);
		ini_set('display_errors',1);
		
		define('DB_HOST', 'localhost');
		define('DB_USER', 'jiwaatlucas');
		define('DB_PASS', 'j23waati$lucas');
		define('DB_NAME', 'iwaat');
		break;
	
	case 'test.iwaat.com':
		define('ENVIRONMENT', 'testing');
		error_reporting(E_ALL);
		ini_set('display_errors',1);
		
		define('DB_HOST', 'localhost');
		define('DB_USER', 'jiwaatlucas');
		define('DB_PASS', 'j3oi2@$I@OJ$wmew');
		define('DB_NAME', 'iwaat');
		break;
	
	default:
		define('ENVIRONMENT', 'production');
		error_reporting(0);
		ini_set('display_errors',0);
}