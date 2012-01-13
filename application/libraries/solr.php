<?php

//Require solr service
require_once(dirname(__FILE__) . '/solr/php_client/Apache/Solr/Service.php');

class Solr extends Apache_Solr_Service{
	
	function __construct(){
		parent::__construct('localhost',8983,'/solr/');
	}
	
	public function escape_query($query){
		//esacpe query
		$match = array('\\', '+', '-', '&', '|', '!', '(', ')', '{', '}', '[', ']', '^', '~', '*', '?', ':', '"', ';');
        $replace = array('\\\\', '\\+', '\\-', '\\&', '\\|', '\\!', '\\(', '\\)', '\\{', '\\}', '\\[', '\\]', '\\^', '\\~', '\\*', '\\?', '\\:', '\\"', '\\;', '\\ ');
        $escaped_query = "(".str_replace($match, $replace, $query).")";
		
		return $escaped_query;
	}
	
}