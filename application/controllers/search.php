<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends MY_Controller {
	
	const RESULTS_PER_PAGE = 10;
	
	private $data = array();
	
	public function index(){
		$this->load->model('app');
		$this->load->library('pagination');
		
		$keywords = $this->input->get('q');
		$this->data['keywords'] = $keywords;
		
		$this->data['page'] = ($this->input->get('page')) ? $this->input->get('page') : 1;
		
		$search_apps_params = array(
			'offset'	=> self::RESULTS_PER_PAGE * ($this->data['page'] - 1),
			'limit'		=> self::RESULTS_PER_PAGE,
		);
		$app_results = $this->app->search_apps($keywords, $search_apps_params);
		$this->data['app_total'] = $app_results->response->numFound;
		
		//echo"<pre>";print_r($app_results->response->docs);echo"</pre>";
		foreach($app_results->response->docs as $app_index => &$app){
			//Set Logo
			if(empty($app->logo)){
				$app->logo = '/images/apps/68/e6e21d348008762a8cfac38e0c3d31f8.png';
			}
			
			//Set Screenshot
			if(empty($app->screenshots)){
				$app->screenshot = '/images/apps/68/fa4d5a4b411cde04e3836f8ccea469dc.jpg';
			}
			elseif(is_array($app->screenshots)){
				$app->screenshot = $app->screenshots[0];
			}
			else{
				$app->screenshot = $app->screenshots;
			}
		}
		$this->data['app_results'] = $app_results;
		
		$pagination_config = array(
			'base_url'				=> '/search?q=' . $this->input->get('q'),
			'per_page'				=> self::RESULTS_PER_PAGE,
			'num_links'				=> 3,
			'page_query_string'		=> true,
			'enable_query_string'	=> true,
			'use_page_numbers'		=> true,
			'query_string_segment'	=> 'page',
			'first_link'			=> 'First',
			'last_link'				=> 'Last',
			'next_link'				=> 'Next',
			'prev_link'				=> 'Previous',
			'total_rows'			=> $this->data['app_total']
		);
		$this->pagination->initialize($pagination_config);
		$this->data['pagination_links'] = $this->pagination->create_links();
		if(empty($this->data['pagination_links'])){
			$this->data['pagination_links'] = 1;
		}
		
		//echo"<pre>";print_r($companies);echo"</pre>";
	
		$this->load->view('search',$this->data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */