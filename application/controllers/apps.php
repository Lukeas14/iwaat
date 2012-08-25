<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apps extends MY_Controller {
	
	private $data = array();
	
	public function app(){
		$this->load->driver('cache');

		$this->load->model('app');
		
		$app_slug = $this->uri->segment(2);
		$cache_app_id = 'app_' . $app_slug;
		if($this->cache->memcached->is_supported()){
			if(!$app = $this->cache->memcached->get($this->app->get_app_cache_id($app_slug))){
				$app = $this->app->get_app($app_slug);

				$this->cache->memcached->save($this->app->get_app_cache_id($app_slug), $app, CACHE_TIME_DAY);
			}
		}
		else{
			$app = $this->app->get_app($app_slug);
		}
		
		if(empty($app)){
			show_404();
		}
		elseif($app['status'] != 'active'){
			show_404();
		}
		
		//Get related apps
		$related_apps = $this->app->get_related_apps($app['id'], 0, 6);
		$app['related_apps'] = (!empty($related_apps->response->docs)) ? $related_apps->response->docs : array();
		
		$this->data['app'] = $app;
		
		$this->data['meta']['title'] = $app['name'] . " Profile | IWAAT.com";
	
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
			
			if ($this->form_validation->run() === false){
				$this->data['notifications']['error'] = $this->form_validation->get_errors();
			}
			else{
				$this->data['suggest_app'] = $this->input->post();

				//Send admin an email
				$this->load->library('swift_email');
				$email_params = array(
					'html'		=> $this->load->view('email/suggest_app_admin_html', $this->data, true),
					'text'		=> '',
					'subject'	=> $this->data['suggest_app']['app_name'] . ' suggested to IWAAT.com',
					'to'		=> array(ADMIN_EMAIL_ADDRESS => ADMIN_EMAIL_NAME),
					'from'		=> array($this->data['suggest_app']['email'])
				);
				$this->swift_email->send_email($email_params);

				$this->load->view('suggest_app_success', $this->data);
			}
		}
		
		
		$this->load->view('suggest_app', $this->data);
	}

	public function claim_app(){
		//Make sure user is logged in
		if (!$this->ion_auth->logged_in()){
			$this->session->set_flashdata('redirect', $_SERVER['REQUEST_URI']);
			$this->session->set_flashdata('message_box', array('type' => 'claim_app', 'app_slug' => $this->uri->segment(2)));
			redirect('/login_register');
		}

		$this->load->driver('cache');

		$this->load->model('app');

		//Get user data
		$this->data['user_profile'] = $this->ion_auth->user()->row();

		//Get app data
		$app_slug = $this->uri->segment(2);
		$cache_app_id = 'app_' . $app_slug;
		if($this->cache->memcached->is_supported()){
			if(!$app = $this->cache->memcached->get($this->app->get_app_cache_id($app_slug))){
				$app = $this->app->get_app($app_slug);

				$this->cache->memcached->save($this->app->get_app_cache_id($app_slug), $app, CACHE_TIME);
			}
		}
		else{
			$app = $this->app->get_app($app_slug);
		}

		$this->data['app'] = $app;
		
		//Make sure app is valid
		if(empty($app)){
			show_404();
		}
		elseif($app['status'] != 'active'){
			show_404();
		}

		//Send email to admin for review
		$this->load->library('swift_email');
		$email_params = array(
			'html'		=> $this->load->view('email/claim_app_admin_html', $this->data, true),
			'text'		=> '',
			'subject'	=> $this->data['user_profile']->username . ' claimed the app ' . $app['name'],
			'to'		=> array(ADMIN_EMAIL_ADDRESS => ADMIN_EMAIL_NAME),
			'from'		=> array($this->data['user_profile']->email => $this->data['user_profile']->username)
		);
		$this->swift_email->send_email($email_params);

		if(!empty($this->data['app']['urls']['homepage'])){
			$app_url = parse_url($this->data['app']['urls']['homepage']);
			if(!empty($app_url['host'])){
				$this->data['app']['hostname'] = str_replace('www.', '', $app_url['host']);
			}
		}

		$this->data['meta']['title'] = "Claim" . $app['name'] . " | IWAAT.com";

		$this->set_css('claim_app.css');

		$this->load->view('claim_app', $this->data);
	}
	
	public function add_app(){
		
		$this->load->view('add_app', $this->data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */