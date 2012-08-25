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

	public function claim_app(){
		$this->load->driver('cache');

		$this->load->model('app');

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
		
		if(empty($app)){
			show_404();
		}
		elseif($app['status'] != 'active'){
			show_404();
		}

		if(!empty($app['urls']['homepage'])){
			$app_url = parse_url($app['urls']['homepage']);
			if(!empty($app_url['host'])){
				$app['hostname'] = $app_url['host'];
			}
		}

		$this->data['app'] = $app;

		$this->data['meta']['title'] = "Claim" . $app['name'] . " | IWAAT.com";

		$this->load->view('claim_app', $this->data);
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
	
	public function add_app(){
		
		$this->load->view('add_app', $this->data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */