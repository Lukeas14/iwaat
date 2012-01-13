<?php

/*
SELECT COUNT(company_tags.tag_id) as total, tags.tag_name
FROM company_tags 
LEFT JOIN tags ON company_tags.tag_id = tags.tag_id
GROUP BY company_tags.tag_id
ORDER BY total DESC
 */

class Company extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function search_companies($keywords, $offset = 0, $limit = 10){
		$sql = "
			SELECT 
				companies . * , company_descriptions.description, 
				MATCH (company_descriptions.description) AGAINST (?) AS score
			FROM company_descriptions
				LEFT JOIN companies ON companies.id = company_descriptions.company_id
			WHERE 
				MATCH (company_descriptions.description) AGAINST (?)
			ORDER BY score DESC
			LIMIT ?,?
		";
		$query = $this->db->query($sql, array($keywords, $keywords, $offset, $limit));
		$companies = array();
		foreach($query->result_array() as $row){
			$companies[] = $row;
		}
		
		return $companies;
	}
	
	function add_app($data){
		/*echo"<pre>";
		print_r($data);
		echo"</pre>";*/
		ini_set('display_errors', 1);
		
		$app_fields = array('company_id','name','tagline','description','phone_number','email','date_launched');
		$app_values = array();
		foreach($app_fields as $field){
			if(!empty($data[$field])) $app_values[$field] = $data[$field];
			else $app_values[$field] = '';
		}
		
		$app_values['phone_number'] = preg_replace('/[^\d]/', "", $app_values['phone_number']);
		
		$app_values['date_launched'] = date("Y-m-d", strtotime($app_values['date_launched']));
		
		$this->db->insert('apps',$app_values);
		$app_id = $this->db->insert_id();
		
		//Add tags
		$tags = explode(',',trim($data['tags']));
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
		
		return $app_id;
	}
	
	function add_app_image($app_id, $type, $url){
		if(@fopen($url,"r") === false) return false;
		
		$image_dir = '/var/www/iwaat.com/public_html/images/apps/'.substr($app_id, -2);
		if(!file_exists($image_dir)){
			if(!mkdir($image_dir, 0777, true)) return false;
		}
		
		$image_extension = array_pop(explode(".",$url));
		$image_file_name = md5($url).'.'.$image_extension;
		if(!copy($url, $image_dir.'/'.$image_file_name)) return false;
				
		$this->db->insert('app_images', array('app_id'=>$app_id, 'type'=>$type, 'file_name'=>$image_file_name));
		$image_id = $this->db->insert_id();
		
		return $image_id;
	}
	
	function complete_company($company_id){
		if(empty($company_id)) return false;
		
		$this->db->where('id',$company_id);
		$this->db->update('companies',array('completed'=>'yes'));
		
		return true;
	}
	
	function get_product($id){
		if(empty($id)) return false;
		elseif(is_numeric($id)){
			$this->db->where(array('id'=>$id), null);
		}
		else{
			$this->db->where(array('permalink'=>$id), null);
		}
		
		$query = $this->db->get('products',1,0);
		if($query->num_rows() > 0){
			$product = $query->row_array();
			return $product;
		}
		else{
			return false;
		}
	}
	
	function get_products($conditions = array(), $offset = 0, $limit = 10){
		$this->db->select("SQL_CALC_FOUND_ROWS *", false);  

		if(!empty($conditions)) {  
			$this->db->where($conditions, NULL);  
		}  
		

		$query = $this->db->get('products', $limit, $offset);  
		//echo $this->db->last_query();

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
			return array('products' => $rows, 'total_products' => (int) $found_rows->row_count);  
		} else {  
			return FALSE;  
		}  
	}
	
	function get_company($id){
		if(empty($id)) return false;
		elseif(is_numeric($id)){
			$this->db->where(array('id'=>$id), null);
		}
		else{
			$this->db->where(array('permalink'=>$id), null);
		}
		
		$query = $this->db->get('companies',1,0);
		if($query->num_rows() > 0){
			$company = $query->row_array();
			return $company;
		}
		else{
			return false;
		}
	}
	
	function get_companies($conditions = array(), $joins = array(), $offset = 0, $limit = 10){
		$this->db->select("SQL_CALC_FOUND_ROWS companies.*", false);  

		if(!empty($conditions)) {  
			$this->db->where($conditions, NULL);  
		}  
		
		if(!empty($joins)){
			foreach($joins as $join_table => $join){
				$this->db->join($join_table, $join['condition'], $join['type']);
				$this->db->select($join['select']);
			}
		}

		$this->db->order_by('popularity_index DESC');
		//$this->db->order_by('RAND()');
		
		$query = $this->db->get('companies', $limit, $offset);  

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
			return array('companies' => $rows, 'total_companies' => (int) $found_rows->row_count);  
		} else {  
			return FALSE;  
		}  
	}
	
	function update_company($company_id, $data){
		if(!is_numeric($company_id) || !is_array($data)) return false;
		
		$this->db->where('id', $company_id);
		$this->db->update('companies',$data);
		
		return $this->db->affected_rows();
	}
	
}