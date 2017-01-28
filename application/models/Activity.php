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

		$this->db->where("date >=", $this->slashesToDashes($start));
		$this->db->where("date <=", $this->slashesToDashes($end));
		$this->db->where("user_email", $user_email);
		$this->db->order_by("rel_order", "ASC");
		$query = $this->db->get("activity");

		$activities = array(
			"user" => $user_email,
			"start" => $start,
			"end" => $end,
			"payload" => array()
		);

		if ($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$date = $this->dashesToSlashes($row->date);

				if (!array_key_exists($date, $activities["payload"]))
				{
					$activities["payload"][$date] = array();									
				}

				array_push($activities["payload"][$date], array(
					"id" => $row->id,
					"time" => $row->time,
					"description" => $row->description,
					"time_frame" => $row->time_frame,
					"rel_order" => $row->rel_order
				));	
			}
		}

		return ($activities);
	}

	public function getById($id)
	{
		$activity = array();

		if (empty($id))
        {
			$this->id = -1;

            return($activity);
        }

		$query = $this->db->get_where('activity', array('id' => $id));

		if ($query->num_rows() <= 0)
		{
			return($activity);
		}

		$row = $query->row();

		$activity["index"] = $id;
		$activity["time"] = $row->time;
		$actvitiy["description"] = $row->description;
		$activity["time_frame"] = $row->time_frame;
		$activity["date"] = $this->dashesToSlashes($row->date);

		return($activity);
	}

	public function add($activityData)
	{
		if (empty($activityData) || empty($activityData["date"]) || empty($activityData["user_email"]))
        {
			$this->id = -1;

            return(FALSE);
        }

		$data = array("user_email" => $activityData["user_email"],
					  "date" => $this->slashesToDashes($activityData["date"]),
					  "time" => $activityData["time"],
					  "description" => $activityData["activity"],
					  "rel_order" => $activityData["order"]
	    			  );

        $this->db->insert("activity", $data);
		$this->id = $this->db->insert_id();

        return (TRUE);
	}

	public function update($activityData)
	{
		if (empty($activityData))
        {
            return(FALSE);
        }

		$this->db->trans_start();

		if (isset($activityData["id"]))
		{
			$current = $this->getById($activityData["id"]);

			if (count($current) == 0)
			{
				return(FALSE);
			}

			$data = array("date" => $this->slashesToDashes($activityData["date"]),
							"time" => $activityData["time"],
							"description" => $activityData["activity"]
							);

			

			$this->db->where("id", $activityData["id"]);
			$this->db->update("activity", $data);
		}

		if (isset($activityData["ordering"]))
		{
			$this->updateorder($activityData["ordering"]);
		}

		$this->db->trans_complete();

		return (TRUE);
	}

	public function updateorder($activityOrdering)
	{
		if (empty($activityOrdering))
        {
            return(FALSE);
        }

		$data = array();

		foreach($activityOrdering as $activity)
		{
			$this->db->where("id", $activity["id"]);
			$this->db->update("activity", array("rel_order" => $activity["order"]));
		}
		
		return(TRUE);
	}

	public function delete($activityData)
	{
		if (empty($activityData) || !isset($activityData["id"]))
        {
            return(FALSE);
        }

		$id = $activityData["id"];

		$current = $this->getById($id);

		if (count($current) == 0)
		{
			return(FALSE);
		}

		$this->db->trans_start();

		$this->db->where("id", $id);
		$this->db->delete("activity");

		if (isset($activityData["ordering"]))
		{
			$this->updateorder($activityData["ordering"]);
		}

		$this->db->trans_complete();

		return(TRUE);
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