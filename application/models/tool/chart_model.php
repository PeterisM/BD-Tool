<?php

class chart_model extends CI_Model {

	
	public function __construct() 
	{
		parent::__construct();
		$this->load->database();
    }

    public function add($user_id, $name, $config, $status, $data, $type, $img)
	{
		$insert = array(
			'user_id' => $user_id,
			'name' => $name,
			'config' => $config,
			'status' => $status,
			'data' => $data,
			'img' => $img,
			'type' => $type,
			'date_created' => NULL,
			'date_modified' => NULL
		);
		$this->db->insert('charts', $insert);
		return $this->db->insert_id();
	}
	
	public function get_data($id)
	{
		$this->db->select('charts.id, charts.user_id, charts.name, charts.config, charts.data, charts.img, charts.type, 
		charts.width, charts.height, charts.status, charts.visited, user.name as user_name');
		$this->db->from('charts');
		$this->db->join('user', 'charts.user_id = user.id');
		$this->db->where('charts.id', $id);
		$items = $this->db->get()->result();
		if ($items == NULL) 
		{
			$item->id = -1;
		}
		else 
		{
			$item = $items[0];
		}
		return $item; 
	}
	
	public function update($id, $data) 
	{
		$this->db->where('id', $id);
		$this->db->update('charts', $data);
	}
	
	public function update_visited($id) 
	{
		$this->db->set('visited', 'visited+1', FALSE);
		$this->db->set('date_modified', 'date_modified', FALSE);
		$this->db->where('id', $id);
		$this->db->update('charts');
	}
    
	public function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('charts');
	}
}

?>