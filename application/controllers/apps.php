<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apps extends MY_Controller {
	
	private $data = array();
	
	public function app(){
		$this->load->model('app');
		
		$app_slug = $this->uri->segment(2);
		
		$app = $this->app->get_app($app_slug);
		if(empty($app)){
			show_404();
		}
		elseif($app['status'] != 'active'){
			show_404();
		}
		$this->data['app'] = $app;
		//echo"<pre>";print_r($app);echo"</pre>";
	
		$this->load->view('app', $this->data);
	}
	
	public function suggest_app(){
		$this->load->helper(array('form', 'url'));
		
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$this->load->model('app');
			$this->load->library('form_validation');

			$validation_rules = array(
				array(
					'field' => 'email',
					'label' => 'Email',
					'rules' => 'trim|required|valid_email'
				),
				array(
					'field' => 'app_name',
					'label' => 'App Name',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'app_url',
					'label' => 'App URL',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'app_description',
					'label' => 'App Description',
					'rules' => 'trim'
				),
			);
			$this->form_validation->set_rules($validation_rules);
			
			if ($this->form_validation->run() == FALSE){
				$this->load->view('suggest_app', $this->data);
			}
			else{
				$this->load->view('suggest_app_success', $this->data);
			}
		}
		
		
		$this->load->view('suggest_app', $this->data);
	}
	
	public function add_app(){
		
		$this->load->view('add_app', $this->data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */