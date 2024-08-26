<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends CI_Controller
{
	public function index()
	{

		$base 		= $this->Msetup->setup();
		$setpage 	= $this->Msetup->get_title(($base['halaman']) !== "" ? $base['halaman'] : 'dashboard');
		$template 	= $this->Msetup->loadTemplate($setpage->title);
		$data = [];
		$data['footer'] 	= $template['footer'];
		$data['title'] 		= $setpage->title;
		$data['topbar'] 	= $template['topbar'];
		$data['sidebar'] 	= $template['sidebar'];

		// var_dump($this->session->userdata());
		$this->load->view('dashboard.php', $data);
	}
}
