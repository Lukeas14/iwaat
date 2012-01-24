<?php

function get_index_color($index){
	if(empty($index) || !is_numeric($index)) $index = 0;
	$alpha_array = array(
			0 => array('bg'=>'FFFF00', 'text'=>'000000'),
			1 => array('bg'=>'FFEE00', 'text'=>'003300'),
			2 => array('bg'=>'FFDD00', 'text'=>'006600'),
			3 => array('bg'=>'FFCC00', 'text'=>'009900'),
			4 => array('bg'=>'FFBB00', 'text'=>'009900'),
			5 => array('bg'=>'FFAA00', 'text'=>'009900'),
			6 => array('bg'=>'FF8800', 'text'=>'009900'),//FFFFCC'),
			7 => array('bg'=>'FF6600', 'text'=>'009900'),
			8 => array('bg'=>'FF4400', 'text'=>'99FF33'),
			9 => array('bg'=>'FF2200', 'text'=>'CCFF00'),
			10 => array('bg'=>'FF0000', 'text'=>'FFFF00'),
		);

	$index_val = round($index / 10);
	$hex_codes = $alpha_array[$index_val];
	return $hex_codes['bg'];
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