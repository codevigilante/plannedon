<?php

class User extends CI_Model 
{
    public $id = -1;
    public $name = "";
    public $email = "";
    public $public = TRUE;
    public $password_hash = "";
    public $remember = "";
    public $temp_password = FALSE;

	public function __construct()
	{
		$this->load->database();
	}

    public function setUser($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    public function validate($email, $password)
    {
        $exists = $this->fetch($email);

        if ($exists == FALSE)
        {
            return(FALSE);
        }

        $valid = password_verify($password, $this->password_hash);

        return($valid);
    }

    public function fetch($email)
    {
        $this->email = $email;

        $query = $this->db->get_where('user', array('email' => $this->email), 1);

        return($this->setFromQuery($query));
    }

    public function fetchFromRemember($remember_token)
    {
        if (empty($remember_token))
        {
            return(FALSE);
        }

        $query = $this->db->get_where('user', array('remember' => $remember_token), 1);

        return($this->setFromQuery($query));
    }

    private function setFromQuery($query)
    {
        if ($query->num_rows() == 0)
        {
            return(FALSE);
        }

        $row = $query->row();

        if (!isset($row))
        {
            return (FALSE);
        }
        
        $this->id = $row->id;
        $this->name = $row->name;
        $this->password_hash = $row->pswd_hash;
        $this->temp_password = $row->pswd_temp == 1;
        $this->public = $row->public == 1;
        $this->remember = $row->remember;

        return(TRUE);
    }

    public function add($name, $email, $password)
    {
        if (empty($email) || empty($password) || empty($name))
        {
            return(FALSE);
        }

        $hashed = password_hash($password, PASSWORD_BCRYPT);

        $data = array("name" => $name,
                      "email" => $email,
                      "pswd_hash" => $hashed);

        $this->db->insert("user", $data);

        return (TRUE);
    }

    public function setRemember($remember_string)
    {
        if (empty($remember_string))
        {
            return(FALSE);
        }

        if (empty($this->email))
        {
            return(FALSE);
        }
        
        $this->db->where("email", $this->email);        
        $this->db->update("user", array("remember" => $remember_string));

        return(TRUE);
    }
}

/* End of file user.php */
/* Location: ./application/models/user.php */