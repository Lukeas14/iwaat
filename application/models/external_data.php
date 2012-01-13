<?php

class External_data extends CI_Model{
	
	private $source = array('twitter');
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	public function get_app_import_queue($queue_size){
		$app_queue = array();

		$this->load->model('app');
		$app_results = $this->app->get_apps(
				array(), 
				array(), 
				0, 
				$queue_size,
				array(),
				'last_import ASC, id ASC'
		);
		
		foreach($app_results['apps'] as $app){
			$app['urls'] = $this->app->get_app_urls($app['id']);
			$app_queue[] = $app;
		}

		/*if(count($app_queue) < $queue_size){
			$queue_difference = $queue_size - count($app_queue);
			$sql = "
				SELECT apps.id, MAX(app_ext_data.time_added) AS last_time_added
				FROM apps
					JOIN app_ext_data ON app_ext_data.app_id = apps.id
				GROUP BY apps.id
				ORDER BY app_ext_data.time_added DESC
				LIMIT {$queue_difference}
			";
			foreach($query->result_array() as $row){
				$app_queue[] = $row['id'];
			}
		}*/

		return $app_queue;
	}
	
	public function set_external_data($app_id, $app_data){
		if(empty($app_id)) return false;
		
		foreach($app_data as $data){
			if(!empty($data['data_text'])){
				$data_table = 'app_external_media';
				$data_val = $data['data_text'];
			}
			elseif(!empty($data['data_numeric']) && is_numeric($data['data_numeric'])){
				$data_table = 'app_external_data';
				$data_val = $data['data_numeric'];
			}
			else{
				continue;
			}
			
			$this->db->set('data', $data_val);
			$this->db->set('time_added', 'NOW()', false);
			$this->db->where(array('app_id' => $app_id, 'type' => $data['type']), false);
			$this->db->update($data_table);
			
			if($this->db->affected_rows() < 1){
				$insert_data = array(
					'app_id' => $app_id,
					'type' => $data['type'],
					'data' => $data_val,
				);
				
				$this->db->set($insert_data);
				$this->db->set('time_added', 'NOW()', false);
				$this->db->insert($data_table);
			}
		}
	}

}