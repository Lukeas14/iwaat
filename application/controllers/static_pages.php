<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Static_pages extends MY_Controller {
	
	public $data;
	
	public function about_us(){
		$this->load->view('about_us', $this->data);
	}
	
	public function contact_us(){
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		
		if($this->form_validation->run() === true){
			$message = "
			Name: " . $this->input->post('name') . "
			Email: " . $this->input->post('email') . "
			Message: " . $this->input->post('message') . "	
			";
		}
		else{
			if($this->form_validation->errors_exist()){
				$this->data['notifications']['error'] = $this->form_validation->get_errors();
			}
		}
			
		
		$this->load->view('contact_us', $this->data);
	}
	
	public function terms_service(){
		$this->load->view('terms_service', $this->data);
	}
	
	public function privacy_policy(){
		$this->load->view('privacy_policy', $this->data);
	}
	
}
