<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Lrabulanan extends CI_Controller {
	private $data = [];
	public function __construct() {
        parent::__construct();
		$this->load->model('laporan/Mlradaerah');
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
		$data['forminsert'] = implode($this->Mlradaerah->formInsert2());
		$this->load->view('laporan/rladaerah',$data);
	}
	public function cetak() {
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
    $data['jstable']  = '';
    $tanggal = $this->input->post('tanggal');
	$data['tablenya'] = $this->Mlradaerah->get_data_bulanan($tanggal);
    
    ob_start();
    $this->load->view('laporan/printlap', $data);
    echo ob_get_clean();
}

}

?>
		
