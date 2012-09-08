<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		
		//Only admins allowed to access these pages
		if (!$this->ion_auth->logged_in()){
			show_404();
		}
		if(!$this->ion_auth->is_admin()){
			show_404();
		}
		
		$this->load->model('company');
	}

	//redirect if needed, otherwise display the user list
	function index()
	{
	
		$this->load->view('admin_index');
	}
	
	function apps(){
		$this->load->library('pagination');
		$this->load->model('app');
		
		$config['offset'] = (!is_numeric($this->uri->segment($this->pagination->uri_segment))) ? 0 : $this->uri->segment($this->pagination->uri_segment);
		$config['base_url'] = '/admin/apps';
		$config['per_page'] = '20'; 
		
		$this->data['apps'] = $this->app->get_apps(array(), array(), $config['offset'], $config['per_page'], 'category_id IS NULL');
		
		
		$config['total_rows'] = $this->data['apps']['total_apps'];
		
		$this->pagination->initialize($config);
		$this->data['pagination_links'] = $this->pagination->create_links();
		
		$this->load->view('admin_apps', $this->data);
	}
	
	function app(){
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('app');
		
		$app_slug = $this->uri->segment(3);
		
		$this->form_validation->set_rules('app_id', 'App ID', 'trim|required');
		$this->form_validation->set_rules('http_referer', 'HTTP Referer', '');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('tagline', 'Tagline', 'trim');
		$this->form_validation->set_rules('description', 'Description', 'trim');
		$this->form_validation->set_rules('phone_number', 'Phone Number', 'trim');
		$this->form_validation->set_rules('email', 'Email', 'trim');
		$this->form_validation->set_rules('date_launched', 'Date Launched', 'trim');
		$this->form_validation->set_rules('tags', 'Tags', 'trim');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		$this->form_validation->set_rules('owner_id', 'Owner', 'trim|required');
		$this->form_validation->set_rules('category_id', 'Category', 'trim');
		$this->form_validation->set_rules('urls[homepage]', 'Homepage URL', 'trim');
		$this->form_validation->set_rules('urls[blog]', 'Blog URL', 'trim');
		$this->form_validation->set_rules('urls[rss]', 'Blog RSS URL', 'trim');
		$this->form_validation->set_rules('urls[twitter]', 'Twitter', 'trim');
		$this->form_validation->set_rules('urls[affiliate]', 'Affiliate URL', 'trim');
		
		if($this->form_validation->run() === true){
			$app_id = $this->input->post('app_id');
			
			if($this->app->update_app($app_id, $this->input->post()) === false){
				$this->data['notifications']['error'] = $this->app->errors;
			}
			else{
				//Update app tags
				$this->app->update_app_tags($app_id, $this->input->post('tags'));
				
				//Update app urls
				$this->app->update_app_urls($app_id, $this->input->post('urls'));
				
				//Upload Logo
				if($_FILES['logo']['size'] > 0){
					$image_config = array(
						'upload_path'	=> APP_IMAGE_TMP_DIR,
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
				
				//Clear app cache
				$this->load->driver('cache');
				if($this->cache->memcached->is_supported()){
					$this->cache->memcached->delete($this->app->get_app_cache_id($app_slug));
				}
				
				$this->session->set_flashdata('confirm', 'App updated.');
				redirect($this->uri->uri_string(), 'location');
			}
		}
		else{
			$this->data['notifications']['error'] = $this->form_validation->get_errors();
		}
		
		
		$this->data['users'] = $this->ion_auth->users()->result_array();
		
		$this->data['categories'] = $this->app->get_categories(0, true);
		
		$app = $this->app->get_app($app_slug);
		if(empty($app)){
			show_404();
		}
		$this->data['app'] = $app;
		
		$this->load->view('admin_app', $this->data);
	}
	
	function add_app(){
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('app');
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		$this->form_validation->set_rules('owner_id', 'Owner', 'trim|required');
		
		if($this->form_validation->run() === true){
			
			if($this->app->add_app($this->input->post()) === false){
				$this->data['notifications']['error'] = $this->app->errors;
			}
			else{
				$this->session->set_flashdata('confirm', 'App added.');
				redirect('/admin/app/' . $this->app->app_slug, 'location');
			}
		}
		else{
			$this->data['notifications']['error'] = $this->form_validation->get_errors();
		}
		
		$this->data['users'] = $this->ion_auth->users()->result_array();
		
		$this->load->view('admin_add_app', $this->data);
	}
	
	function generate_screenshot_queue(){
		$this->load->model('app');
		
		$app_id = $this->input->get('app_id');
		$homepage_url = $this->input->get('homepage_url');
		
		$screenshot_large_url = sprintf(SCREENSHOT_API_URL, SCREENSHOT_LARGE_WIDTH, SCREENSHOT_LARGE_HEIGHT, $homepage_url);
		@fopen($screenshot_large_url, 'r');
		
		$screenshot_small_url = sprintf(SCREENSHOT_API_URL, SCREENSHOT_SMALL_WIDTH, SCREENSHOT_SMALL_HEIGHT, $homepage_url);
		@fopen($screenshot_small_url, 'r');
		echo"done";
		exit();
	}
	
	function generate_screenshot(){
		$this->load->model('app');
		
		$app_id = $this->input->get('app_id');
		$app_slug = $this->input->get('app_slug');
		$homepage_url = $this->input->get('homepage_url');
		
		$screenshot_large_url = sprintf(SCREENSHOT_API_URL, SCREENSHOT_LARGE_WIDTH, SCREENSHOT_LARGE_HEIGHT, $homepage_url);
		if($this->app->add_app_image_from_url($app_id, 'screenshot_large', $screenshot_large_url) === false){
			$this->session->set_flashdata('error', 'Error adding large screenshot.');
			exit();
		}
		
		$screenshot_small_url = sprintf(SCREENSHOT_API_URL, SCREENSHOT_SMALL_WIDTH, SCREENSHOT_SMALL_HEIGHT, $homepage_url);
		if($this->app->add_app_image_from_url($app_id, 'screenshot_small', $screenshot_small_url) === false){
			$this->session->set_flashdata('error', 'Error adding small screenshot.');
			exit();
		}
		
		$this->session->set_flashdata('confirm', 'Screenshot generated.');
		
		redirect('/admin/app/' . $app_slug, 'location');
	}
	
	function company()
	{
		$company_permalink = $this->uri->segment(3);
		if(empty($company_permalink)) show_404();
		
		$this->data['company'] = $this->company->get_company($company_permalink);
		$this->data['company']['data'] = json_decode($this->data['company']['data'], true);
		
		//Combine competitors
		$this->data['company']['data']['competitors'] = array();
		foreach($this->data['company']['data']['competitions'] as $comp_id => $comp){
			$this->data['company']['data']['competitors'][] = "<a href='/admin/company/".$comp['competitor']['permalink']."' target='_blank'>".$comp['competitor']['name']."</a>";
		}
		
		//Acquisition
		if(!empty($this->data['company']['data']['acquisition'])){
			$this->data['company']['data']['acquired'] = "<a href='".$this->data['company']['data']['acquisition']['acquiring_company']['permalink']."'>".$this->data['company']['data']['acquisition']['acquiring_company']['name']."</a> - ".$this->data['company']['data']['acquisition']['source_description'];
		}
		else{
			$this->data['company']['data']['acquired'] = 'False';
		}
		
		//Office
		if(!empty($this->data['company']['data']['offices'])){
			$this->data['company']['data']['office'] = implode(" ",$this->data['company']['data']['offices'][0]);
		}
		else{
			$this->data['company']['data']['office'] = '';
		}
		
		//Screenshots
		$this->data['company']['screens'] = array();
		foreach($this->data['company']['data']['screenshots'] as $screen){
			$this->data['company']['screens'][] = "<img src='http://www.crunchbase.com/".$screen['available_sizes'][(count($this->data['company']['data']['image']) - 1)][1]."'/>";
		}
		
		//Products
		$products = $this->company->get_products(array('company_id'=>$this->data['company']['id']));
		$this->data['products'] = array();
		if(!empty($products)){
			foreach($products as $product){
				$this->data['products'][] = $product;
			}
		}
		
		$this->load->view('admin/includes/header.php');
		$this->load->view('admin/company',$this->data);
		$this->load->view('admin/includes/footer.php');
	}

	function companies()
	{
		$this->load->library('pagination');
		$config['offset'] = (!is_numeric($this->uri->segment($this->pagination->uri_segment))) ? 0 : $this->uri->segment($this->pagination->uri_segment);
		$config['base_url'] = '/admin/companies';
		$config['per_page'] = '20'; 
		$this->data['companies'] = $this->company->get_companies(array('status'=>'active', 'completed'=>'no'), array(), $config['offset'],$config['per_page']);
		$config['total_rows'] = $this->data['companies']['total_companies'];

		$this->pagination->initialize($config);
		$this->data['pagination_links'] = $this->pagination->create_links();
		
		$this->load->view('admin/includes/header.php');
		$this->load->view('admin/companies',$this->data);
		$this->load->view('admin/includes/footer.php');
		
	}
	
	function add_application(){
		if($_SERVER['REQUEST_METHOD'] != 'POST'){
			show_404();
		}
		
		$post_values = $this->input->post();
		$app_id = $this->company->add_app($post_values);
		
		redirect('/'.$post_values['redirect_url']);
	}

	function complete_company(){
		if($_SERVER['REQUEST_METHOD'] != 'POST'){
			show_404();
		}
		
		$post_values = $this->input->post();
		$this->company->complete_company($post_values['company_id']);
		
		redirect('/admin/companies');
	}
}
