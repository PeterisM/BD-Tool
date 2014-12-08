<?php

class chart_list_model extends CI_Model {

	public function __construct() {
	parent::__construct();
	$this->load->database();
    }   

    public function get_user($id) 
	{
        $this->db->select('charts.id, charts.user_id, charts.name, charts.config, charts.img, charts.status, 
        charts.date_created, charts.date_modified, charts.visited');
		$this->db->from('charts');
		$this->db->where('charts.user_id', $id);
		$this->db->order_by('charts.date_modified', 'desc'); 
		$results = $this->db->get()->result();
		return $results; 
    }          

    public function get_public() 
	{
        $this->db->select('charts.id, charts.user_id, charts.name, charts.config, charts.img, charts.status, 
        charts.date_created, charts.date_modified, charts.visited, user.name as user_name');
		$this->db->from('charts');
        $this->db->join('user', 'charts.user_id = user.id');
		$this->db->where('charts.status', 1);
		$this->db->order_by('charts.date_modified', 'desc'); 
		$results = $this->db->get()->result();
		return $results; 
    }        
}

?>