<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    protected $UserData = array("name" => "",
                                "email" => "",
                                "authed" => FALSE);

    public function __construct()
    {
        parent::__construct();

        $this->load->library("session");

        $session_set = $this->session->has_userdata("authed");

		if ($session_set)
		{
            $this->UserData["authed"] = $this->session->authed;
            $this->UserData["email"] = $this->session->email;
            $this->UserData["name"] = $this->session->name;

            return;
		}

        $remember_cookie = $this->input->cookie("bloodfish_remember");

        if (!empty($remember_cookie))
        {
            $this->load->model("User");
            
            $found = $this->User->fetchFromRemember($remember_cookie);

            if ($found)
            {
                $this->GenerateRemember($this->User);
                $this->BeginSession($this->User);
            }
        }
    }

    protected function GenerateRemember($user)
    {
        if (!($user instanceof User))
        {
            return(FALSE);
        }

        $this->load->helper("globals");

        $remember_string = randomPassword(25);

        $this->input->set_cookie("remember", $remember_string, 1209600);

        $user->setRemember($remember_string);

        return(TRUE);
    }

    protected function BeginSession($user)
    {
        if (!($user instanceof User))
        {
            return(FALSE);
        }

        $this->UserData["name"] = $user->name;
        $this->UserData["email"] = $user->email;
        $this->UserData["authed"] = TRUE;

        $this->session->set_userdata($this->UserData);

        return(TRUE);
    }

    protected function LoginUser($user)
    {
        if (!($user instanceof User))
        {
            $this->ShowError("Unable to determine who this is.");
            return;
        }

        $cookie_ok = $this->GenerateRemember($user);
        $session_ok = $this->BeginSession($user);

        if ($cookie_ok && $session_ok)
        {
            redirect("/calendar");
        }

        $this->ShowError("Internal error. I don't know what else to do.");
    }

    protected function ShowError($err_msg)
    {
        $this->load->view("head");
        $this->load->view("nav");
        $this->load->view("internal_error", array("err_msg" => $err_msg));
    }

    protected function LoadHead($page)
    {
        $this->load->view("head", array("page" => $page));
    }

    protected function LoadNav($show_login)
    {
        $data = array("show_login" => $show_login,
                      "authed" => $this->UserData["authed"],
                      "user_name" => $this->UserData["name"]);

        $this->load->view("nav", $data);
    }
}