<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends MY_Controller {
	
	private $data = array();
	
	public function index(){
		$this->load->model('app');
		
		$keywords = $this->input->get('q');
		$this->data['keywords'] = $keywords;
		
		$app_results = $this->app->search_apps($keywords);
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
		
		//echo"<pre>";print_r($companies);echo"</pre>";
	
		$this->load->view('search',$this->data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */