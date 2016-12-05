<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library("session");
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

        $session_data = array("name" => $user->name,
                              "id" => $user->id,
                              "email" => $user->email);

        $this->session->set_userdata($session_data);

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
}