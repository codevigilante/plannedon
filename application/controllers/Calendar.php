<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendar extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();

		if (! $this->UserData["authed"])
        {
            redirect("/login");
        }
	}

	public function index()
	{
		$this->LoadHead("Calendar");
		$this->LoadNav(TRUE);
		$this->load->view("calendar");
	}
}

/* End of file calendar.php */
/* Location: ./application/controllers/calendar.php */