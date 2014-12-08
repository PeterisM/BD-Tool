<?php

class user_model extends CI_Model {
	
	public function __construct() 
	{
		parent::__construct();
		$this->load->database();
    }

    public function authorize($name, $password)
	{
		$query = $this->db->get_where('user', array('name' => $name));
		$items = $query->result();
		if(sizeof($items) == 1)
		{
			$item = $items[0];
			if(sha1($password . $item->salt) == $item->password) 
			{
				return $item;
			}
			else return false;
			
		}
		else
		{
			return false;
		}
    }
	
	public function get_data($id)
	{
		$this->db->select("user.id, user.name, user.email");
		$this->db->from("user");
		$this->db->where('user.id', $id);
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
		$this->db->update('user', $data);
	}
	
	public function get_pass($id)
	{
		$this->db->select("user.salt, user.password");
		$this->db->from("user");
		$this->db->where('user.id', $id);
		$items = $this->db->get()->result();
		if ($items == NULL)
		{
			$item = 0;
		}
		else 
		{
			$item = $items[0];
		}
		return $item; 
	}
	
	public function update_password($id, $data) 
	{
		if($id != 1)
		{
			$this->db->where('id', $id);
			$this->db->update('user', $data);
		}
	}
	
	public function get_email($email)
	{
		$this->db->select("user.id, user.name, user.email");
		$this->db->from("user");
		$this->db->where('user.email', $email);
		$items = $this->db->get()->result();
		if ($items == NULL) return false;
		else return true;
	}
	
	public function get_name($name)
	{
		$this->db->select("user.id, user.name, user.email");
		$this->db->from("user");
		$this->db->where('user.name', $name);
		$items = $this->db->get()->result();
		if ($items == NULL) return false;
		else return true;
	}
	
	public function register($name, $email, $password)
	{
		$salt = md5(uniqid(rand(), TRUE));

		$hashed_passwod = sha1(sha1($password) . $salt); 

		$insert = array(
			'name' => $name,
			'email' => $email,
			'password' => $hashed_passwod,
			'salt' => $salt,
			'status' => 1
		);
		$this->db->insert('user', $insert); 


        
		//Sagatavo e-pasta saturu
		//$subject = 'Reģistrācija XXX sistēmā';
		$text = "Sveiki, " . $name . "!
Jūsu reģistrācija XXX bijusi veiksmīga!";
		//Sūta e-pastu
		//return $this->send_email($email, $subject, $text);
	}
}
?>