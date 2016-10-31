<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller 
{
	public function index()
	{
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
			redirect("/calendar");
		}

		//$name = $this->input->post("firstname");
		//$email = $this->input->post("email");
		//$password = $this->input->post("password");

		// validate the input
		// if it's good...
		//  hash the password with the email
		//  store the info in the db
		//  redirect user to calendar thing
		// if it's not good...
		//  re-show the index with error messages
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */