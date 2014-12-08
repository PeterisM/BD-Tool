<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tool extends MY_Controller {
    
	public $colors = array('ffd000', 'cc3535', '3366CC', 'DC3912', 'FF9900', '109618', '990099', '0099C6', 'DD4477', '66AA00',
					'B82E2E', '316395', '994499', '22AA99', 'AAAA11', '6633CC', 'E67300', '8B0707', '651067', '329262',
					'5574A6', '3B3EAC', 'B77322', 'B91383', 'F4359E', 'ffd600', 'af19d2', 'c7658f', '3fa6ae','1939FF',
					'5ABBFF', '00EE21', '4EFFA5', 'ae91e0', 'c3e191', 'ccd815', '3b5998', '65c2c9', '65c89d', 'FF3F3F',
					'FF7F00', 'FF7AFF', 'FF60BD', 'FF4EA5', 'EE4977', '8B2A2A', 'AAEED8', '8CFFFF', '3FFF3F', 'FFFF5A');
	
	public function index()
    {
        $this->session->set_userdata("lang", 'lv');
        $viewdata = $this->ViewData();      	
		if ($viewdata["status"] != 1) 
		{
			redirect('/');
		}
		else
		{		
			$this->load->model('tool/chart_list_model');
			$viewdata['charts'] = $this->chart_list_model->get_user($viewdata['user_id']);
			$viewdata["page_title"] = 'Diagrammas';
			$this->load->view('tool/charts', $viewdata);
		}
	}
	
	public function logout()
	{
		$viewdata = $this->ViewData();
		//Dzēš datus no sesijas
		if($viewdata["status"] != -1)
		{
			$this->session->unset_userdata("logged");
			$this->session->unset_userdata("status");
			$this->session->unset_userdata("user_name");
			$this->session->unset_userdata("user_id");
		}
		redirect('/');
	}
	
	public function password()
	{
		$this->session->set_userdata("lang", 'lv');
        $viewdata = $this->ViewData();      	
		if ($viewdata["status"] != 1) 
		{
			redirect('/');
		}
		else
		{		
			if ($viewdata["user_id"] == 1) 
			{
				redirect('/');
			}
			else
			{
				$viewdata["page_title"] = "Paroles mainīšana";
				$this->load->view('tool/password', $viewdata);
			}
		}
	}
    
    public function check_pass( )
    {
        $viewdata = $this->ViewData();
        if ($viewdata["status"] != 1) 
		{
			redirect('/');
		}
		else
		{
			if ($viewdata["user_id"] == 1) 
			{
				redirect('tool');
			}
			else
			{
				if(!empty($_POST))
				{ 
					$this->form_validation->set_rules('currpassword', 'Pašreizējā parole', 'required');
					$this->form_validation->set_rules('password', 'Parole', 'required');
					$this->form_validation->set_rules('passconf', 'Parole vēlreiz', 'required|matches[password]');
					
					$this->load->model("tool/user_model");
					$results = $this->user_model->get_pass($viewdata["user_id"]);
					
					if ($this->form_validation->run() == FALSE) 
					{
						$currpassword = '';
						if (!form_error('currpassword') && sha1(sha1($this->input->post("currpassword")) . $results->salt) != $results->password)
						{   
							$currpassword = 'Jūs ievadījāt nepareizu pašreizējo paroli!';
						}
						else $currpassword = form_error('currpassword');
						$jdata = array(
							'currpassword' => $currpassword, 
							'password' => form_error('password'),
							'passconf' => form_error('passconf')
						);
					}
					elseif (sha1(sha1($this->input->post("currpassword")) . $results->salt) != $results->password ) 
					{
						$jdata = array('currpassword' => 'Jūs ievadījāt nepareizu pašreizējo paroli!');
					}
					//Ja jaunā parole sakrīt ar esošo paroli
					elseif ($this->input->post("currpassword") == $this->input->post("password"))
					{
						$jdata = array(
							'password' => 'Jaunajai parolei jāatšķiras no pašreizējās paroles!',
							'passconf' => 'Jaunajai parolei jāatšķiras no pašreizējās paroles!'
						);
					}
					//Ja jaunā parole atšķiras no vecās, tad mēģina to saglabāt datu bāzē ar jaunu 'salt' vērtību
					else 
					{				
						$data['salt'] = md5(uniqid(rand(), TRUE)); //Ģenerē jaunu salt vērtību
						$data['password'] =  sha1(sha1($this->input->post("password")) . $data['salt']);
						
						$this->user_model->update_password($viewdata["user_id"], $data);
						$jdata = array('message' => 'Jūsu parole veiksmīgi nomainīta!');
					}
					echo json_encode($jdata);
				}
				else redirect('tool/password');
			}
        }
    }
	
	public function view($id)
    {
        $id = intval($id);
		$this->session->set_userdata("lang", 'lv');
        $viewdata = $this->ViewData();
		$viewdata["follow"] = "NOINDEX,NOFOLLOW";	
		$this->load->model('tool/chart_model');
		$this->chart_model->update_visited($id);
		$viewdata['chart'] = $this->chart_model->get_data($id);
		$path = base_url() . 'data/' . $viewdata['chart']->data;
		$data = csvToGC($path);
		$viewdata["chart_data"] = $data[0];
		$viewdata["chart_header"] = $data[1];
				
		$viewdata["page_title"] = $viewdata['chart']->name;
		$this->load->view('tool/preview', $viewdata);
    }
	
	public function delete($id)
	{
		$id = intval($id);
		$viewdata = $this->ViewData();
		if(!empty($_POST))
		{
			if ($viewdata["status"] == -1) 
			{
				$jdata = array('redirect' => true);
			}
			else
			{
				$this->load->model("tool/chart_model");
				$chart = $this->chart_model->get_data($id);
				if ($chart->id != -1)
				{
					if ($chart->user_id != $viewdata["user_id"]) 
					{
						$jdata = array('redirect' => true);
					}
					else
					{	
						if($chart->id < 10000003)
						{
							$jdata = array('success' => true, 'message' => 'Parauga diagrammas dzēst aizliegts!');
						}
						else
						{
							unlink(realpath('data/' . $chart->data)); 
							if ($chart->img) 
							{
								unlink(realpath('./data/' . $viewdata["user"] . '/' . $chart->img)); 
							}
							$this->chart_model->delete($id);
							$jdata = array(
								'success' => true,
								'message' => 'Diagramma "' . $chart->name . '" ir veiksmīgi izdzēsta!',
								'id' => $chart->id
							);
							if($this->input->post("ajax") == 2)
							{
								$this->session->set_userdata("message", 'Diagramma "' . $chart->name . '" ir veiksmīgi izdzēsta!');
							}
						}
					}
				}
				else
				{
					if($this->input->post("ajax") == 2)
					{
						$this->session->set_userdata("message", 'Nav tāda diagramma!');
					}
					$jdata = array('message' => 'Nav tāda diagramma!');
				}
			}
			echo json_encode($jdata);
		}
		else redirect('/');
	}
	
	public function create()
    {
		$viewdata = $this->ViewData();
		if ($viewdata["status"] != 1) 
		{
			redirect('/');
		}
		else
		{		
			$this->session->set_userdata("lang", 'lv');
			$viewdata["page_title"] = 'Izveidošana';
			$viewdata['chart'] = new stdClass();
			$viewdata['chart']->id = 0;
			
			$this->form_validation->set_rules('name', 'Diagrammas nosaukums', 'trim|required|xss_clean');
			
			$config['upload_path'] = '././data/' . $viewdata["user"];
			$config['allowed_types'] = '*';
			$config['max_size'] = '2048';
			$config['remove_spaces'] = 'true';
			$this->load->library('upload', $config);

			if ($this->form_validation->run() == FALSE)
			{
				$this->load->view('tool/make', $viewdata);
			}
			elseif(!$_FILES || trim($_FILES['data']['name']) == '' )
			{
				$viewdata['error'] = 'Datne ir obligāta!';
				$this->load->view('tool/make', $viewdata);
			}
			elseif($_FILES && trim($_FILES['data']['name']) != '' && !$this->upload->do_upload('data'))
			{
				$viewdata['error'] = $this->upload->display_errors();
				$this->load->view('tool/make', $viewdata);
			}
			else
			{		
				$upload_data = $this->upload->data();
				$name = $this->input->post("name");
				$data = $viewdata["user"] . '/'. $upload_data['file_name'];
				$type = 'line';
				$img = '';
				
				$config_array['id'] = '10000000';
				$config_array['title'] = 'Chart Title';
				$config_array['ca_left'] = '60';
				$config_array['ca_top'] = '60';
				$config_array['ca_width'] = '70';
				$config_array['ca_height'] = '80';
				$config_array['titlePosition'] = 'out';
				$config_array['legend_position'] = 'right';
				$config_array['backgroundColor'] = 'ffffff';
				$config_array['focusTarget'] = 'datum';
				$config_array['vAxis_title'] = 'Y Title';
				$config_array['v_minValue'] = '0';
				$config_array['v_maxValue'] = '100';
				$config_array['vAxis_direction'] = 'left';
				$config_array['hAxis_title'] = 'X Title';
				$config_array['hAxis_direction'] = 'left';
				$config_array['h_slantedTextAngle'] = '1';
				$config_array['orientation'] = 'horizontal';
								
				//Sērijas
				$path = base_url() . 'data/' . $data;
				$csv = csvToGC($path);
				$chart_header = $csv[1];
				
				$series_count = count($chart_header) - 1;		
				for($i = 0; $i < $series_count; $i++) $config_array['series'][$i] = $this->colors[$i];
				
				//codes JSON friendly
				$config_temp = json_encode($config_array,JSON_FORCE_OBJECT);
				$config = json_encode($config_temp,JSON_FORCE_OBJECT);
				
				$status = 0;
				$this->load->model("tool/chart_model");
				$insert_id = $this->chart_model->add($viewdata["user_id"], $name, $config, $status, $data, $type, $img);
				$config_array['id'] = $insert_id;
				$config_temp = json_encode($config_array,JSON_FORCE_OBJECT);
				$update['config'] = json_encode($config_temp,JSON_FORCE_OBJECT);
								
				$this->chart_model->update($insert_id, $update);
				redirect('tool/edit/' . $insert_id);
			}
		}
    }
	
	public function update($id)
    {
		$id = intval($id);
		$viewdata = $this->ViewData();
		if ($viewdata["status"] != 1) 
		{
			redirect('/');
		}
		else
		{	
			$this->load->model('tool/chart_model');
			$viewdata['chart'] = $this->chart_model->get_data($id);
			if ($viewdata["chart"]->user_id != $viewdata["user_id"]) 
			{
				redirect('tool');
			}
			else
			{	
				$this->session->set_userdata("lang", 'lv');
				$viewdata["page_title"] = 'Datnes labošana';
				$viewdata['up_data'] = true;
				
				if($viewdata["chart"]->id < 10000003)
				{
					$this->session->set_userdata("message", 'Parauga diagrammau labot aizliegts!');
					$this->load->view('tool/make', $viewdata);
				}
				else
				{
					$this->form_validation->set_rules('name', 'Diagrammas nosaukums', 'trim|required|xss_clean');
					
					$config['upload_path'] = '././data/' . $viewdata["user"];
					$config['allowed_types'] = '*';
					$config['max_size'] = '2048';
					$config['remove_spaces'] = 'true';
					$this->load->library('upload', $config);

					if ($this->form_validation->run() == FALSE)
					{
						$this->load->view('tool/make', $viewdata);
					}
					elseif($_FILES && trim($_FILES['data']['name']) != '' && !$this->upload->do_upload('data'))
					{
						$viewdata['error'] = $this->upload->display_errors();
						$this->load->view('tool/make', $viewdata);
					}
					else
					{		
						$upload_data = $this->upload->data();
						if($upload_data['file_name'])
						{
							unlink(realpath('data/' . $viewdata['chart']->data)); 
							$update['data'] = $viewdata["user"] . '/'. $upload_data['file_name'];
							//pievienojam vai noņemam sērijas
							
							$config_array = json_decode($viewdata["chart"]->config);
							$config_array = json_decode($config_array, true);
							
							
							$path = base_url() . 'data/' . $update['data'];
							$csv = csvToGC($path);
							$chart_header = $csv[1];
							$new_series_count = count($chart_header) - 1;
							$old_series_count = count($config_array['series']);
							
							if($new_series_count > $old_series_count)
							{
								for($i = $old_series_count; $i < $new_series_count; $i++) $config_array['series'][$i] = $this->colors[$i];
							}
							else
							{
								for($i = $new_series_count; $i < $old_series_count; $i++) unset($config_array['series'][$i]);
							}
							
							//codes JSON friendly
							$config_temp = json_encode($config_array,JSON_FORCE_OBJECT);
							$update['config'] = json_encode($config_temp,JSON_FORCE_OBJECT);					
						}
						if($viewdata['chart']->img != '')
						{
							rename('data/' . $viewdata["user"] . '/'. $viewdata['chart']->img, 'data/' . $viewdata["user"] . '/' . $this->input->post("name") . '.png');
							$update['img'] = $this->input->post("name") . '.png';
						}
						$update['name'] = $this->input->post("name");
						$update['date_modified'] = NULL;
						$this->load->model("tool/chart_model");
						$insert_id = $this->chart_model->update($id, $update);
						redirect('tool/edit/' . $id);
					}
				}
			}
		}
    }
	
	public function edit($id)
    {
        $id = intval($id);
        $viewdata = $this->ViewData();
		if ($viewdata["status"] != 1) 
		{
			redirect('/');
		}
		else
		{		
			$this->load->model('tool/chart_model');
			$viewdata['chart'] = $this->chart_model->get_data($id);
			if ($viewdata["chart"]->user_id != $viewdata["user_id"]) 
			{
				redirect('tool');
			}
			else
			{	
				$path = base_url() . 'data/' . $viewdata['chart']->data;
				$data = csvToGC($path);
				$viewdata["chart_data"] = $data[0];
				$viewdata["chart_header"] = $data[1];
				
				//$viewdata["chart_config"] = json_decode($viewdata["chart"]->config);
				//$viewdata["chart_config_array"] = json_decode($viewdata["chart_config"], true);
				//$viewdata["chart_config_json"] = json_encode($viewdata["chart_config_array"],JSON_FORCE_OBJECT);
				//$viewdata["chart_config_json2"] = json_encode($viewdata["chart_config_json"],JSON_FORCE_OBJECT);

				$this->session->set_userdata("lang", 'lv');
				$this->lang->load('form_validation', 'latvian');
				$viewdata["page_title"] = $viewdata["chart"]->name . ' labošana';
				$this->load->view('tool/make', $viewdata);
			}
		}
    }
	
	public function save_data($id)
    {
		$id = intval($id);
		$viewdata = $this->ViewData();
		//if(!empty($_POST))
		//{
			if ($viewdata["status"] != 1) 
			{
				$jdata = array('redirect' => true);
			}
			else
			{	
				$this->load->model('tool/chart_model');
				$chart = $this->chart_model->get_data($id);
				if ($chart->user_id != $viewdata["user_id"]) 
				{
					$jdata = array('redirect' => true);
				}
				else
				{	
					if($chart->id < 10000003)
					{
						$this->session->set_userdata("message", 'Parauga diagrammas datus labot aizliegts!');
						$jdata = array('success' => true);
					}
					else
					{
						$file = './data/' . $chart->data;
						$complete = $this->input->post("data");
						$data = '';
						for($i = 0; $i < count($complete); $i++)
						{
							foreach ($complete[$i] as $key => $value) {
								$temp[$key][$i] = $value;
							}
						}
						
						for($i = 0; $i < count($temp); $i++)
						{
							$comma = 1;
							foreach ($temp[$i] as $key => $value) {
								$data .= $value;
								if($comma != count($temp[$i])) $data .= ',';
								$comma++;
							}
							if($i < count($temp) - 1) $data .= PHP_EOL;
						}
						
						$fp = fopen($file, "w");
						if ($fp) {
							fwrite($fp, $data); // Write information to the file
							fclose($fp); // Close the file
						}
						
						$this->session->set_userdata("message", 'Diagrammas "' . $chart->name . '" dati ir veiksmīgi saglabāti!');
						$jdata = array('success' => true);
					}
				}
			}
			echo json_encode($jdata);
		//}
		//else redirect('/');
    }	
	
	public function save($id)
    {
		$id = intval($id);
		$viewdata = $this->ViewData();
		//if(!empty($_POST))
		//{
			if ($viewdata["status"] != 1) 
			{
				$jdata = array('redirect' => true);
			}
			else
			{	
				$this->load->model('tool/chart_model');
				$chart = $this->chart_model->get_data($id);
				if ($chart->user_id != $viewdata["user_id"]) 
				{
					$jdata = array('redirect' => true);
				}
				else
				{	
					if($chart->id < 10000003)
					{
						$jdata = array('success' => true, 'message' => 'Parauga diagrammas uzstādījums labot aizliegts!');
					}
					else
					{
						$type = $this->input->post("type");

						$config_array['id'] = $id;
						$config_array['title'] = $this->input->post("title");
						$config_array['ca_left'] = $this->input->post("ca_left");
						$config_array['ca_top'] = $this->input->post("ca_top");
						$config_array['ca_width'] = $this->input->post("ca_width");
						$config_array['ca_height'] = $this->input->post("ca_height");
						$config_array['titlePosition'] = $this->input->post("titlePosition");
						$config_array['legend_position'] = $this->input->post("legend_position");
						$config_array['backgroundColor'] = $this->input->post("backgroundColor");
						$config_array['focusTarget'] = $this->input->post("focusTarget");
						
						switch ($chart->type) {
							case 'line': 
								$config_array['vAxis_title'] = $this->input->post("vAxis_title");
								$config_array['v_minValue'] = $this->input->post("v_minValue");
								$config_array['v_maxValue'] = $this->input->post("v_maxValue");
								$config_array['vAxis_direction'] = $this->input->post("vAxis_direction");
								$config_array['hAxis_title'] = $this->input->post("hAxis_title");
								$config_array['hAxis_direction'] = $this->input->post("hAxis_direction");
								$config_array['h_slantedTextAngle'] = $this->input->post("h_slantedTextAngle");
								$config_array['orientation'] = $this->input->post("orientation");
								break;
							case 'bar': 
								$config_array['vAxis_title'] = $this->input->post("vAxis_title");
								$config_array['v_minValue'] = $this->input->post("v_minValue");
								$config_array['v_maxValue'] = $this->input->post("v_maxValue");
								$config_array['vAxis_direction'] = $this->input->post("vAxis_direction");
								$config_array['hAxis_title'] = $this->input->post("hAxis_title");
								$config_array['hAxis_direction'] = $this->input->post("hAxis_direction");
								$config_array['h_slantedTextAngle'] = $this->input->post("h_slantedTextAngle");
								$config_array['orientation'] = $this->input->post("orientation");
								$config_array['groupWidth'] = $this->input->post("groupWidth");
								break;
							case 'area': 
								$config_array['vAxis_title'] = $this->input->post("vAxis_title");
								$config_array['v_minValue'] = $this->input->post("v_minValue");
								$config_array['v_maxValue'] = $this->input->post("v_maxValue");
								$config_array['vAxis_direction'] = $this->input->post("vAxis_direction");
								$config_array['hAxis_title'] = $this->input->post("hAxis_title");
								$config_array['hAxis_direction'] = $this->input->post("hAxis_direction");
								$config_array['h_slantedTextAngle'] = $this->input->post("h_slantedTextAngle");
								$config_array['orientation'] = $this->input->post("orientation");
								break;
						}
						//Sērijas
						$series_count = $this->input->post("series_count");		
						for($i = 0; $i < $series_count; $i++) $config_array['series'][$i] = $this->input->post($i . "_series");
						
						//Nokodē JSON friendly
						$config_temp = json_encode($config_array,JSON_FORCE_OBJECT);
						$config = json_encode($config_temp,JSON_FORCE_OBJECT);
						
						$update['config'] = $config;
						$update['type'] = $type;
						$update['width'] = $this->input->post("width");
						$update['height'] = $this->input->post("height");
						
						$this->load->model("tool/chart_model");
						$this->chart_model->update($id, $update);
						$jdata = array('success' => true, 'message' => 'Diagrammas "' . $chart->name . '" uzstādījumi ir veiksmīgi saglabāti!');
					}
				}
			}
			echo json_encode($jdata);
		//}
		//else redirect('/');
    }
	
	public function save_img($id)
    {
		$id = intval($id);
		$viewdata = $this->ViewData();
		//if(!empty($_POST))
		//{
			if ($viewdata["status"] != 1) 
			{
				$jdata = array('redirect' => true);
			}
			else
			{	
				$this->load->model('tool/chart_model');
				$chart = $this->chart_model->get_data($id);

				$img = $this->input->post("img");
				$img = str_replace('data:image/png;base64,', '', $img);
				$img = str_replace(' ', '+', $img);
				$data = base64_decode($img);
				$file = './data/' . $viewdata["user"] . '/' . $chart->name . '_temp.png';
				$success = file_put_contents($file, $data);
				
				$config['image_library'] = 'gd2';
				$config['source_image'] = $file;
				$config['new_image'] = './data/' . $viewdata["user"] . '/' . $chart->name . '.png';
				$config['maintain_ratio'] = false;
				$config['width'] = 220;
				$config['height'] = 220;
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();
				$this->image_lib->clear();
				
				unlink(realpath($file)); 

				$update['img'] = $chart->name . '.png';			
				$this->load->model("tool/chart_model");
				$insert_id = $this->chart_model->update($id, $update);
				$jdata = array('success' => true);
			}
			echo json_encode($jdata);
		//}
		//else redirect('/');
    }
	
	public function make_public($id)
	{
		$id = intval($id);
		$viewdata = $this->ViewData();
		//if(!empty($_POST))
		//{
			if ($viewdata["status"] == -1) {$jdata = array('redirect' => true);}
			else
			{
				$this->load->model("tool/chart_model");
				$chart = $this->chart_model->get_data($id);
				if ($chart->id != -1)
				{
					if ($chart->user_id != $viewdata["user_id"]) {$jdata = array('redirect' => true);}
					else
					{	
						$update['status'] = 1;
						$update['date_modified'] = NULL;
						$this->chart_model->update($id, $update);
						$jdata = array(
							'success' => true,
							'message' => 'Diagramma "' . $chart->name . '" ir veiksmīgi publiskota!'
						);
					}
				}
				else
				{
					$jdata = array('message' => 'Nav tāda diagramma!');
				}
			}
			echo json_encode($jdata);
		//}
		//else redirect('/');
	}
		
	public function un_public($id)
	{
		$id = intval($id);
		$viewdata = $this->ViewData();
		//if(!empty($_POST))
		//{
			if ($viewdata["status"] == -1) {$jdata = array('redirect' => true);}
			else
			{
				$this->load->model("tool/chart_model");
				$chart = $this->chart_model->get_data($id);
				if ($chart->id != -1)
				{
					if ($chart->user_id != $viewdata["user_id"]) {$jdata = array('redirect' => true);}
					else
					{	
						if($chart->id < 10000003)
						{
							$jdata = array('success' => true, 'message' => 'Parauga diagrammai jābūt publiskai!');
						}
						else
						{
							$update['status'] = 0;
							$update['date_modified'] = NULL;
							$this->chart_model->update($id, $update);
							$jdata = array(
								'success' => true,
								'message' => 'Diagramma "' . $chart->name . '" vairs nav publiska!'
							);
						}
					}
				}
				else
				{
					$jdata = array('message' => 'Nav tāda diagramma!');
				}
			}
			echo json_encode($jdata);
		//}
		//else redirect('/');
	}
}
