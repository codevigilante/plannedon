<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendar extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("User");

		$session_set = $this->session->has_userdata("id");

		if ($session_set)
		{

		}

		// if session data is present, use it to load activity
		// if it's not, check the remember_me cookie
		// if it's there, check it against the database and load the user, start a new session, refresh the cookie
		// if it's not there, redirect to login page
	}

	public function index()
	{
		$this->load->helper("cookie");
		$remember = $this->input->cookie("bloodfish_remember");

		if (!empty($remember))
		{

		}
		else
		{
			redirect("/login");
		}

		$this->load->view('head', array("page" => "Calendar"));
        $this->load->view('nav', array("show_login" => FALSE));
		$this->load->view("calendar");
	}
}

/* End of file calendar.php */
/* Location: ./application/controllers/calendar.php */