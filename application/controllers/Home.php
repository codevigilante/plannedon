<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller 
{
	public function index()
	{
		print_r("Blood tunnel");
		$this->load->view('home');
	}

	public function getstarted()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules("firstname", "Name", "trim|required");
		$this->form_validation->set_rules("email", "Email Address", "trim|required|valid_email");
		$this->form_validation->set_rules("password", "Password", "trim|required");

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('home');
		}
		else
		{
			$this->load->model('User');

			$user_data = new UserData($this->input->post("firstname"), $this->input->post("email"));

			$result = $this->User->add($user_data, $this->input->post("password"));

			/*if ($result["success"] === FALSE)
			{
				$this->load->view("home", $result);
			}
			else
			{
				redirect("/calendar");
			}*/

			//$this->load->view('calendar', $result);
		}
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */