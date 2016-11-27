<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller 
{
	public function index()
	{
        $this->loadLogin(array("page" => "Login"), array(), "login");
	}

    public function validate()
    {
        $this->load->library('form_validation');

		$this->form_validation->set_rules("email", "Email Address", "trim|required|valid_email");
		$this->form_validation->set_rules("password", "Password", "trim|required");

		$head_data = array("page" => "Login");
		
		if ($this->form_validation->run() == FALSE)
        {
            $this->loadLogin($head_data, array("form_errors" => TRUE), "login");
        }
        else
        {
            $this->load->model('User');

            $valid = $this->User->validate($this->input->post("email"), $this->input->post("password"));

            if ($valid == FALSE)
            {
                $this->loadLogin($head_data, array("valid" => FALSE), "login");
            }
            else
            {
                redirect("/calendar");
            }
        }
    }

    public function recover()
    {
        $this->load->library('form_validation');

		$this->form_validation->set_rules("email", "Email Address", "trim|required|valid_email");

		$head_data = array("page" => "Help");
		
		if ($this->form_validation->run() == FALSE)
        {
            $this->loadLogin($head_data, array("form_errors" => TRUE), "help");
        }
        else
        {
            $this->load->model('User');

            $found = $this->User->fetch($this->input->post("email"));

            if ($found == FALSE)
            {
                $this->loadLogin($head_data, array("unfound" => TRUE), "help");
            }
            else
            {
                // generate new password
                // mark as temporary
                // send email
                redirect("/login?recover=1");
            }
        }
    }

    public function help()
    {
        $this->loadLogin(array("page" => "Help"), array(), "help");
    }

    private function loadLogin($head_data, $body_data, $body)
    {
        $this->load->view('head', $head_data);
        $this->load->view('nav', array("show_login" => TRUE));
		$this->load->view($body, $body_data);
    }
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */