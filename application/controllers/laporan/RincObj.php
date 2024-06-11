<?php defined('BASEPATH') OR exit('No direct script access allowed');
class RelBphtb extends CI_Controller {
	private $data = [];
	public function __construct() {
        parent::__construct();
		$this->load->model('laporan/MrincObj');
    }
	public function index()
	{	
		$Jssetup	= $this->Jssetup;
		$base 		= $this->Msetup->setup();
		$setpage	= $this->Msetup->get_title($base['halaman'].'/'.$base['fungsi']);
		$template 	= $this->Msetup->loadTemplate($setpage->title);
		$data['footer']		= $template['footer'];
		$data['title'] 		= $setpage->title;
		$data['link'] 		= $setpage->link;
		$data['topbar'] 	= $template['topbar'];
		$data['modalEdit'] 	= [];
		$data['modalDelete']= [];
		$data['sidebar'] 	= $template['sidebar'];
		$data['jstable']	= NULL;
	 	$data['jsedit']		= NULL;
	 	$data['jsdelete']	= NULL;
		$data['forminsert'] = implode($this->MrincObj->formInsert());
		$this->load->view('laporan/rladaerah',$data);
	}
	public function printLap() {
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
        redirect('404');
    }
    $Jssetup 		  = $this->Jssetup;
    $base 			  = $this->Msetup->setup();
    $setpage 		  = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
    $template 		  = $this->Msetup->loadTemplate($setpage->title);
    $data['footer']   = $template['footer'];
    $data['title'] 	  = $setpage->title;
    $data['link'] 	  = $setpage->link;
    $data['topbar']   = $template['topbar'];
    $data['sidebar']  = $template['sidebar'];
    $data['jstable']  = ''; // $Jssetup->jsDatatable2('#ftf','Api/ApiLradaerah/fetch_data');
	$data['tablenya'] = $this->MrincObj->get_data('2024-05-1');
    $tanggal = $this->input->post('tanggal');
    
    ob_start();
    $this->load->view('laporan/relbphtb', $data);
    echo ob_get_clean();
}

}

?>
		
