<?php //defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends MY_Controller {

	private $data = array();
	
	function __construct()
	{
		parent::__construct();
				
		//Restrict remote access
		if(!isset($_SERVER['SHELL']) || $_SERVER['SHELL'] != '/bin/bash'){
			//show_404();
		}
	}
	
	function index(){
		$this->load->model('app');
		$apps = $this->app->get_apps(array('apps.id'=>2), array('app_urls'=>array('select'=>'url AS homepage_url','condition'=>"apps.id = app_urls.app_id AND app_urls.type = 'homepage' ",'type'=>'left')));
		foreach($apps['apps'] as $app){
			if(empty($app['homepage_url'])) continue;
			$screenshot = $this->app->get_screenshot($app['homepage_url']);
			if(empty($screenshot)) continue;
			$this->app->add_app_image($app['id'],'screenshot_large',$screenshot, 'jpg');
			echo $app['id']."\n";
		}
		//print_r($apps);
		//echo $this->app->get_screenshot('http://www.setlist.fm');
	}
	
	function get_screenshots(){
		$stw_large_base_url = "http://images.shrinktheweb.com/xino.php?stwembed=1&stwaccesskeyid=ca6b061948c9f8b&stwxmax=1024&stwymax=500&stwurl=";
		$stw_small_base_url = "http://images.shrinktheweb.com/xino.php?stwembed=1&stwaccesskeyid=ca6b061948c9f8b&stwxmax=400&stwymax=300&stwurl=";
		
		$this->load->model('app');
		$apps = $this->app->get_apps(array(), array(), 700, 300);
		
		for($i = 0; $i < 2; $i ++){
			foreach($apps['apps'] as $app){
				echo $app['id']."-".$app['name']."\n";
				$app['urls'] = $this->app->get_app_urls($app['id']);
				if(empty($app['urls']['homepage'])) continue;

				$stw_large_url = $stw_large_base_url . $app['urls']['homepage'];
				$stw_small_url = $stw_small_base_url . $app['urls']['homepage'];

				$this->app->add_app_image($app['id'], 'screenshot_large', $stw_large_url, 'jpg');
				$this->app->add_app_image($app['id'], 'screenshot_small', $stw_small_url, 'jpg');
			}
		}
		
		
		
		return;
		/*
		foreach($apps['apps'] as $app){
			if(empty($app['homepage_url'])) continue;
			echo $app['name']." - ".$app['screenshot_count']."\n";
			
			$img_src = "/var/www/iwaat.com/public_html/images/screenshots/".$app['slug']."_large.png";
			//take large screenshot
			$shell_cmd = "python /usr/local/bin/pywebshot/pywebshot.py -d 5 -t 1024x769 -s 1024x769 -f {$img_src} {$app['homepage_url']}";
			$shell_output = shell_exec($shell_cmd);
			$this->app->add_app_image($app['id'], 'screenshot_large', $img_src, 'png');
			
			//take small screenshot
			$img_src = "/var/www/iwaat.com/public_html/images/screenshots/".$app['slug']."_small.png";
			$shell_cmd = "python /usr/local/bin/pywebshot/pywebshot.py -d 5 -t 360x300 -f {$img_src} {$app['homepage_url']}";
			$shell_output = shell_exec($shell_cmd);
			$this->app->add_app_image($app['id'], 'screenshot_small', $img_src, 'png');
			//echo $shell_output;
		}
		 * 
		 */
	}
	
	/*
	function get_screenshots(){
		$this->load->model('app');
		$apps = $this->app->get_apps('', array(
			'app_urls'=>array('select'=>'url AS homepage_url','condition'=>"apps.id = app_urls.app_id AND app_urls.type = 'homepage'",'type'=>'left'),
			'app_images'=>array('select'=>'COUNT(app_images.id) AS screenshot_count','condition'=>"apps.id = app_images.app_id AND (app_images.type = 'screenshot_small' OR app_images.type = 'screenshot_large')", 'type'=>'left')
		), 0, 30, 'COUNT(app_images.id) = 0');
		
		foreach($apps['apps'] as $app){
			if(empty($app['homepage_url'])) continue;
			echo $app['name']." - ".$app['screenshot_count']."\n";
			
			$img_src = "/var/www/iwaat.com/public_html/images/screenshots/".$app['slug']."_large.png";
			//take large screenshot
			$shell_cmd = "python /usr/local/bin/pywebshot/pywebshot.py -d 5 -t 1024x769 -s 1024x769 -f {$img_src} {$app['homepage_url']}";
			$shell_output = shell_exec($shell_cmd);
			$this->app->add_app_image($app['id'], 'screenshot_large', $img_src, 'png');
			
			//take small screenshot
			$img_src = "/var/www/iwaat.com/public_html/images/screenshots/".$app['slug']."_small.png";
			$shell_cmd = "python /usr/local/bin/pywebshot/pywebshot.py -d 5 -t 360x300 -f {$img_src} {$app['homepage_url']}";
			$shell_output = shell_exec($shell_cmd);
			$this->app->add_app_image($app['id'], 'screenshot_small', $img_src, 'png');
			//echo $shell_output;
		}
	}
	*/

	function create_slugs(){
		$this->load->model('app');
		$apps = $this->app->get_apps(array('slug'=>''),array(),0,2000);
		foreach($apps['apps'] as $app){
			$slug = $this->app->create_slug($app['name']);
			$this->app->update_app($app['id'], array('slug'=>$slug));
			echo $app['name'].":".$slug."\n";
		}
		//echo"<pre>";print_r($apps);echo"</pre>";
	}
	
	function import_external_data_old()
	{
		$this->load->model('app');
		$this->load->model('company');
		
		$this->load->library('external_data',array());
		
		$companies = $this->company->get_companies("companies.popularity_index IS NULL AND status != 'deadpool' AND homepage_url != ''",array(),0,20000);
		foreach($companies['companies'] as $company){
			echo $company['id'] . ' - ' .$company['name']."\n";
			if(!$this->external_data->set_url($company['homepage_url'])){
				echo"-Not a valid URL\n";
				$this->company->update_company($company['id'], array('popularity_index'=>0));
				continue;
			}
			
			$domain_authority = $this->external_data->get_seomoz_domainauthority();
			$this->company->update_company($company['id'], array('popularity_index'=>$domain_authority['Domain Authority']));
			
			usleep(500000);
		}
		//print_r($apps);
		
	}
	
	function import_external_data(){
		$this->load->model('app');
		$this->load->library('import_data');
		$this->load->model('external_data');
		$this->load->model('traction_index');
		
		$app_import_queue = $this->external_data->get_app_import_queue(100);
		
		$cron_start_time = time();
		
		foreach($app_import_queue as $app){
			$app_start_time = time();
		
			echo $app['id']." - ".$app['name']."\n";
			
			$app_external_data = $this->import_data->import_external_data($app);
			$this->external_data->set_external_data($app['id'], $app_external_data);
			$this->app->update_app($app['id'], array('last_import' => 'NOW()'), false);
			
			//Ensure that at least 10 seconds have expired in between app data imports
			$time_expired = time() - $app_start_time;
			if($time_expired <= 11){
				sleep(11 - $time_expired);
			}
			
			//Ensure that this script is done before the next cronjob starts
			$time_expired = time() - $cron_start_time;
			if($time_expired >= 60*55){
				exit();
			}
		}
		
		//Update traction index
		$apps = $this->app->get_apps(array(), array(), 0, 100000);
		foreach($apps['apps'] as $app){
			$traction_index = $this->traction_index->get_traction_index($app['id']);
			if(!is_numeric($traction_index)) $traction_index = 0;
			$this->app->update_app($app['id'], array('popularity_index' => $traction_index));
			
			
				
			echo $app['name']." - ".$traction_index."\n";
		}
		
		//Clear app cache
		$this->load->driver('cache');
		if($this->cache->memcached->is_supported()){
			$this->cache->memcached->clean();
		}
	}
	
	function get_homepage_url(){
		$this->load->model('app');
		$this->load->library('import_data');
		
		$apps = $this->app->get_apps(array(), array(), 0, 10000);
		foreach($apps['apps'] as $app){
			$app['urls'] = $this->app->get_app_urls($app['id']);
			if(empty($app['urls']['homepage'])) continue;
			
			$homepage_redirect_url = $this->import_data->set_url($app['urls']['homepage']);
			
			if(!empty($homepage_redirect_url)){
				$this->app->set_app_url($app['id'], 'homepage_redirect', $homepage_redirect_url);
			}
			echo $app['name']." - ".$homepage_redirect_url."\n";
		}
	}
	
	function update_traction_index(){
		$this->load->model('app');
		$this->load->model('traction_index');
		
		$apps = $this->app->get_apps(array(), array(), 0, 100000);
		foreach($apps['apps'] as $app){
			$traction_index = $this->traction_index->get_traction_index($app['id']);
			if(!is_numeric($traction_index)) $traction_index = 0;
			$this->app->update_app($app['id'], array('popularity_index' => $traction_index));
			echo $app['name']." - ".$traction_index."\n";
		}
	}
}
