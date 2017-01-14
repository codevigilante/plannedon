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
		$start = $this->input->post("start");
		$end = $this->input->post("end");

		if (empty($start) || empty($end))
		{
			return;
		}

		$this->load->model("Activity");
		$result = $this->Activity->get($start, $end, $this->UserData["email"]);

		//print_r($result);

		$this->load->view("activity", $result);
	}

	public function add()
	{
		if (empty($this->input->post("when")))
		{
			echo "Nothing to do here.";

			return;
		}

		$this->load->model("Activity");

		$data = array(
			"when" => $this->input->post("when"),
			"timeframe" => $this->input->post("timeframe"),
			"time" => $this->input->post("time"),
			"activity" => $this->input->post("activity"),
			"user_email" => $this->UserData["email"]
		);

		$result = $this->Activity->add($data);

		echo $this->Activity->id;
	}

}

/* End of file calendar.php */
/* Location: ./application/controllers/calendar.php */