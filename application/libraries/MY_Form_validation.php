<?php

class MY_Form_validation extends CI_Form_validation{
	
	function __construct(){
		parent::__construct();
	}
	
	public function errors_exist(){
		return (!empty($this->_error_array)) ? true : false;
	}
	
	public function get_errors(){
		return $this->_error_array;
	}
	
}