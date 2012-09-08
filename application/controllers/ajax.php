<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function app_discussions(){
		$this->load->model('discussion');

		$this->data['app_id'] = $this->input->get('app_id');

		if(empty($this->data['app_id']) || !is_numeric($this->data['app_id'])){
			show_404();
		}

		header('Content-type: application/json');

		$options = array(
			'app_id' => $this->input->get('app_id'),
			'type' => $this->config->item('app_profile_discussion_types'),
			'sort' => array('time_posted' => MONGODB_DESCENDING)
		);

		$app_discussions = $this->discussion->get_discussions($options);
		
		$discussions = array();
		$user_ids = array();

		foreach($app_discussions as $discussion){
			if(!empty($discussion['user_id'])){
				$user_ids[] = $discussion['user_id'];
			}
			$discussions[] = $discussion;
		}

		$users = $this->ion_auth->where_in('id', $user_ids)->users()->result_array();

		//echo"<pre>";print_r($users);echo"</pre>";
		//exit();
		echo json_encode(array('discussions' => $discussions, 'users' => $users));
		
		return;
	}

}