<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller 
{

	public function index()
	{
		$this->loadHome(array("page" => "Hello"), array());
	}

	public function getstarted()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules("firstname", "Name", "trim|required");
		$this->form_validation->set_rules("email", "Email Address", "trim|required|valid_email");
		//$this->form_validation->set_rules("password", "Password", "trim|required");

		$head_data = array("page" => "Hello");
		$data = array("form_errors" => FALSE,
					  "duplicate" => FALSE);

		if ($this->form_validation->run() == FALSE)
		{
			$data["form_errors"] = TRUE;
			$this->loadHome($head_data, $data);
		}
		else
		{
			$this->load->model('User');

			$alreadyExists = $this->User->fetch($this->input->post("email"));

			if ($alreadyExists)
			{
				if ($this->User->public)
				{
					redirect("/calendar");
				}
				else
				{
					$data["duplicate"] = TRUE;
					$this->loadHome($head_data, $data);
				}
			}
			else
			{
				$this->load->helper("globals");
				$password = randomPassword(10);

				$result = $this->User->add($this->input->post("firstname"), $this->input->post("email"), $password);

				redirect("/calendar");
			}			
		}
	}

	private function loadHome($head_data, $body_data)
	{
		$this->load->view("head", $head_data);
		$this->load->view("nav", array("show_login" => TRUE));
		$this->load->view("home", $body_data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */