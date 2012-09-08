<?php

require_once('discussion.php');

class Question extends Discussion{
	
	public $structure = array();

	public function __construct(){
		parent::__construct();
	}

	public function get_question($id){
		return $this->get_discussion(array('type' => 'question', 'display_id' => $id));
	}

	public function add_question($data){
		if(!$this->validate_question($data)) return false;

		$question = $this->new_discussion;

		$question['type'] = 'question';
		$question['user_id'] = intval($data['user_id']);
		$question['app_id'] = intval($data['app_id']);
		$question['title'] = $data['title'];
		$question['text'] = $data['text'];
		$question['time_created'] = new MongoDate();
		$question['time_posted'] = new MongoDate();
		$question['time_last_updated'] = new MongoDate();

		return $this->add_discussion($question);
	}

	public function update_review($review_id, $data){
		return $this->mongo->db->discussions->update(array('_id' => $review_id), array('$set' => $data), array('safe' => true));
	}

	public function get_user_app_review($user_id, $app_id){
		return $this->mongo->db->discussions->findOne(array('user_id' => $user_id, 'app_id' => $app_id));
	}

	private function validate_question($data){
		$required_fields = array('user_id', 'app_id', 'title', 'text');

		foreach($required_fields as $field){
			if(empty($data[$field])){
				return false;
			}
		}

		return true;
	}
}