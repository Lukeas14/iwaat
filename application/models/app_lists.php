<?php

class App_lists extends CI_Model{
	
	public $errors = array();

	function __construct(){
		// Call the Model constructor
		parent::__construct();
	}

	public function add_app_list(){

	}

	public function update_app_list(){

	}

	public function get_app_lists($options = array()){
		$default_options = array(
			'select' 	=> 'app_lists.*',
			'offset'	=> 0,
			'limit'		=> 10
		);
		$options = array_merge($default_options, $options);

		$this->db->select("SQL_CALC_FOUND_ROWS app_lists.*", false);

		if(!empty($options['select'])){
			$this->db->select($options['select'], false);
		}

		if(!empty($options['owner_id']) && is_numeric($options['owner_id'])){
			$this->db->where('owner_id', $options['owner_id']);
		}

		if(empty($options['offset']) || !is_numeric($options['offset'])){
			$options['offset'] = 0;
		}

		if(empty($options['limit']) || !is_numeric($options['limit'])){
			$options['limit'] = 10;
		}

		$query = $this->db->get('app_lists', $options['limit'], $options['offset']);

		$app_lists = array('app_lists' => array(), 'total_app_lists' => $query->num_rows());

		if($app_lists['total_app_lists'] > 0){
			$count_query = $this->db->query('SELECT FOUND_ROWS() AS row_count');
			$found_rows = $count_query->row();

			foreach($query->result_array() as $row){
				$app_lists['app_lists'][] = $row;
			}

			return $app_lists;
		}
		else{
			return $app_lists;
		}
	}
	
}