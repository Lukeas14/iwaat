<?php

require_once('discussion.php');

class Review extends Discussion{
	
	public $structure = array();

	public function __construct(){
		parent::__construct();
	}

	public function get_review($id){
		return $this->get_discussion(array('type' => 'review', 'display_id' => $id));
	}

	public function add_review($data){
		if(!$this->validate_review($data)) return false;

		$review = $this->new_discussion;

		$review['type'] = 'review';
		$review['user_id'] = intval($data['user_id']);
		$review['app_id'] = intval($data['app_id']);
		$review['title'] = $data['title'];
		$review['text'] = $data['text'];
		$review['time_created'] = new MongoDate();
		$review['time_posted'] = new MongoDate();
		$review['time_last_updated'] = new MongoDate();

		return $this->add_discussion($review);
	}

	public function update_review($review_id, $data){
		return $this->mongo->db->discussions->update(array('_id' => $review_id), array('$set' => $data), array('safe' => true));
	}

	public function get_user_app_review($user_id, $app_id){
		return $this->mongo->db->discussions->findOne(array('user_id' => $user_id, 'app_id' => $app_id));
	}

	private function validate_review($data){
		$required_fields = array('user_id', 'app_id', 'title', 'text');

		foreach($required_fields as $field){
			if(empty($data[$field])){
				return false;
			}
		}

		return true;
	}
}