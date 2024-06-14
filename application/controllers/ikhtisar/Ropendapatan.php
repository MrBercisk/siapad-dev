<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Ropendapatan extends CI_Controller {
	private $data = [];
	public function __construct() {
        parent::__construct();
		$this->load->model('ikhtisar/Mropendapatan');
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
		$data['forminsert'] = implode($this->Mropendapatan->formInsert());
		$this->load->view('ikhtisar/ropend',$data);
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
	$tanggal = $this->input->post('tanggal');
	
	$data['blnpajak'] = $this->input->post('blnpajak');
	$data['tahun'] = $this->input->post('tahun');
	$tahun = $this->input->post('tahun');
	$data['rekening'] = $this->input->post('rekening');

	$data['tablenya'] = $this->Mropendapatan->get_laporan_hari($tanggal);

    ob_start();
    $this->load->view('ikhtisar/printlopen', $data);
    echo ob_get_clean();
}

}

?>
		
