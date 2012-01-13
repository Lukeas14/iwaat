<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends MY_Controller {

	public function contact_us(){
		$this->load->helper('form');
		$this->load->view('contact_us');
	}
}
