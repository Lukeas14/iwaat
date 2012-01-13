<?php

class Traction_index extends CI_Model{
	
	private $fields = array('facebook_share_count', 'seomoz_authority', 'twitter_url_count', 'twitter_url_mentions', 'twitter_followers');
	private $field_aggrs;

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		
		$this->field_aggrs = $this->get_field_aggrs();
	}
	
	private function get_field_aggrs(){
		$this->db->select('type, MIN(data) as min, MAX(data) as max', false);
		$this->db->where_in('type', $this->fields);
		$this->db->group_by('type');
		$aggr_results = $this->db->get('app_external_data');
		$type_aggr = array();
		foreach($aggr_results->result_array() as $result){
			$type_aggr[$result['type']] = $result;
		}
		
		return $type_aggr;
	}
	
	function get_traction_index($app_id){
		if(empty($this->field_aggrs)){
			$this->field_aggrs = $this->get_field_aggrs();
		}
		
		$sub_indices = array();
		
		$this->db->select('type, data, time_added');
		$this->db->where('time_added >', '(CURDATE() - INTERVAL 1 MONTH)', false);
		$this->db->where('app_id', $app_id);
		$this->db->where_in('type', $this->fields);
		$this->db->group_by('type');
		$results = $this->db->get('app_external_data');
		
		foreach($results->result_array() as $app_field){
			if(empty($app_field['data']) || !is_numeric($app_field['data'])) continue;
			
			$sub_indices[$app_field['type']] = (log($app_field['data']) - log($this->field_aggrs[$app_field['type']]['min'])) / (log($this->field_aggrs[$app_field['type']]['max']) - log($this->field_aggrs[$app_field['type']]['min']));
		}
		
		if(count($sub_indices) == 0){
			return false;
		}
		
		$traction_value = pow(array_product($sub_indices), (1 / count($sub_indices)));
		
		$traction_index = round($traction_value * 100);
		
		return $traction_index;
	}
	
}