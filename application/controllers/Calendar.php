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
		$data["dates"] = array();
		$data["weeks"] = 8;
		$data["today"] = getdate();
		$daysToProcess = $data["weeks"] * 7;
		$x = 0;

		while($x < $daysToProcess)
		{
			$date_string = "";

			if ($data["today"]["wday"] == 1)
			{
				$date_string = "now +" . $x . " day";
			}
			else
			{
				$date_string = "last Monday +" . $x . " day";
			}

			$next_day = getdate(strtotime($date_string));

			//print_r($next_day["month"] . " " . $next_day["mday"]);

			array_push($data["dates"], $next_day);

			$x++;
		}

		$this->LoadHead("Calendar");
		$this->LoadNav(TRUE);
		$this->load->view("calendar", $data);
	}
}

/* End of file calendar.php */
/* Location: ./application/controllers/calendar.php */