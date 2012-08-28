<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Discussions extends MY_Controller {
	
	private $data = array();
	
	public function add_discussion(){
		$this->load->helper(array('form', 'url'));

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$this->load->library('form_validation');

			$validation_rules = array(
				array(
					'field' => ''
				)
			);
		}
		else{

		}
		echo"add a discussion";
		echo"<pre>";print_r($_POST);echo"</pre>";
		exit();
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */