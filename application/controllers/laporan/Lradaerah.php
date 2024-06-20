<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
use Dompdf\Options;
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
class Lradaerah extends CI_Controller {
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
		$data['forminsert'] = implode($this->Mlradaerah->formInsert1());
		$this->load->view('laporan/rladaerah',$data);
	}
	public function cetak() {
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
        redirect('404');
    }
	set_time_limit(300);
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
   
	
	$data['tgl_cetak'] = $this->input->post('tgl_cetak');
	$tanggal = $this->input->post('tanggal');
	
	$tanda_tangan = $this->input->post('tanda_tangan');
	$ttd_checkbox = $this->input->post('ttd_checkbox') ? true : false;
	
	if($ttd_checkbox && $tanda_tangan){
		$ttddetail = $this->db
		->select('id, nama, nip, jabatan1, jabatan2')
		->from('mst_tandatangan')
		->where('id', $tanda_tangan)
		->get()
		->row_array();
		$data['tanda_tangan'] = $ttddetail;
	}
	$data['ttd_checkbox'] = $ttd_checkbox;

	$data['apbdp_checkbox'] = $this->input->post('apbdp_checkbox') ? true : false;

	$data['tablenya'] = $this->Mlradaerah->get_data_harian($tanggal);
	
	ob_start();
	$html = $this->load->view('laporan/printlap', $data, true);
	ob_clean();
    ob_flush();

	$dompdf = new Dompdf();
	$options = new Options();
	$options->set('isHtml5ParserEnabled', true);
	$options->set('isPhpEnabled', true);
	$dompdf->loadHtml($html);
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->render();
	$dompdf->stream("laporan_lra_harian.pdf", array("Attachment" => 0));
}

}

?>
		
