<?php

class UserData
{
    public $id = 0;
    public $name = "";
    public $email = "";

    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }
}

class User extends CI_Model 
{
	public function __construct()
	{
		$this->load->database();
	}
    
    public function add($user_data, $password)
    {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $result = array ("success" => FALSE, "duplicate" => FALSE, "error_msg" => "");

        return ($result);
        // check if user already exists
        // if so...
        //  return FALSE
        // otherwise
        //  insert in db
        //  return TRUE
    }
}

/* End of file user.php */
/* Location: ./application/models/user.php */