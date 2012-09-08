<?php

class CI_Mongo extends Mongo{

	public $db;

	protected $collection;
	
	public function __construct(){
		$server_url = $this->get_server_url();

		parent::__construct($server_url);

		$this->db = $this->{MONGODB_NAME};

		//$this->discussions = $this->get_collection('discussions');
	}

	private function get_server_url(){
		if(defined('MONGODB_PASS') && MONGODB_PASS != '') {
			$mongo_url = "mongodb://" . MONGODB_USER . ":" . MONGODB_PASS . "@" . MONGODB_HOST . ":" . MONGODB_PORT;
		}
		else{
			$mongo_url = "mongodb://" .  MONGODB_HOST . ":" . MONGODB_PORT;
		}

		return $mongo_url;
	}

	/*private function get_collection($collection_name){
		return $this->db->{$collection_name};
	}*/

}