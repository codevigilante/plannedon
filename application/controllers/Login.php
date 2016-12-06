<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller 
{
    public function __construct()
	{
        parent::__construct();
	}

	public function index()
	{
        if ($this->UserData["authed"])
        {
            redirect("/calendar");
            return;
        }

        $this->loadLogin("Login", array("show_login" => FALSE), array(), "login");
	}

    public function validate()
    {
        $this->load->library('form_validation');

		$this->form_validation->set_rules("email", "Email Address", "trim|required|valid_email");
		//$this->form_validation->set_rules("password", "Password", "trim|required");

		$head_data = "Login";
        $nav_data = array("show_login" => FALSE);
		
		if ($this->form_validation->run() == FALSE)
        {
            $this->loadLogin($head_data, $nav_data, array("form_errors" => TRUE), "login");
        }
        else
        {
            $this->load->model('User');

            $exists = $this->User->fetch($this->input->post("email"));

            //$valid = $this->User->validate($this->input->post("email"), $this->input->post("password"));

            if ($exists)
            {
                $this->LoginUser($this->User);
            }
            else
            {
                $this->loadLogin($head_data, $nav_data, array("valid" => FALSE), "login");
            }

            /*if ($valid == FALSE)
            {
                $this->loadLogin($head_data, $nav_data, array("valid" => FALSE), "login");
            }
            else
            {
                redirect("/calendar");
            }*/
        }
    }

    public function recover()
    {
        $this->load->library('form_validation');

		$this->form_validation->set_rules("email", "Email Address", "trim|required|valid_email");

		$head_data = "Help";
        $nav_data = array("show_login" => TRUE);
		
		if ($this->form_validation->run() == FALSE)
        {
            $this->loadLogin($head_data, $nav_data, array("form_errors" => TRUE), "help");
        }
        else
        {
            $this->load->model('User');

            $found = $this->User->fetch($this->input->post("email"));

            if ($found == FALSE)
            {
                $this->loadLogin($head_data, $nav_data, array("unfound" => TRUE), "help");
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

    public function logout()
    {
        $this->session->sess_destroy();
        $this->input->set_cookie("remember", "", 0);

        redirect("/login");
    }

    public function help()
    {
        $this->loadLogin("Help", array("show_login" => TRUE), array(), "help");
    }

    private function loadLogin($head_data, $nav_data, $body_data, $body)
    {
        $this->LoadHead($head_data);
        $this->load->view('nav', $nav_data);
		$this->load->view($body, $body_data);
    }
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */