<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	public $data;
	
	public function index(){
		$this->load->model('app');
		$this->load->helper('form');
		
		
		$this->data['homepage_categories'] = $this->app->get_categories(0);
		
		$this->data['homepage_apps'] = $this->app->get_homepage_apps($this->data['homepage_categories']);
		//echo"<pre>";print_r($this->data['homepage_apps']);echo"</pre>";
		
		$this->data['is_homepage'] = true;
		
		$this->load->view('home', $this->data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */