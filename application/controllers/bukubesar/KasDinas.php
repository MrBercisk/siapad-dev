<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
class KasDinas extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('bukubesar/MKasDinas');
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
			'forminsert' =>  implode($this->MKasDinas->formInsert())
	
		];
		$this->load->view('bukubesar/perdinas',$data);
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
	$ttd_checkbox = $this->input->post('ttd_checkbox') ? true : false;

	$bulan =  $this->input->post('bulan');
	$tahun = $this->input->post('tahun');
	$iddinas = $this->input->post('iddinas');
/* 
	$tablenya = $this->MKasDinas->get_data_bkdinas($bulan, $tahun);
	echo '<pre>';
	var_dump($dinas);
	echo '</pre>';
	die(); */

	$totals = $this->MKasDinas->get_apbd_apbdp_total($tahun, $iddinas);

	$data = [
		'footer' => $template['footer'],
		'title' => $setpage->title,
		'link' => $setpage->link,
		'topbar' => $template['topbar'],
		'sidebar' => $template['sidebar'],
		'ttd_checkbox' => $ttd_checkbox,
        'format_bulan' => strftime('%B', strtotime("$tahun-$bulan")),
		'format_tahun' => $tahun,
		'iddinas' => $iddinas,
		'tablenya' => $this->MKasDinas->get_data_bkdinas($bulan, $tahun, $iddinas),
		'total_apbd' => $totals->total_apbd,
		'total_apbdp' => $totals->total_apbdp,
        'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tgl_cetak)),

	];
	
	$tanda_tangan_data = $this->Msetup->get_tanda_tangan($ttd_checkbox, $tanda_tangan);

	if ($tanda_tangan_data) {
		$data['tanda_tangan'] = $tanda_tangan_data;
	}
    $dinas_data = $this->Msetup->get_dinas($iddinas);
	if ($dinas_data) {
		$data['iddinas'] = $dinas_data;
	}
	$this->load->view('bukubesar/printbkdinas', $data);
	/* ob_start();
	$html = $this->load->view('bukubesar/printbkdinas', $data, true);
	ob_get_clean();
	

	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->render();
	$dompdf->stream("buku_besar_per_dinas.pdf", array("Attachment" => 0)); */
}


}

?>
		
