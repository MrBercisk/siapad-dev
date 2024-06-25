<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
class Bkbesar extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('laporan/MBkBesar');
    }
	public function index()
	{	
		$base 		= $this->Msetup->setup();
		$setpage	= $this->Msetup->get_title($base['halaman'].'/'.$base['fungsi']);
		$template 	= $this->Msetup->loadTemplate($setpage->title);
		$data = [
			'footer' => $template['footer'],
			'topbar' => $template['topbar'],
			'sidebar' => $template['sidebar'],
			'title' => $setpage->title,
			'link' => $setpage->link,
			'forminsert' =>  implode($this->MBkBesar->formInsert())
	
		];
		$this->load->view('laporan/bkbesar',$data);
	}
	public function cetak() {
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
        redirect('404');
    }
    $base 			  = $this->Msetup->setup();
    $setpage 		  = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
    $template 		  = $this->Msetup->loadTemplate($setpage->title);
	
	$tanggal = $this->input->post('tanggal');

	$tgl_cetak = $this->input->post('tgl_cetak');
	$tanda_tangan = $this->input->post('tanda_tangan');
	$ttd_checkbox = $this->input->post('ttd_checkbox') ? true : false;

	$data = [
		'footer' => $template['footer'],
		'title' => $setpage->title,
		'link' => $setpage->link,
		'topbar' => $template['topbar'],
		'sidebar' => $template['sidebar'],
		'ttd_checkbox' => $ttd_checkbox,
		'format_tanggal' =>strftime('%d %B %Y', strtotime($tanggal)),
		'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tgl_cetak)),
		'tanggal_sebelumnya' =>strftime('%d %B %Y', strtotime('-1 day', strtotime($tanggal))),
		'tablenya' => $this->MBkBesar->get_data_hari_ini($tanggal),
		'saldo' => $this->MBkBesar->get_saldo_awal($tanggal),
		'pembiayaan' => $this->MBkBesar->get_pembiayaan($tanggal),
		'blud' => $this->MBkBesar->get_blud($tanggal),
		'bos' => $this->MBkBesar->get_bos($tanggal),
		'rilau' => $this->MBkBesar->get_rilau($tanggal),
	];
	$tanda_tangan_data = $this->Msetup->get_tanda_tangan($ttd_checkbox, $tanda_tangan);

	if ($tanda_tangan_data) {
		$data['tanda_tangan'] = $tanda_tangan_data;
	}
	
	ob_start();
	$html = $this->load->view('laporan/printbkbesar', $data, true);
	ob_get_clean();
	

	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->render();
	$dompdf->stream("laporan_buku_besar.pdf", array("Attachment" => 0));
}


}

?>
		
