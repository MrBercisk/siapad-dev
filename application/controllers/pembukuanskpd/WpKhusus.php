<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
class Wpkhusus extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('pembukuanskpd/Mwpkhusus');
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
			'forminsert' =>  implode($this->Mwpkhusus->formInsert())
	
		];
		$this->load->view('pembukuanskpd/lapwpkhusus',$data);
	}

	public function cetak() {
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
        redirect('404');
    }
    $base 			  = $this->Msetup->setup();
    $setpage 		  = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
    $template 		  = $this->Msetup->loadTemplate($setpage->title);

	$tgl_cetak = $this->input->post('tgl_cetak');

	$tanda_tangan = $this->input->post('tanda_tangan');

	$tahun = $this->input->post('tahun');
	$tipe = $this->input->post('tipe');
	$tglawal = $this->input->post('tglawal');	
	$tglakhir = $this->input->post('tglakhir');	

	/* $tablenya = $this->Mlrabapop->ambildata($kdrekening, $tahun); */
	/* echo '<pre>';
	var_dump($kdrekening);
	echo '</pre>';
	die();
 */

	$data = [
		'footer' => $template['footer'],
		'title' => $setpage->title,
		'link' => $setpage->link,
		'topbar' => $template['topbar'],
		'sidebar' => $template['sidebar'],
		'format_tahun' => $tahun,
        'tglawal' => $tglawal,
        'tglakhir' => $tglakhir,
		'tablenya' => $this->Mwpkhusus->ambildata($tglawal, $tglakhir, $tipe, $tahun),
        'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tgl_cetak)),
        'tgl_awal_format' =>strftime('%d %B %Y', strtotime($tglawal)),
        'tgl_akhir_format' =>strftime('%d %B %Y', strtotime($tglakhir)),

	];
	
	$tanda_tangan_data = $this->Msetup->get_tanda_tangan_tanpa_checbox($tanda_tangan);
	if ($tanda_tangan_data) {
		$data['tanda_tangan'] = $tanda_tangan_data;
	}
 
/* 	$this->load->view('pembukuanskpd/printwpkhusus', $data ); */
	
	ob_start();
	$html = $this->load->view('pembukuanskpd/printwpkhusus', $data, true);
	ob_get_clean();
	

	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->render();
	$dompdf->stream("wp_keterangan_khusus.pdf", array("Attachment" => 0));
}




}

?>
		
