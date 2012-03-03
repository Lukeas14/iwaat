<?php

function get_index_color($index){
	if(empty($index) || !is_numeric($index)) $index = 0;
	$alpha_array = array(
			0 => array('bg'=>'FFFF00', 'text'=>'555'),
			1 => array('bg'=>'FFEE00', 'text'=>'555'),
			2 => array('bg'=>'FFDD00', 'text'=>'555'),
			3 => array('bg'=>'FFCC00', 'text'=>'FFF'),
			4 => array('bg'=>'FFBB00', 'text'=>'FFF'),
			5 => array('bg'=>'FFAA00', 'text'=>'FFF'),
			6 => array('bg'=>'FF8800', 'text'=>'FFFFFF'),//FFFFCC'),
			7 => array('bg'=>'FF6600', 'text'=>'EEEEEE'),
			8 => array('bg'=>'FF4400', 'text'=>'EEEEEE'),
			9 => array('bg'=>'FF2200', 'text'=>'DDDDDD'),
			10 => array('bg'=>'FF0000', 'text'=>'DDDDDD'),
		);

	$index_val = round($index / 10);
	//echo $index_val;
	$hex_codes = $alpha_array[$index_val];
	//return $hex_codes['bg'];
	return $hex_codes;
}

function truncate ($string, $limit){
	if(strlen($string) > $limit){
		return substr($string, 0, $limit)."...";
	}
	else{
		return $string;
	}
}

function get_relative_time($time){
	$unix_time = strtotime($time);
	$today = strtotime(date('Y-m-d'));
	
	//Time not valid
	if(!is_numeric($unix_time) || $unix_time <= 0){
		return false;
	}
	
	//Today
	if($unix_time >= $today){
		return 'Today';
	} 
	//Yesterday
	elseif($unix_time > ($today - 86400)){
		return 'Yesterday';
	}
	//Days ago
	elseif($unix_time > ($today - 604800)){
		return ceil(($today - $unix_time) / 86400). ' days ago';
	}
	//Weeks ago
	elseif($unix_time > ($today - 2592000)){
		return ceil(($today - $unix_time) / 604800). ' weeks ago';
	}
	//Months ago
	elseif($unix_time > ($today - 31104000)){
		return ceil(($today - $unix_time) / 2592000). ' months ago';
	}
	elseif($unix_time > 0){
		return ceil(($today - $unix_time) / 31104000). ' years ago';
	}
	else{
		return false;
	}
}

function get_notifications($controller_notifications){
	$CI =& get_instance();
	
	$notifications = array();
	
	/*hforeach(array('message','error') as $note_type){
		if(array_key_exists($note_type, $_GET)){
			$notifications[$note_type] = array();
			if(is_array($_GET[$note_type])){
				array_merge($notifications[$note_type], $_GET[$note_type]);
			}
			else{
				$notifications[$note_type][] = $_GET[$note_type];
			}
		}
	}*/
	
	foreach(array('message', 'error', 'confirm') as $note_type){
		$notifications[$note_type] = array();
		
		$note_val = $CI->session->flashdata($note_type);
		if(!empty($note_val)){
			if(is_array($note_val)){
				$notifications[$note_type] = array_merge($notifications[$note_type], $note_val);
			}
			else{
				$notifications[$note_type][] = strip_tags($note_val, '<b><a><i>');
			}
		}
		
		if(!empty($controller_notifications[$note_type])){
			$note_val = $controller_notifications[$note_type];
			if(!empty($note_val)){
				if(is_array($note_val)){
					$notifications[$note_type] = array_merge($notifications[$note_type], $note_val);
				}
				else{
					$notifications[$note_type][] = strip_tags($note_val, '<b><a><i>');
				}
			}
		}
		
		if(!empty($_GET[$note_type])){
			$note_val = $_GET[$note_type];
			if(!empty($note_val)){
				if(is_array($note_val)){
					$notifications[$note_type] = array_merge($notifications[$note_type], $note_val);
				}
				else{
					$notifications[$note_type][] = strip_tags($note_val, '<b><a><i>');
				}
			}
		}
		
		if(empty($notifications[$note_type])){
			unset($notifications[$note_type]);
		}
	}
	
	return $notifications;
}

function get_app_image_directory($app_id){
	return substr($app_id, -2);
}

function get_app_image($app_id, $file_name){
	return "/images/apps/" . get_app_image_directory($app_id) . "/" . $file_name;
}

function get_ip_address(){
	if (isset($_SERVER)){
           if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
               return $_SERVER["HTTP_X_FORWARDED_FOR"];

           if (isset($_SERVER["HTTP_CLIENT_IP"]))
               return $_SERVER["HTTP_CLIENT_IP"];

           return $_SERVER["REMOTE_ADDR"];
    }
	
	if (getenv('HTTP_X_FORWARDED_FOR'))
	   return getenv('HTTP_X_FORWARDED_FOR');

	if (getenv('HTTP_CLIENT_IP'))
	   return getenv('HTTP_CLIENT_IP');

    return getenv('REMOTE_ADDR');
}

function validate_email_address($email){
	if(filter_var($email, FILTER_VALIDATE_EMAIL)){
		return true;
    }
    return false;
}