<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Root extends MY_Controller {
    
    public function index()
    {
        $this->session->set_userdata("lang", 'lv');
        $this->lang->load('form_validation', 'latvian');
        $viewdata = $this->ViewData();
		$viewdata["key_words"] = 'charts';
		$viewdata["description"] = 'data visualisation';
		$viewdata["follow"] = "NOINDEX,NOFOLLOW";
		$viewdata["page_title"] = 'Datu vizuāla attēlošana tīmekļa vietnēs';
		$viewdata["homepage"] = true;
		
		if ($viewdata["status"] == 1) 
		{
			redirect('tool');
		}
		else
		{		
			$this->load->view('root/home', $viewdata);
		}
    }
    
	public function login()
    {
        $this->session->set_userdata("lang", 'lv');
        $this->lang->load('form_validation', 'latvian');
        $viewdata = $this->ViewData();
		$viewdata["key_words"] = 'charts';
		$viewdata["description"] = 'data visualisation';
		$viewdata["follow"] = "NOINDEX,NOFOLLOW";
		$viewdata["page_title"] = 'Pieteikties';
					
		if ($viewdata["status"] == 1) 
		{
			redirect('tool');
		}
		else
		{		
			$this->load->view('root/login', $viewdata);
		}
    }
	
	public function charts()
    {
        $this->session->set_userdata("lang", 'lv');
        $this->lang->load('form_validation', 'latvian');
        $viewdata = $this->ViewData();
		$viewdata["key_words"] = 'charts';
		$viewdata["description"] = 'data visualisation';
		$viewdata["follow"] = "NOINDEX,NOFOLLOW";
		$viewdata["page_title"] = 'Publiskās diagrammas';
					
		if ($viewdata["status"] == 1) 
		{
			redirect('tool');
		}
		else
		{	
			$this->load->model('tool/chart_list_model');
			$viewdata['charts'] = $this->chart_list_model->get_public();
			$this->load->view('root/public', $viewdata);
		}
    }
	
	public function chart($id)
    {
        $id = intval($id);
		$this->session->set_userdata("lang", 'lv');
        $this->lang->load('form_validation', 'latvian');
        $viewdata = $this->ViewData();
		$viewdata["key_words"] = 'charts';
		$viewdata["description"] = 'data visualisation';
		$viewdata["follow"] = "NOINDEX,NOFOLLOW";
					
		if ($viewdata["status"] == 1) 
		{
			redirect('tool');
		}
		else
		{	
			$this->load->model('tool/chart_model');
			$viewdata['chart'] = $this->chart_model->get_data($id);
			if ($viewdata["chart"]->status != 1) 
			{
				redirect('charts');
			}
			else
			{	
				$viewdata["page_title"] = $viewdata['chart']->name;
				$this->load->view('root/public_view', $viewdata);
			}
		}
    }

    public function signup()
	{
		$this->session->set_userdata("lang", 'lv');
        $this->lang->load('form_validation', 'latvian');
        $viewdata = $this->ViewData();
		$viewdata["key_words"] = 'charts';
		$viewdata["description"] = 'data visualisation';
		$viewdata["follow"] = "NOINDEX,NOFOLLOW";
		$viewdata["page_title"] = 'Reģistrācija';
					
		if ($viewdata["status"] == 1) 
		{
			redirect('tool');
		}
		else
		{		
			$this->load->view('root/register', $viewdata);
		}
	}
	
	public function authorize()
    {
		if(!empty($_POST))
		{
			$viewdata = $this->ViewData();
			if ($viewdata["status"] == 1) 
			{
				$jdata = array('success' => true);
			}
			else
			{		
				$this->form_validation->set_rules('user_name', 'Lietotājvārds', 'trim|required|xss_clean');
				$this->form_validation->set_rules('password', 'Parole', 'required');
				
				if ($this->form_validation->run() == FALSE) 
				{
					$jdata = array(
						'user_name' => form_error('user_name'), 
						'password' => form_error('password')
					);
				}
				else 
				{
					$name = $this->input->post("user_name");
					$password = $this->input->post("password");
					$this->load->model("tool/user_model");
					$results = $this->user_model->authorize($name, $password);
					
					if ($results == FALSE) 
					{
						$jdata = array('error' => 'Lietotājvārds un/vai parole ir nepareiza!');
					} 
					else 
					{
						$jdata = array('success' => true);
						$this->session->set_userdata("status", $results->status);
						$this->session->set_userdata("user_name", $results->name);
						$this->session->set_userdata("user_id", $results->id);
						$this->session->set_userdata("logged", true);
					}
				}
			}
			echo json_encode($jdata);
		}
		else redirect('login');
    }
	
	public function register()
    {
		if(!empty($_POST))
		{
			$viewdata = $this->ViewData();
			if ($viewdata["status"] == 1) 
			{
				$jdata = array('success' => true);
			}
			else
			{	
				$this->form_validation->set_rules('name', 'Lietotājvārds', 'trim|required|min_length[3]|max_length[30]|alpha_numeric|xss_clean');
				$this->form_validation->set_rules('email', 'E-pasta adrese', 'trim|required|max_length[255]|valid_email|xss_clean');
				$this->form_validation->set_rules('password', 'Parole', 'required|min_length[6]');
				$this->form_validation->set_rules('passconf', 'Parole vēlreiz', 'required|matches[password]');
				
				if ($this->form_validation->run() == FALSE) 
				{
					$jdata = array(
						'name' => form_error('name'), 
						'email' => form_error('email'), 
						'password' => form_error('password'),
						'passconf' => form_error('passconf')
					);
				}
				else 
				{
					$name = $this->input->post("name");
					$email = $this->input->post("email");
					$password = $this->input->post("password");
					$this->load->model("tool/user_model");
					$results1 = $this->user_model->get_email($email);
					$results2 = $this->user_model->get_name($name);
					if ($results1 == true && $results2 == true) 
					{
						$jdata = array('error' => 'Lietotājvārds un e-pasts jau ir izmantots!');
					} 
					elseif ($results1 == true) 
					{
						$jdata = array('error' => 'E-pasts jau ir izmantots!');
					} 
					elseif ($results2 == true) 
					{
						$jdata = array('error' => 'Lietotājvārds jau ir izmantots!');
					} 
					else 
					{
						$this->user_model->register($name, $email, $password);
						$results = $this->user_model->authorize($name, sha1($password));
						$jdata = array('success' => true);
						$structure = FCPATH . "/data/" . $name;
						if (!mkdir($structure, 0755, true)) {die('Failed to create folders...');}
						$this->session->set_userdata("status", $results->status);
						$this->session->set_userdata("user_name", $results->name);
						$this->session->set_userdata("user_id", $results->id);
						$this->session->set_userdata("logged", true);
						$this->session->set_userdata("message", 'Reģistrācija veiksmīga');
					}
				}
			}
			echo json_encode($jdata);
		}
		else redirect('signup');
    }
}