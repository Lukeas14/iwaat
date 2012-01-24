<?php

class App extends CI_Model{
	
	private $thumboo_api_key = '2c5814dc2150c7e84fdd00ab84d29160';
	
	public $errors = array();
	
	public $app_slug;

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function search_apps($query, $offset = 0, $limit = 10){
		$this->load->library('solr');
		
		$query = $this->solr->escape_query($query);
		
		$results = $this->solr->search($query);
		//echo"<pre>";print_r($results);print_r($results->response);print_r($results->response->docs);echo"</pre>";
		
		return $results;
	}
	
	function get_related_apps($app_id, $offset = 0, $limit = 10){
		$this->load->library('solr');
		
		if(empty($app_id) || !is_numeric($app_id)) return false;
		
		$results = $this->solr->morelikethis($app_id, $offset, $limit);
		
		return $results;
	}
	
	function add_app($data){
		$this->errors = array();
		
		$add_app = array();
		
		if(isset($data['name'])){
			$add_app['name'] = $data['name'];
		}
		if(isset($data['slug'])){
			$add_app['slug'] = $data['slug'];
		}
		else{
			$add_app['slug'] = $this->create_slug($add_app['name']);
		}
		$this->app_slug = $add_app['slug'];
		if(isset($data['tagline'])){
			$add_app['tagline'] = $data['tagline'];
		}
		if(isset($data['description'])){
			$add_app['description'] = $data['description'];
		}
		if(isset($data['phone_number'])){
			$add_app['phone_number'] = $data['phone_number'];
		}
		if(isset($data['email'])){
			$add_app['email'] = $data['email'];
		}
		if(isset($data['status'])){
			$add_app['status'] = $data['status'];
		}
		if(isset($data['date_launched'])){
			if($data['date_launched'] == ''){
				$this->db->set('date_launched', 'null', false);
			}
			else{
				$date_launched_unix = strtotime($data['date_launched']);
				if(empty($date_launched_unix) || $date_launched_unix < 500000000){
					$this->errors[] = 'Date Lanched format is not valid. (Ex. 2011-3-14)';
				}
				$add_app['date_launched'] = date('Y-m-d', $date_launched_unix);
			}
		}
		if(isset($data['owner_id'])){
			$add_app['owner_id'] = $data['owner_id'];
		}
		
		if(!empty($this->errors)){
			return false;
		}
		
		$this->db->set($add_app);
		
		$this->db->set('last_import', 'null', false);
		
		$this->db->insert('apps');
		
		$app_id = $this->db->insert_id();
		
		return $app_id;
		/*
		ini_set('display_errors', 1);
		
		$app_fields = array('company_id','name','tagline','description','phone_number','email','date_launched');
		$app_values = array();
		foreach($app_fields as $field){
			if(!empty($data[$field])) $app_values[$field] = $data[$field];
			else $app_values[$field] = '';
		}
		
		$app_values['phone_number'] = preg_replace('/[^\d]/', "", $app_values['phone_number']);
		
		$app_values['date_launched'] = date("Y-m-d", strtotime($app_values['date_launched']));
		
		$app_values['slug'] = $this->create_slug($app_values['name']);
		
		$this->db->insert('apps',$app_values);
		$app_id = $this->db->insert_id();
		
		//Add tags
		$tags = explode(',',trim($data['tags']));
		
		$tags = array_slice($tags, 0, 5);
		foreach($tags as $tag_name){
			$tag_name = trim($tag_name);
			
			$this->db->select('id');
			$this->db->where('name',$tag_name);
			$tag_query = $this->db->get('tags');
			if($tag_query->num_rows() > 0){
				$tag = $tag_query->row();
				$tag_id = $tag->id;
			}
			else{
				$this->db->insert('tags',array('name'=>$tag_name));
				$tag_id = $this->db->insert_id();
			}
			
			$this->db->insert('app_tags',array('app_id'=>$app_id, 'tag_id'=>$tag_id));			
		}
		
		//Add urls
		foreach($data['urls'] as $url_type => $url){
			if(empty($url)) continue;
			$this->db->insert('app_urls', array('app_id'=>$app_id, 'type'=>$url_type, 'url'=>$url));
		}
		
		//Add images
		if(!empty($data['logo'])){
			$this->add_app_image($app_id, 'logo', $data['logo']);
		}
		foreach($data['screenshot'] as $screenshot){
			if(!empty($screenshot)){
				$this->add_app_image($app_id, 'screenshot', $screenshot);
			}
		}
		
		return $app_id;*/
	}
	
	function add_app_image($app_id, $type, $url, $extension = false){
		if(@fopen($url,"r") === false) return false;
		
		$image_dir = '/var/www/iwaat.com/public_html/images/apps/'.substr($app_id, -2);
		if(!file_exists($image_dir)){
			if(!mkdir($image_dir, 0777, true)) return false;
		}
		
		if($extension === false){
			$image_extension = array_pop(explode(".",$url));
		}
		else{
			$image_extension = $extension;
		}
		$image_file_name = md5($url).'.'.$image_extension;
		if(!copy($url, $image_dir.'/'.$image_file_name)) return false;
				
		$this->db->insert('app_images', array('app_id'=>$app_id, 'type'=>$type, 'file_name'=>$image_file_name));
		$image_id = $this->db->insert_id();
		
		return $image_id;
	}
	
	function add_app_image_from_url($app_id, $type, $url){
		if(@fopen($url, "r") === false) return false;
		
		$image_dir = APP_IMAGE_DIR . '/' . $this->get_app_image_directory($app_id);
		if(!file_exists($image_dir)){
			if(!mkdir($image_dir, 0777, true)) return false;
		}
		$image_name = md5($app_id . $type . $url . time()) . '.jpg';
		$image_src = $image_dir . '/' . $image_name;
		
		if(!copy($url, $image_src)) return false;
		
		$this->db->where(array('app_id' => $app_id, 'type' => $type));
		$query = $this->db->get('app_images');
		if($query->num_rows() > 0){
			foreach($query->result_array() as $app_image){
				@unlink($image_dir . '/' . $app_image['file_name']);
			}
			$this->db->where(array('app_id' => $app_id, 'type' => $type));
			$this->db->delete('app_images');
		}
		
		$this->db->insert('app_images', array('app_id' => $app_id, 'type' => $type, 'file_name' => $image_name));
		
		return true;
	}
	
	function add_app_image_from_upload($app_id, $type, $data){
		$this->load->helper('file');
		
		if(@fopen($data['full_path'], "r") === false) return false;
		
		$image_dir = APP_IMAGE_DIR . '/' . $this->get_app_image_directory($app_id);
		if(!file_exists($image_dir)){
			if(!mkdir($image_dir, 0777, true)) return false;
		}
		$image_src = $image_dir . '/' . $data['file_name'];
		
		$image_config = array(
			'image_library'		=> 'gd2',
			'source_image'		=> $data['full_path'],
			'maintain_ratio'	=> true,
			'new_image'			=> $image_src
		);
		if($type == 'logo'){
			$image_config['height'] = 100;
			$image_config['width'] = 200;
		}
		elseif($type == 'screenshot'){
			$image_config['height'] = 1024;
			$image_config['width'] = 700;
		}
		else{
			return false;
		}
		$this->load->library('image_lib', $image_config);
		
		if(!$this->image_lib->resize()){
			return false;
		}
		
		$this->db->where(array('app_id' => $app_id, 'type' => $type));
		$query = $this->db->get('app_images');
		foreach($query->result_array() as $app_image){
			unlink($image_dir . '/' .$app_image['file_name']);
		}
		if($query->num_rows() > 0){
			$this->db->where(array('app_id' => $app_id, 'type' => $type));
			$this->db->delete('app_images');
		}
		
		$this->db->insert('app_images', array('app_id' => $app_id, 'type' => $type, 'file_name' => $data['file_name']));
		
		return true;
	}
	
	function get_app_image_directory($app_id){
		return substr($app_id, -2);
	}
	
	function create_slug($name){
		$slug = strtolower(trim(preg_replace(array('~[^0-9a-z]~i', '~-+~'), '-', preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($name, ENT_QUOTES, 'UTF-8'))), '-'));
		
		$this->db->select('id');
		$this->db->where('slug',$slug);
		$query = $this->db->get('apps');
		if($query->num_rows() > 0){
			$slug = $slug.'-'.mt_rand(1,100);
		}
		
		return $slug;
	}
	
	function get_screenshot($url){
		$_SERVER['HTTP_HOST'] = 'dev.iwaat.com';
		$_SERVER['REQUEST_URI'] = '/';
		$thumboo_api = "2c5814dc2150c7e84fdd00ab84d29160";
		$thumboo_url = $url;
		$thumboo_link = $url;
		$thumboo_params = "u=".urlencode("http://".$_SERVER["HTTP_HOST"].
		$_SERVER["REQUEST_URI"])."&su=".urlencode($thumboo_url)."&c=huge&api=".$thumboo_api.'&l='.urlencode($thumboo_link);
		//@readfile("http://counter.goingup.com/thumboo/snapshot.php?".$thumboo_params);
		//http://counter.goingup.com/thumboo/snapshot.php?u=http://www.iwaat.com&su=http://napster.com&c=huge&api=2c5814dc2150c7e84fdd00ab84d29160&l=http://www.napster.com
		//f781bf6ba8f6db4e11dc54583b5fd7c9
		//http://counter2.goingup.com/thumboo/image.php?i=f781bf6ba8f6db4e11dc54583b5fd7c9%7C%7C%7Cnapster.com%7C%7C%7C1024x768
		$thumboo_js = explode('"', file_get_contents("http://counter.goingup.com/thumboo/snapshot.php?".$thumboo_params));
		//print_r($thumboo_js);
		$thumboo_uid = $thumboo_js[(array_search('uid', $thumboo_js) + 2)];
		$thumboo_src = "http://counter2.goingup.com/thumboo/image.php?i=".$thumboo_uid.urlencode('|||').$url.urlencode('|||')."1024x768\n";
		echo $thumboo_src;
		return $thumboo_src;
	}
	
	function get_app_images($app_id){
		if(empty($app_id)) return false;
		$this->db->select('*');
		$this->db->where('app_id',$app_id);
		$query = $this->db->get('app_images');
		$app_images = array();
		foreach($query->result_array() as $image){
			$image['source'] = "/images/apps/".substr($app_id, -2)."/".$image['file_name'];
			$app_images[$image['type']][] = $image;
		}
		
		return $app_images;
	}
	
	function get_app_urls($app_id){
		if(empty($app_id)) return false;
		$this->db->select('*');
		$this->db->where('app_id',$app_id);
		$query = $this->db->get('app_urls');
		$app_urls = array();
		foreach($query->result_array() as $url){
			$app_urls[$url['type']] = $url['url'];
		}
		
		return $app_urls;
	}
	
	function get_app_media($app_id, $types = ''){
		if(empty($app_id)) return false;
		$this->db->select('*');
		$this->db->where('app_id',$app_id);
		$this->db->where_in('type', $types);
		$query = $this->db->get('app_external_media');
		$app_media = array();
		foreach($query->result_array() as $media){
			$app_media[$media['type']] = json_decode($media['data'], true);
		}
		
		return $app_media;
	}
	
	function get_app_tags($app_id){
		if(empty($app_id)) return false;
		$this->db->select('tags.id, tags.name');
		$this->db->join('tags', 'tags.id = app_tags.tag_id', 'left');
		$this->db->where('app_tags.app_id', $app_id);
		$query = $this->db->get('app_tags');
		$app_tags = array();
		foreach($query->result_array() as $app_tag){
			$app_tags[$app_tag['id']] = $app_tag['name'];
		}
				
		return $app_tags;
	}
	
	function get_app_cache_id($app_slug){
		return 'app_' . $app_slug;
	}
	
	function get_app($id){
		if(empty($id)) return false;
		
		if(is_numeric($id)){
			$this->db->where('id',$id);
		}
		else{
			$this->db->where('slug',$id);
		}
		
		$this->db->select('*');
		$query = $this->db->get('apps');
		
		if($query->num_rows <= 0) return false;
		
		$app = $query->row_array();
		
		//Get app images
		$app['images'] = $this->get_app_images($app['id']);
		
		//Get app_urls
		$app['urls'] = $this->get_app_urls($app['id']);
		
		//Get app_tags
		$app['tags'] = $this->get_app_tags($app['id']);
		
		//Get app media
		$app['media'] = $this->get_app_media($app['id'], array('blog_rss_feed','twitter_user_timeline', 'twitter_url_mentions', 'twitter_user_data'));
		$app['media']['twitter_feed'] = array(); 
		foreach($app['media'] as $media_type => &$media_type_items){
			if(!empty($media_type_items)){
				foreach($media_type_items as $media_item_index => &$media_item){
					switch($media_type){
						case'blog_rss_feed':
							$media_item['relative_datetime'] = get_relative_time($media_item['datetime']);
							break;

						case 'twitter_user_timeline':
							$media_item['relative_datetime'] = get_relative_time($media_item['created_at']);
							$media_item['profile_image_url'] = $app['media']['twitter_user_data']['profile_image_url'];
							$media_item['from_user'] = $app['media']['twitter_user_data']['screen_name'];
							$app['media']['twitter_feed'][] = $media_item;
							break;
						/*case 'twitter_url_mentions':
							$media_item['relative_datetime'] = get_relative_time($media_item['created_at']);*/
					}
				}
			}
		}
		
		//Get related apps
		$related_apps = $this->get_related_apps($app['id'], 0, 6);
		$app['related_apps'] = (!empty($related_apps->response->docs)) ? $related_apps->response->docs : array();
		
		return $app;
	}
	
	function get_apps($conditions = array(), $joins = array(), $offset = 0, $limit = 10, $having = array(), $order_by = ''){
		$this->db->select("SQL_CALC_FOUND_ROWS apps.*", false);  

		if(!empty($conditions)) {  
			$this->db->where($conditions, NULL);  
		}  
		
		if(!empty($joins)){
			foreach($joins as $join_table => $join){
				$this->db->join($join_table, $join['condition'], $join['type']);
				$this->db->select($join['select']);
			}
		}
		
		$this->db->group_by('apps.id');
		
		if(!empty($having)){
			$this->db->having($having);
		}
		
		if(!empty($order_by)){
			$this->db->order_by($order_by);
		}

		$query = $this->db->get('apps', $limit, $offset);  

		if($query->num_rows() > 0) {
			// let's see how many rows would have been returned without the limit  
			$count_query = $this->db->query('SELECT FOUND_ROWS() AS row_count');  
			$found_rows = $count_query->row();  

			// load all of the returned results into a single array ($rows).  
			// this is handy if you need to execute other SQL statements or bring  
			// in additional model data that might be useful to have in this array.  
			// alternatively, you could return $query object if you prefer that.  
			$rows = array();  
			foreach($query->result_array() as $row) {  

				// to build on my comment above about returning an array instead of  
				// the raw $query object, as an example, this would be a good spot  
				// to retrieve the comment count for each entry and append that to  
				// the current row before we push the row data into the $rows array.  
				//$row->comment_count = $this->_comment_count($row->entry_id);  

				array_push($rows, $row);  
			}  

			// after the foreach loop above, we should now have all of the combined  
			// entry data in a single array. let's return a two-element array: the  
			// first element contains the result set in array form, and the second  
			// element is the number of rows in the full result set without the limit  
			return array('apps' => $rows, 'total_apps' => (int) $found_rows->row_count);  
		} else {  
			return FALSE;  
		}  
	}
	
	function get_categories($parent_category = 0, $active = true){
		if(is_numeric($parent_category)){
			$this->db->where('parent_id', $parent_category);
		}
		if($active === true){
			$this->db->where('status', 'active');
		}
		
		$this->db->order_by('name');
		
		$query = $this->db->get('categories');
		
		return $query->result_array();
	}
	
	function get_homepage_apps($categories, $app_count = 5){
		$apps = array();
		foreach($categories as $category){
			$apps[$category['id']] = $this->get_apps(array('apps.status' => 'active', 'apps.category_id' => $category['id'], 'app_images.type' => 'logo'), array('app_images' => array('select' => 'app_images.file_name as logo', 'condition' => 'app_images.app_id = apps.id', 'type' => 'left')), 0, $app_count, array(), 'popularity_index');
		}
		
		return $apps;
	}
	
	function update_app_tags($app_id, $tags){
		if(!is_array($tags)){
			$tags = explode(",", $tags);
		}
		
		$tags = array_slice($tags, 0, 5);
		
		$tag_ids = array();
		$insert_tags = array();
		foreach($tags as $tag_name){
			$tag_name = trim($tag_name);
			if(empty($tag_name)) continue;
			
			$this->db->select('id');
			$this->db->where('name',$tag_name);
			$tag_query = $this->db->get('tags');
			if($tag_query->num_rows() > 0){
				$tag = $tag_query->row();
				$tag_id = $tag->id;
			}
			else{
				$this->db->insert('tags',array('name'=>$tag_name));
				$tag_id = $this->db->insert_id();
			}
			
			$tag_ids[] = $tag_id;
			$insert_tags[] = "($app_id, $tag_id)";
		}
		
		if(!empty($insert_tags)){
			$this->db->query("INSERT IGNORE INTO app_tags (app_id, tag_id) VALUES " . implode(",", $insert_tags));
		}
		
		$this->db->where('app_id', $app_id);
		if(!empty($tag_ids)){
			$this->db->where_not_in('tag_id', $tag_ids);
		}
		$this->db->delete('app_tags');
	}
	
	function update_app_urls($app_id, $urls){
		foreach($urls as $url_type => $url_val){
			if(in_array($url_type, array('homepage','blog','rss','affiliate'))){
				//$url = mysql_real_escape_string(preg_replace('~^(?:f|ht)tps?://~i','', $url_val));
				$url = mysql_real_escape_string($url_val);
			}
			elseif($url_type == 'twitter'){
				$url = mysql_real_escape_string($url_val);
			}
			else continue;
			
			if(!empty($url)){
				$this->db->query("INSERT INTO app_urls (app_id, type, url) VALUES (" . $app_id . ", '" . $url_type . "', '" . $url . "') ON DUPLICATE KEY UPDATE url = '" . $url . "'");
			}
			else{
				$this->db->where(array('app_id' => $app_id, 'type' => $url_type));
				$this->db->delete('app_urls');
			}
		}
	}
	
	function update_app($app_id, $data, $escape_values = true){
		if(!is_numeric($app_id) || !is_array($data)) return false;
		
		$this->errors = array();
		
		$update_app = array();
		
		if(isset($data['name'])){
			$update_app['name'] = $data['name'];
		}
		if(isset($data['slug'])){
			$update_app['slug'] = $data['slug'];
		}
		if(isset($data['tagline'])){
			$update_app['tagline'] = $data['tagline'];
		}
		if(isset($data['description'])){
			$update_app['description'] = $data['description'];
		}
		if(isset($data['phone_number'])){
			$update_app['phone_number'] = $data['phone_number'];
		}
		if(isset($data['email'])){
			$update_app['email'] = $data['email'];
		}
		if(isset($data['popularity_index'])){
			if(!is_numeric($data['popularity_index'])){
				$this->errors[] = 'Popularity Index must be a number.';
			}
			else{
				$update_app['popularity_index'] = $data['popularity_index'];
			}
		}
		if(isset($data['status'])){
			$update_app['status'] = $data['status'];
		}
		if(isset($data['date_launched'])){
			if($data['date_launched'] == ''){
				$this->db->set('date_launched', 'null', false);
			}
			else{
				$date_launched_unix = strtotime($data['date_launched']);
				if(empty($date_launched_unix) || $date_launched_unix < 500000000){
					$this->errors[] = 'Date Lanched format is not valid. (Ex. 2011-3-14)';
				}
				$update_app['date_launched'] = date('Y-m-d', $date_launched_unix);
			}
		}
		if(isset($data['owner_id'])){
			$update_app['owner_id'] = $data['owner_id'];
		}
		if(isset($data['category_id']) && is_numeric($data['category_id'])){
			$update_app['category_id'] = $data['category_id'];
		}
		if(isset($data['last_import']))
		{
			if($data['last_import'] == 'NOW()'){
				$this->db->set('last_import', 'NOW()', false);
			}
			else{
				$update_app['last_import'] = date('Y-m-d H:i:s', strtotime($data['last_import']));
			}
		}
		
		if(!empty($this->errors)){
			return false;
		}
		
		foreach($update_app as $update_app_field => $update_app_val){
			$this->db->set($update_app_field, $update_app_val);
		}
		$this->db->where('id', $app_id);
		$this->db->update('apps');
		
		return $this->db->affected_rows();
	}
	
	function set_app_url($app_id, $type, $url){
		if(empty($app_id)) return false;
		
		$url = mysql_real_escape_string($url);
		$sql = "
			INSERT INTO app_urls
			(app_id, type, url)
			VALUES ({$app_id}, '{$type}', '{$url}')
			ON DUPLICATE KEY UPDATE url = '{$url}'
		";
		$this->db->query($sql);
	}
}