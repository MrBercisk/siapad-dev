<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
use Dompdf\Options;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
date_default_timezone_set("Asia/Jakarta");
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
	
	$tanggal = $this->input->post('tanggal');
	$tgl_cetak = $this->input->post('tgl_cetak');
	$tanda_tangan = $this->input->post('tanda_tangan');
	$ttd_checkbox = $this->input->post('ttd_checkbox') ? true : false;
	$apbdp_checkbox = $this->input->post('apbdp_checkbox') ? true : false;
	$audited = $this->input->post('audited') ? true : false;
	$un_audited = $this->input->post('un_audited') ? true : false;


	$tahun = date('Y', strtotime($tanggal));
    $tahun_depannya = $tahun + 1;

	$tablenya = $this->Mlradaerah->get_data_harian($tanggal);
	
	
	$data = [
		'footer' => $template['footer'],
		'title' => $setpage->title,
		'link' => $setpage->link,
		'topbar' => $template['topbar'],
		'sidebar' => $template['sidebar'],
		/* 'nobaris_checkbox' => $nobaris_checkbox, */
		'ttd_checkbox' => $ttd_checkbox,
		'apbdp_checkbox' => $apbdp_checkbox,
		'audited' => $audited,
		'un_audited' => $un_audited,
		'tgl_cetak' => $tgl_cetak,
		'tahun_depannya' => $tahun_depannya,
		'tablenya' => $tablenya,
		'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tgl_cetak)),
		'tgl_format' =>strftime('%d %B %Y', strtotime($tanggal)),
		
	];

	
	if($ttd_checkbox && $tanda_tangan){
		$ttddetail = $this->db
		->select('id, nama, nip, jabatan1, jabatan2')
		->from('mst_tandatangan')
		->where('id', $tanda_tangan)
		->get()
		->row_array();
		$data['tanda_tangan'] = $ttddetail;
	}

	$this->load->view('laporan/printlap', $data);
	/* ob_start();
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
	$dompdf->stream("laporan_lra_harian.pdf", array("Attachment" => 0)); */
}

}

?>
		
