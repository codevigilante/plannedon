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

		$this->load->view("activity", $result);
	}

	public function add()
	{
		$data = $this->input->post("activityData");

		$this->load->model("Activity");

		$data["user_email"] = $this->UserData["email"];

		$result = $this->Activity->add($data);

		if ($result == FALSE)
		{
			return;
		}

		$data["id"] = $this->Activity->id;

		echo json_encode($data);
	}

	public function update()
	{
		$data = $this->input->post("activityData");

		$this->load->model("Activity");

		$result = $this->Activity->update($data);

		if ($result == FALSE)
		{
			return;
		}

		echo json_encode($data);
	}

	public function remove()
	{
		$data = $this->input->post("activityData");

		$this->load->model("Activity");

		$result = $this->Activity->delete($data);

		if ($result == FALSE)
		{
			return;
		}

		echo json_encode($data);
	}

}

/* End of file calendar.php */
/* Location: ./application/controllers/calendar.php */