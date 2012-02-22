<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	public $data;
	
	public function index(){
		$this->load->model('app');
		$this->load->helper('form');
		$this->load->driver('cache');
		
		$homepage_apps_cache_id = 'homepage_apps';
		$homepage_apps_cache_time = CACHE_TIME;
		if($this->cache->memcached->is_supported()){
			if(!$homepage_apps = $this->cache->memcached->get($homepage_apps_cache_id)){
				$homepage_apps = $this->app->get_homepage_apps();
				
				$this->cache->memcached->save($homepage_apps_cache_id, $homepage_apps, $homepage_apps_cache_time);
			}
		}
		else{
			$homepage_apps = $this->app->get_homepage_apps();
		}
		$this->data['homepage_apps'] = $homepage_apps['apps'];
		$this->data['homepage_categories'] = $homepage_apps['categories'];
		
		$this->data['is_homepage'] = true;
		
		$this->load->view('home', $this->data);
	}
	
	public function add_newsletter_email(){
		if($_SERVER['REQUEST_METHOD'] != 'POST' || !$this->input->post('newsletter_email')){
			show_404();
		}
		
		$add_newsletter_email = $this->ion_auth->add_newsletter_email($this->input->post('newsletter_email'));
		
		return;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */