<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Discussions extends MY_Controller {
	
	private $data = array();
	
	public function add_discussion(){
		echo"add a discussion";
		echo"<pre>";print_r($_POST);echo"</pre>";
		exit();
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */