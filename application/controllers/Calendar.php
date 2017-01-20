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
			"relorder" => $this->input->post("relorder"),
			"user_email" => $this->UserData["email"]
		);

		$result = $this->Activity->add($data);

		if ($result == FALSE)
		{
			echo "Failed to add";

			return;
		}

		$data["id"] = $this->Activity->id;

		echo json_encode($data);
	}

	public function update()
	{
		if (empty($this->input->post("index")))
		{
			echo "Nothing to do here.";

			return;
		}

		$data = array(
			"when" => $this->input->post("when"),
			"timeframe" => $this->input->post("timeframe"),
			"time" => $this->input->post("time"),
			"activity" => $this->input->post("activity"),
			"id" => $this->input->post("index"),
			"user_email" => $this->UserData["email"]
		);

		$this->load->model("Activity");

		$result = $this->Activity->update($data);

		if ($result == FALSE)
		{
			echo "Failed to update";

			return;
		}

		echo json_encode($data);
	}

	public function updateorder()
	{
		if (empty($this->input->post("activities")))
		{
			echo "Nothing to do here.";

			return;
		}

		$this->load->model("Activity");
		$data = $this->input->post("activities");

		$this->Activity->updateorder($data);

		echo json_encode($data);
	}

	public function remove()
	{
		if (empty($this->input->post("index")))
		{
			echo "Nothing to do here.";

			return;
		}

		$this->load->model("Activity");

		$result = $this->Activity->delete($this->input->post("index"));

		if ($result == FALSE)
		{
			echo "Failed to delete";

			return;
		}

		echo $this->input->post("index");
	}

}

/* End of file calendar.php */
/* Location: ./application/controllers/calendar.php */