<?php

class MY_Controller extends CI_Controller {
	
    public function __construct() 
	{
		parent::__construct();
		$this->load->helper('html');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('date');
		$this->load->helper('email');
		$this->load->helper('text');
		$this->load->helper('language');
		$this->load->helper('tool');
		$this->load->library("session");
		$this->load->library('form_validation');
    }

    protected function ViewData()
	{
		$user_name = '';
        $user_id = 0; 
		$status = -1; 
		$message = false; 
		$up_data = false; 
		$homepage = false;
		$chart_data = false;
		$this->session->userdata("lang") == false ? $lang = 'lv' : $lang = $this->session->userdata("lang");
		if ($this->session->userdata("logged") == false) 
		{
			$auth = false;
		} 
		else 
		{
			$auth = true;
			$user_id = $this->session->userdata("user_id");
			$user_name = $this->session->userdata("user_name");
			$status = $this->session->userdata("status");
			$message = $this->session->userdata("message");
			$this->session->unset_userdata("message");
		}

		$ip = 'nav';
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
          $ip=$_SERVER['REMOTE_ADDR'];
        }
		return array(
			"user" => $user_name, 
			"user_id" => $user_id, 
			"status" => $status, 
			"message" => $message, 
			"homepage" => $homepage,
			"logged" => $auth,
			"lang" => $lang,
			"up_data" => $up_data,
			"chart_data" => $chart_data,
			"ip" => $ip,
			"key_words" => "NAV ATSLĒGAS VĀRDI", 
			"description" => "NAV APRAKSTS", 
			"page_title" => "NAV LAPAS NOSAUKUMS",
			"follow" => "NOINDEX,NOFOLLOW"); 
	}
}
?>