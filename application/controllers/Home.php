<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->loadHome(array());
	}

	public function getstarted()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules("firstname", "Name", "trim|required");
		$this->form_validation->set_rules("email", "Email Address", "trim|required|valid_email");
		//$this->form_validation->set_rules("password", "Password", "trim|required");

		$data = array("form_errors" => FALSE,
					  "duplicate" => FALSE);

		if ($this->form_validation->run() == FALSE)
		{
			$data["form_errors"] = TRUE;
			$this->loadHome($data);
		}
		else
		{
			$this->load->model('User');

			$alreadyExists = $this->User->fetch($this->input->post("email"));

			if ($alreadyExists)
			{
				if ($this->User->public)
				{
					$this->LoginUser($this->User);
				}
				else
				{
					$data["duplicate"] = TRUE;
					$this->loadHome($data);
				}
			}
			else
			{
				$this->load->helper("globals");
				$password = randomPassword(10);

				$result = $this->User->add($this->input->post("firstname"), $this->input->post("email"), $password);

				$this->LoginUser($this->User);
			}			
		}
	}

	private function loadHome($body_data)
	{
		$this->LoadHead("Hello");
		$this->LoadNav(TRUE);
		$this->load->view("home", $body_data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */