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
		
		//Get related apps
		$related_apps = $this->app->get_related_apps($app['id'], 0, 6);
		$app['related_apps'] = (!empty($related_apps->response->docs)) ? $related_apps->response->docs : array();
		
		$this->data['app'] = $app;
	
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