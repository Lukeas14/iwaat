<?php

class Category extends CI_Model {

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
				LEFT JOIN companies ON companies.company_id = company_descriptions.company_id
			WHERE 
				MATCH (company_descriptions.description) AGAINST (?)
			ORDER BY score DESC
			LIMIT ?,?
		";
		$query = $this->db->query($sql, array($keywords,$keywords, $offset, $limit));
		$companies = array();
		foreach($query->result_array() as $row){
			$companies[] = $row;
		}
		
		return $companies;
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
	
	function get_companies($conditions = array(), $offset = 0, $limit = 10){
		$this->db->select("SQL_CALC_FOUND_ROWS *", false);  

		if(!empty($conditions)) {  
			$this->db->where($conditions, NULL);  
		}  

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
	
}