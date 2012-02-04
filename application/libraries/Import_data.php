<?php

class Import_data{
	
	private $CI; //Codeigniter instance
	
	private $sources = array('homepage', 'seomoz', 'twitter', 'facebook', 'blog_rss','compete');
	//private $sources = array('compete');
	
	const TWITTER_COUNT_URL = 'http://urls.api.twitter.com/1/urls/count.json?url=%s';
	const TWITTER_SEARCH_URL = 'http://search.twitter.com/search.json?q=%s';
	const TWITTER_USER_URL = 'http://api.twitter.com/1/users/lookup.json?screen_name=%s';
	const TWITTER_USER_TIMELINE_URL = 'http://api.twitter.com/1/statuses/user_timeline.json?screen_name=%s&trim_user=1';
	
	const FACEBOOK_SHARE_COUNT_URL = 'http://graph.facebook.com/?id=%s';
	
	const SEOMOZ_AUTHORITY_URL = 'http://lsapi.seomoz.com/linkscape/url-metrics/%s?AccessID=%s&Expires=%s&Signature=%s';
	const SEOMOZ_ACCESS_ID = 'member-33f124b667';
	const SEOMOZ_SECRET_KEY = 'd3a75efeac98bff3fdee353c90d6aaf3';
	
	const COMPETE_API_KEY = '2e391886a1969cb33b19a34d2aaab2cf';
	const COMPETE_UV_URL = 'http://apps.compete.com/sites/%s/trended/uv/?apikey=%s&latest=1';

	function __construct(){
		$this->CI =& get_instance();
		
		$this->CI->load->library('curl');

		error_reporting(E_ALL ^ E_DEPRECATED);
		
		/*$this->CI->load->library('seostats/src/seostats');
		
		if(!empty($params['url'])){
			$this->set_url($params['url']);
		}*/
	}
	
	public function import_external_data($app){
		$app_external_data = array();
		
		$app['homepage_url'] = $this->set_url($app['urls']['homepage']);
		
		foreach($this->sources as $source){
			$import_method = "import_{$source}_data";
			$app_external_data = array_merge($app_external_data, $this->{$import_method}($app));
		}
		
		return $app_external_data;
	}
	
	public function set_url($url){
		$user_agent = array(CURLOPT_USERAGENT=>'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');
		$homepage_response = $this->CI->curl->simple_get($url, array(), $user_agent);
		$response_info = $this->CI->curl->info;
		if($response_info['http_code'] != 200){
			return false;
		}
		
		return $response_info['url'];
	}
	
	private function import_blog_rss_data($app){
		$blog_data = array();
		
		if(empty($app['urls']['rss'])) return $blog_data;
		
		$app['blog_rss_url'] = $this->set_url($app['urls']['rss']);
		
		if($app['blog_rss_url'] === false) return $blog_data;
		
		$this->CI->load->library('simplepie');
		
		try{
			$this->CI->simplepie->set_feed_url($app['blog_rss_url']);
			$this->CI->simplepie->enable_cache(false);
			$this->CI->simplepie->init();
			$this->CI->simplepie->handle_content_type();
		}
		catch(Exception $e){
			echo"done";
			return $blog_data;
		}
		
		$blog_rss_feed = array();
		
		foreach($this->CI->simplepie->get_items() as $feed_item){
			$feed_data = array(
				'title' => $feed_item->get_title(),
				'permalink' => $feed_item->get_permalink(),
				'description' => strip_tags($feed_item->get_description()),
				'datetime' => $feed_item->get_date('Y-m-d H:i:s')
			);
			$blog_rss_feed[] = $feed_data;
		}
		
		$this->CI->simplepie->__destruct();
		
		if(!empty($blog_rss_feed)){
			$blog_data[] = array(
				'type' => 'blog_rss_feed',
				'data_text' => json_encode($blog_rss_feed)
			);
		}
		
		return $blog_data;
	}
	
	private function import_homepage_data($app){
		$homepage_data = array();
		
		if(empty($app['homepage_url'])) return $homepage_data;
		
		$this->CI->load->library('simple_html_dom');
		$homepage_dom = file_get_html($app['homepage_url']);
		
		if($homepage_dom !== false){
		
			//Get plaintext
			$homepage_text = $homepage_dom->plaintext;
			$homepage_data[] = array(
				'type' => 'homepage_text',
				'data_text' => $this->remove_whitespace($homepage_text)
			);

			//Get title
			$homepage_title = $homepage_dom->find('head title', 0);
			if(!empty($homepage_title)){
				$homepage_data[] = array(
					'type' => 'homepage_title',
					'data_text' => $this->remove_whitespace($homepage_title->innertext)
				);
			}

			$homepage_meta_keywords = $homepage_dom->find('head meta[name=keywords]', 0);
				if(!empty($homepage_meta_keywords)){
					$homepage_data[] = array(
					'type' => 'homepage_meta_keywords',
					'data_text' => $this->remove_whitespace($homepage_meta_keywords->content)
				);
			}

			$homepage_meta_description = $homepage_dom->find('head meta[name=description]', 0);
			if(!empty($homepage_meta_description)){
				$homepage_data[] = array(
					'type' => 'homepage_meta_description',
					'data_text' => $this->remove_whitespace($homepage_meta_description->content)
				);
			}
		}
		
		return $homepage_data;
	}
	
	private function import_seomoz_data($app){
		$seomoz_data = array();
		
		if(!empty($app['homepage_url'])){
			$homepage_url = parse_url($app['homepage_url']);
			$expires = time() + 300;
			$signature = urlencode(base64_encode(hash_hmac('sha1', self::SEOMOZ_ACCESS_ID."\n".$expires, self::SEOMOZ_SECRET_KEY, true)));
			$seomoz_authority_url = sprintf(self::SEOMOZ_AUTHORITY_URL, urlencode($homepage_url['host']), self::SEOMOZ_ACCESS_ID, $expires, $signature);
			$json_response = $this->CI->curl->simple_get($seomoz_authority_url);
			$response = json_decode($json_response, true);
			if(!empty($response['upa'])){
				$seomoz_data[] = array(
					'type' => 'seomoz_authority',
					'data_numeric' => round($response['upa'])
				);
			}
		}
		
		return $seomoz_data;
	}
	
	private function import_googleplus_data($app){
		
	}
	
	private function import_twitter_data($app){
		$twitter_data = array();
		
		if(!empty($app['homepage_url'])){
			//Get twitter url counts
			$twitter_count_url = sprintf(self::TWITTER_COUNT_URL, $app['homepage_url']);
			$json_response = $this->CI->curl->simple_get($twitter_count_url);
			$response = json_decode($json_response, true);
			if(!empty($response['count']) && is_numeric($response['count'])){
				$twitter_data[] = array(
					'type' => 'twitter_url_count',
					'data_numeric' => $response['count']
				);
			}

			//Get twitter mentions
			$twitter_search_url = sprintf(self::TWITTER_SEARCH_URL, $app['homepage_url']);
			$json_response = $this->CI->curl->simple_get($twitter_search_url);
			$response = json_decode($json_response, true);
			if(!empty($response) && count($response['results']) > 0){
				$twitter_data[] = array(
					'type' => 'twitter_url_mentions',
					'data_text' => json_encode($response['results'])
				);
			}
		}
		
		if(!empty($app['urls']['twitter'])){
			$twitter_screen_name = $app['urls']['twitter'];
			
			//Get twitter user data
			$twitter_user_url = sprintf(self::TWITTER_USER_URL, $twitter_screen_name);
			$json_response = $this->CI->curl->simple_get($twitter_user_url);
			$response = json_decode($json_response, true);
			if(!empty($response[0])){
				$twitter_data[] = array(
					'type' => 'twitter_followers',
					'data_numeric' => $response[0]['followers_count']
				);
				$twitter_data[] = array(
					'type' => 'twitter_user_data',
					'data_text' => json_encode($response[0])
				);
			}
			
			//Get twitter user tweets
			$twitter_user_timeline_url = sprintf(self::TWITTER_USER_TIMELINE_URL, $twitter_screen_name);
			$json_response = $this->CI->curl->simple_get($twitter_user_timeline_url);
			$response = json_decode($json_response, true);
			if(!empty($response)){
				$twitter_data[] = array(
					'type' => 'twitter_user_timeline',
					'data_text' => json_encode($response)
				);
			}
		}
		
		return $twitter_data;
	}
	
	private function import_facebook_data($app){
		$facebook_data = array();
		
		if(!empty($app['homepage_url'])){
			$facebook_share_count_url = sprintf(self::FACEBOOK_SHARE_COUNT_URL, $app['homepage_url']);
			$json_response = $this->CI->curl->simple_get($facebook_share_count_url);
			$response = json_decode($json_response, true);
			if(!empty($response['shares'])){
				$facebook_data[] = array(
					'type' => 'facebook_share_count',
					'data_numeric' => $response['shares']
				);
			}
		}
		
		return $facebook_data;
	}
	
	private function import_compete_data($app){
		$compete_data = array();
		
		if(!empty($app['homepage_url'])){
			$homepage_url = preg_replace('~^(?:f|ht)tps?://~i','', $app['homepage_url']);
			$compete_uv_url = sprintf(self::COMPETE_UV_URL, $homepage_url, self::COMPETE_API_KEY);
			$json_response = $this->CI->curl->simple_get($compete_uv_url);
			$response = json_decode($json_response, true);
			if($response['status'] == 'OK' && !empty($response['data']['trends']['uv'][0]['value']) && $response['data']['trends']['uv'][0]['value'] > 0){
				$compete_data[] = array(
					'type' => 'compete_unique_visitors',
					'data_numeric' => $response['data']['trends']['uv'][0]['value']
				);
			}
		}
		
		return $compete_data;
	}
	
	private function remove_whitespace($str){
		return preg_replace('/\s+/', ' ', strip_tags(html_entity_decode(str_replace(array("\n","\t"), " ", $str))));
	}
	
	private function format_url($url){
		$url = preg_replace('~^www.~', '', preg_replace('~^http://~', '', $url));
		
		$url = "http://www.".$url;
		
		return $url;
		
	}
	
	function get_seomoz_domainauthority(){
		return $this->get_data('Seomoz_Domainauthority_Array');
	}
	
	function get_data($seo_stats_method){
		try{
			$data = $this->CI->seostats->{$seo_stats_method}();
		}
		catch(Exception $e){
			//print_r($e);
		}
		
		return $data;
	}
	
}