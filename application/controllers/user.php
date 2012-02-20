<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {
	
	private $data = array(
		'notifications' => array()
	);
	
	public function login_register(){
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$redirect = ($this->session->flashdata('redirect') !== false) ? $this->session->flashdata('redirect') : '/account/profile';
		$this->session->set_flashdata('redirect', $redirect);		
		
		$this->load->view('login_register', $this->data);
	}
	
	public function login(){
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('login_email', 'Email Address', 'trim|required|valid_email');
		$this->form_validation->set_rules('login_password', 'Password', 'trim|required');
		
		if($this->form_validation->run() === true){
			$remember = (bool) $this->input->post('login_remember');
			
			if($this->ion_auth->login($this->input->post('login_email'), $this->input->post('login_password'), $remember)){
				$this->session->set_flashdata('confirm', $this->ion_auth->messages());
				
				$redirect = ($this->session->flashdata('redirect') !== false) ? $this->session->flashdata('redirect') : '/account/profile';
				redirect($redirect, 'refresh');
			}
			else{
				$this->session->set_flashdata('error', $this->ion_auth->errors());
				$this->session->keep_flashdata('redirect');
				redirect('login_register', 'refresh');
			}
		}
		else{
			$this->data['notifications']['error'] = $this->form_validation->get_errors();
			
			$this->session->keep_flashdata('redirect');
			$this->load->view('login_register', $this->data);
		}
		
	}
	
	public function register(){
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('register_first_name', 'First Name', 'trim|required|xss_clean|max_length[64]');
		$this->form_validation->set_rules('register_last_name', 'Last Name', 'trim|required|xss_clean|max_length[64]');
		$this->form_validation->set_rules('register_email', 'Email Address', 'trim|required|valid_email');
		$this->form_validation->set_rules('register_password', 'Password', 'trim|required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[register_confirm_password]');
		$this->form_validation->set_rules('register_confirm_password', 'Confirm Password', 'trim|required');
		
		if($this->form_validation->run() === true){
			$username = $this->input->post('register_first_name') . ' ' . $this->input->post('register_last_name');
			$email = $this->input->post('register_email');
			$password = $this->input->post('register_password');
			
			$additional_data = array(
				'first_name' => $this->input->post('register_first_name'),
				'last_name' => $this->input->post('register_last_name')
			);
		}
		if($this->form_validation->run() === true && $this->ion_auth->register($username, $password, $email, $additional_data)){
			$this->load->library('swift_email');
			$this->data['user_data'] = array(
				'username'	=> $username,
				'email'		=> $email,
				'password'	=> $password
			);
			$email_params = array(
				'html'		=> $this->load->view('email/register_html', $this->data, true),
				'text'		=> $this->load->view('email/register_text', $this->data, true),
				'subject'	=> 'Welcome to I Want An App That...',
				'from'		=> array(ADMIN_EMAIL_ADDRESS => ADMIN_EMAIL_NAME),
				'to'		=> array($email => $username)
			);
			$this->swift_email->send_email($email_params);
			
			$this->session->set_flashdata('confirm', 'Account created.  Welcome to IWAAT.com');
			
			$this->ion_auth->login($email, $password, false);
					
			$redirect = ($this->session->flashdata('redirect') !== false) ? $this->session->flashdata('redirect') : '/account/profile';
			redirect($redirect, 'refresh');
		}
		else{
			if($this->form_validation->errors_exist()){
				$this->data['notifications']['error'] = $this->form_validation->get_errors();
			}
			elseif($this->ion_auth->errors()){
				$this->data['notifications']['error'] = $this->ion_auth->errors();
			}
			else{
				$this->data['notifications']['error'] = array('Error registering account.');
			}
			
			$this->session->keep_flashdata('redirect');
		}
		
		$this->load->view('login_register', $this->data);
	}
	
	public function logout(){
		$logout = $this->ion_auth->logout();
		
		$this->session->set_flashdata('confirm', 'You have successfully logged out.');
	
		redirect('/login_register?confirm=You have successfully logged out.', 'location');
	}
	
	public function account_profile(){
		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata('message', 'Please log in or register to edit your account profile');
			$this->session->set_flashdata('redirect', $this->uri->uri_string());
			redirect('/login_register');
		}
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean|max_length[64]');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|xss_clean|max_length[64]');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
		
		if($this->form_validation->run() === true){
			$update_data = array(
				'first_name'	=> $this->input->post('first_name'),
				'last_name'		=> $this->input->post('last_name'),
				'email'			=> $this->input->post('email'),
				'username'		=> $this->input->post('first_name') . ' ' . $this->input->post('last_name')
			);
			$user_update = $this->ion_auth->update(null, $update_data);
			if($user_update){
				$this->session->set_flashdata('confirm', 'Your profile has been updated.');
				redirect('/account/profile', 'location');
			}
			else{
				$this->session->set_flashdata('error', $this->ion_auth->errors());
				redirect('/account/profile', 'location');
			}
		}
		else{
			$this->data['notifications']['error'] = $this->form_validation->get_errors();
		}
		
		$this->data['user_profile'] = $this->ion_auth->user()->row();
		
		$this->load->view('account_profile', $this->data);
	}
	
	public function account_add_app(){
		if(!$this->ion_auth->logged_in()){
			$this->session->set_flashdata('message', 'Please log in or register to add an application.');
			$this->session->set_flashdata('redirect', $this->uri->uri_string());
			redirect('/login_register', 'location');
		}
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('app');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('tagline', 'Tagline', 'trim');
		$this->form_validation->set_rules('description', 'Description', 'trim');
		$this->form_validation->set_rules('date_launched', 'Date Launched', 'trim');
		$this->form_validation->set_rules('tags', 'Tags', 'trim');
		$this->form_validation->set_rules('urls[homepage]', 'Homepage URL', 'trim|required');
		$this->form_validation->set_rules('urls[blog]', 'Blog URL', 'trim');
		$this->form_validation->set_rules('urls[rss]', 'Blog RSS URL', 'trim');
		$this->form_validation->set_rules('urls[twitter]', 'Twitter', 'trim');
		
		if($this->form_validation->run() === true){
			$accepted_fields = array('name', 'tagline', 'description', 'email', 'date_launched', 'tags', 'urls');
			$add_app_data = array();
			foreach($this->input->post() as $post_field => $post_val){
				if(in_array($post_field, $accepted_fields)){
					$add_app_data[$post_field] = $post_val;
				}
			}
			
			$profile = $this->ion_auth->user()->row();
			$add_app_data['owner_id'] = $profile->id;
			
			$add_app_data['status'] = 'pending_review';
			
			if(!$app_id = $this->app->add_app($add_app_data)){
				$this->data['notifications']['error'] = $this->app->errors;
			}
			else{
				$this->app->update_app_tags($app_id, $add_app_data['tags']);
				
				$this->app->update_app_urls($app_id, $add_app_data['urls']);
				
				if($_FILES['logo']['size'] > 0){
					//Upload Logo
					$image_config = array(
						'upload_path'	=> './tmp',
						'allowed_types'	=> 'gif|jpg|png',
						'max_size'		=> 2048,
						'file_name'		=> md5($app_id . 'logo')
					);
					$this->load->library('upload', $image_config);
					if(!$this->upload->do_upload('logo')){
						$logo_data = $this->upload->data();
						if(!empty($logo_data['file_name'])){
							$this->session->set_flashdata('error', $this->upload->display_errors());
						}
					}
					else{
						$logo_data = $this->upload->data();
						$this->app->add_app_image_from_upload($app_id, 'logo', $logo_data);
					}
				}
				
				$this->session->set_flashdata('confirm', $add_app_data['name'] . ' Application Added.');
				redirect('/account/edit_app/' . $this->app->app_slug, 'location');
			}
		}
		else{
			$this->data['notifications']['error'] = $this->form_validation->get_errors();
		}
		
		$this->data['user_profile'] = $this->ion_auth->user()->row();
		$this->data['user_apps'] = $this->app->get_apps(array('owner_id'=>$this->data['user_profile']->id));
		
		$this->load->view('account_add_app', $this->data);
	}
	
	public function account_edit_app(){
		if(!$this->ion_auth->logged_in()){
			$this->session->set_flashdata('message', 'Please log in to edit an application.');
			$this->session->set_flashdata('redirect', $this->uri->uri_string());
			redirect('/login_register', 'location');
		}
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('app');
		
		$app_slug = $this->uri->segment(3);
		
		$this->data['user_profile'] = $this->ion_auth->user()->row();
		$this->data['user_apps'] = $this->app->get_apps(array('owner_id'=>$this->data['user_profile']->id));
		
		$app = $this->app->get_app($app_slug);
		if(empty($app)){
			show_404();
		}
		$this->data['app'] = $app;
		
		$this->load->view('account_edit_app', $this->data);
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
	
	function change_password()
	{
		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata('message', 'Please log in or register to change your password.');
			$this->session->set_flashdata('redirect', $this->uri->uri_string());
			redirect('/login_register');
		}
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->data['user_profile'] = $this->ion_auth->user()->row();
		
		$this->form_validation->set_rules('old', 'Old password', 'required');
		$this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', 'Confirm New Password', 'required');
		
		if($this->form_validation->run() === true){
			$identity = $this->session->userdata($this->config->item('identity','ion_auth'));
			
			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));
			if($change){
				$this->session->set_flashdata('confirm', 'Your password has been changed.');
				redirect('/account/profile', 'location');
			}
			else{
				$this->session->set_flashdata('error', $this->ion_auth->errors());
				redirect('/account/change_password', 'location');
			}
		}
		else{
			$this->data['notifications']['error'] = $this->form_validation->get_errors();
		}
		
		$this->load->view('account_change_password', $this->data);
	}
	
	//forgot password
	function forgot_password()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('email', 'Email Address', 'required');
		
		if($this->form_validation->run() === true){
			$forgotten = $this->ion_auth->forgotten_password($this->input->post('email'));
			
			if($forgotten){
				$this->session->set_flashdata('confirm', $this->ion_auth->messages());
				redirect('/login_register', 'location');
			}
			else{
				$this->session->set_flashdata('error', $this->ion_auth->errors());
				redirect('/account/forgot_password', 'location');
			}
		}
		else{
			$this->data['notifications']['error'] = $this->form_validation->get_errors();
		}
		
		$this->load->view('/account_forgot_password', $this->data);
	}

	public function reset_password($code)
	{
		$reset = $this->ion_auth->forgotten_password_complete($code);

		if ($reset)
		{  //if the reset worked then send them to the login page
			$this->session->set_flashdata('confirm', $this->ion_auth->messages());
			redirect("/login_register", 'location');
		}
		else
		{ //if the reset didnt work then send them back to the forgot password page
			$this->session->set_flashdata('error', $this->ion_auth->errors());
			redirect("/account/forgot_password", 'location');
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */