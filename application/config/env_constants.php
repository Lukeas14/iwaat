<?php

$environments = array('development','testing','production');
$host = array('dev.iwaat.com', 'test.iwaat.com', 'www.iwaat.com');
$host_environments = array_combine($host, $environments);


if(!empty($argv)){
	foreach($argv as $arg){
		if(in_array($arg, array('development', 'testing', 'production'))){
			define('ENVIRONMENT', $arg);
		}
	}
}

if(!defined('ENVIRONMENT') && !empty($_SERVER['HTTP_HOST'])){
	if(array_key_exists($_SERVER['HTTP_HOST'], $host_environments)){
		define('ENVIRONMENT', $host_environments[$_SERVER['HTTP_HOST']]);
	}
}

if(!defined('ENVIRONMENT')){
	define('ENVIRONMENT', 'production');
}

switch(ENVIRONMENT){
	case 'development':
		define('BASE_URL', 'http://dev.iwaat.com');
		
		error_reporting(E_ALL);
		ini_set('display_errors',1);
		
		define('DB_HOST', 'localhost');
		define('DB_USER', 'jiwaatlucas');
		define('DB_PASS', 'j23waati$lucas');
		define('DB_NAME', 'iwaat');
		
		define('SOLR_USERNAME', 'jwasolrat');
		define('SOLR_PASSWORD', 'joi2!d9s@kd#@d01)#');
		break;
	
	case 'testing':
		define('BASE_URL', 'http://test.iwaat.com');
		
		error_reporting(E_ALL);
		ini_set('display_errors',1);
		
		define('DB_HOST', 'localhost');
		define('DB_USER', 'jiwaatlucas');
		define('DB_PASS', 'j3oi2@$I@OJ$wmew');
		define('DB_NAME', 'iwaat');
		
		define('SOLR_USERNAME', 'jwasolrat');
		define('SOLR_PASSWORD', 'joi2!d9s@kd#@d01)#');
		break;
	
	case 'production':
	default:
		define('BASE_URL', 'http://www.iwaat.com');
		
		error_reporting(E_ALL);
		ini_set('display_errors',1);
		
		define('DB_HOST', 'localhost');
		define('DB_USER', 'jliwaatwesley');
		define('DB_PASS', 'eiof2!J@Issd$I6kfsd4');
		define('DB_NAME', 'iwaat');
		
		define('SOLR_USERNAME', 'jwasolrat');
		define('SOLR_PASSWORD', 'joi2!d9s@kd#@d01)#');
}