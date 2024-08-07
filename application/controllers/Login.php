<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Login extends CI_Controller
{
    public function index()
    {
        $base         = $this->Msetup->setup();
        $setpage     = $this->Msetup->get_title(($base['halaman']) !== "" ? $base['halaman'] : 'dashboard');
        $template     = $this->Msetup->loadTemplate($setpage->title);
        $data = [];
        $data['footer']     = $template['footer'];
        $data['title']         = $setpage->title;
        $data['topbar']     = $template['topbar'];
        $data['sidebar']     = $template['sidebar'];
        $this->load->view('login.php', $data);
    }
    public function getAuth()
    {
        var_dump($_POST);
    }
}
