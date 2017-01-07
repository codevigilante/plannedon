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
		$data["weeks_to_show"] = 9;
		$data["show_previous_week"] = TRUE;

		$this->LoadHead("Calendar");
		$this->LoadNav(TRUE);
		$this->load->view("calendar", $data);
	}

	public function get()
	{
		// GET or POST should contain start-date and end-date
	}
}

/* End of file calendar.php */
/* Location: ./application/controllers/calendar.php */