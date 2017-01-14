<?php

class Activity extends CI_Model 
{
	public $id;
	public $month;
	public $day;
	public $year;
	public $timeframe;
	public $time;
	public $activity;
	public $sql;

	public function __construct()
	{
		$this->load->database();
	}

	public function get($start, $end, $user_email)
	{
		// select * from activity where user_email="email" and date>=start and date<=end order by date ASC;

		$query = $this->db->get_where('activity', array('user_email' => $user_email));

		$activities = array(
			"user" => $user_email,
			"start" => $start,
			"end" => $end,
			"payload" => array()
		);

		if ($query->num_rows() > 0)
		{
			$payload = $activities["payload"];
			$curDate = strtotime("10 September 2000");

			foreach($query->result() as $row)
			{
				$date = $this->dashesToSlashes($row->date);

				if ($curDate != strtotime($date))
				{
					$activities["payload"][$date] = array();
					$curDate = strtotime($date);
				}

				array_push($activities["payload"][$date], array(
					"id" => $row->id,
					"time" => $row->time,
					"description" => $row->description,
					"time_frame" => $row->time_frame
				));
			}
		}

		return ($activities);
	}

	public function add($activityData)
	{
		if (empty($activityData) || empty($activityData["when"]) || empty($activityData["user_email"]))
        {
			$this->id = -1;

            return(FALSE);
        }

		$when = $this->slashesToDashes($activityData["when"]);

		$data = array("user_email" => $activityData["user_email"],
					  "date" => $when,
					  "time" => $activityData["time"],
					  "description" => $activityData["activity"],
					  "time_frame" => $activityData["timeframe"]
					  );

        $this->db->insert("activity", $data);
		$this->id = $this->db->insert_id();

		//$this->sql = $this->db->set($data)->get_compiled_insert('activity');

        return (TRUE);
	}

	private function slashesToDashes($date)
	{
		// converts date in the form of M/D/Y
		$mdy = DateTime::createFromFormat('m/d/Y', $date);

		return($mdy->format("Y-n-j"));
	}

	private function dashesToSlashes($date)
	{
		$mdy = DateTime::createFromFormat('Y-m-d', $date);

		return($mdy->format("n/j/Y"));
	}
}

/* End of file activity.php */
/* Location: ./application/models/activity.php */