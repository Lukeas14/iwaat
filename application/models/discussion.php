<?php

class Discussion extends CI_Model{

	const ASCENDING = 1;
	const DESCENDING = -1;
	
	protected $new_discussion = array(
		'type' => null,
		'user_id' => null,
		'app_id' => null,
		'score' => 0,
		'flags' => 0,
		'views' => 0,
		'title' => null,
		'text' => null,
		'updates' => array(),
		'time_created' => null,
		'time_last_updated' => null,
		'status' => 'active'
	);

	function __construct(){
		parent::__construct();

		$this->load->library('mongo');
	}

	public function get_constant($name){
		return $this->$name;
	}

	public function get_discussions($options){
		$params = $this->get_params($options);

		$discussions = $this->mongo->db->discussions->find($params);
		
		if(!empty($options['sort'])){
			$discussions = $discussions->sort($options['sort']);
		}

		if(!empty($options['limit']) && is_numeric($options['limit'])){
			$discussions = $discussions->limit($options['limit']);
		}

		return $discussions;
	}

	public function get_discussions_count($discussion_cursor){
		return $discussion_cursor->count();
	}

	public function get_discussions_apps($discussion_cursor){
		$this->load->model('app');

		$app_ids = array();
		foreach($discussion_cursor as $discussion){
			if(!empty($discussion['app_id'])) $app_ids[] = $discussion['app_id'];
		}

		$apps = $this->app->get_apps(null, null, 0, null, null, null, null, array('field' => 'id', 'values' => $app_ids));



		return $apps;
	}

	public function get_discussion($options){
		$params = $this->get_params($options);

		return $this->mongo->db->discussions->findOne($params);
	}

	protected function add_discussion($data){
		$data['display_id'] = $this->create_display_id();

		$result =  $this->mongo->db->discussions->insert($data, array('safe' => true));

		return array(
			'data' => $data,
			'status' => $result
		);
	}

	private function get_params($options){
		$params = array();

		if(!empty($options['app_id']) && is_numeric($options['app_id'])){
			$params['app_id'] = intval($options['app_id']);
		}

		if(!empty($options['user_id']) && is_numeric($options['user_id'])){
			$params['user_id'] = intval($options['user_id']);
		}

		if(!empty($options['type'])){
			if(is_array($options['type'])){
				$params['type'] = array('$in' => $options['type']);
			}
			elseif(array_key_exists($options['type'], $this->config->item('discussion_types'))){
				$params['type'] = $options['type'];
			}
		}

		if(!empty($options['display_id'])){
			$params['display_id'] = $options['display_id'];
		}

		return $params;
	}

	private function create_display_id(){
		$display_id = null;

		while($display_id === null){
			$random = '';
			for ($i = 0; $i < DISPLAY_ID_LENGTH; $i++) {
				$random .= chr(rand(ord('a'), ord('z')));
			}

			if($this->get_discussion(array('display_id' => $random)) === null){
				$display_id = $random;
			}
		}

		return $display_id;
	}

}