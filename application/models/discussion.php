<?php

class Discussion extends CI_Model{
	
	private $conn;
	private $db;
	private $discussions_coll;

	function __construct(){
		parent::__construct();

		//Connect to Mongodb server
		if(defined('MONGODB_PASS')){
			$mongo_url = "mongodb://" . MONGODB_USER . ":" . MONGODB_PASS . "@" . MONGODB_HOST . ":" . MONGODB_PORT;
		}
		else{
			$mongo_url = "mongodb://" .  MONGODB_HOST . ":" . MONGODB_PORT;
		}
		$this->conn = new Mongo($mongo_url . '/iwaat');

		//Connect to iwaat database
		$this->db = $this->conn->iwaat;

		$this->discussions_coll = $this->db->discussions;
	}

	public function get_app_discussions($options){
		$params = array();

		if(!empty($options['app_id']) && is_numeric($options['app_id'])){
			$params['app_id'] = intval($options['app_id']);
		}

		if(!empty($options['type']) && in_array($options['type'], array('blog_post','app_tweet','review'))){
			$params['type'] = $options['type'];
		}
		/*
		print_r($options);
		echo"<br/>";
		print_r($params);
		echo"<br/>";
		*/
		$discussions = $this->discussions_coll->find($params);

		return $discussions;
	}


}