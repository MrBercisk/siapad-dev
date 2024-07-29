<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
class LraBapendaOp extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('bukubesar/MlrabapOp');
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
			'forminsert' =>  implode($this->MlrabapOp->formInsert())
	
		];
		$this->load->view('bukubesar/lrabapendaop',$data);
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
	$idrekening = $this->input->post('idrekening');

	/* $tablenya =  $this->MBapendaOp->get_data_bapenda_wp($tahun, $kdrekening);
	echo '<pre>';
	var_dump($tablenya);
	echo '</pre>';
	die(); */

	$totals = $this->MBapendaOp->get_apbd_apbdp_total($tahun);

	$data = [
		'footer' => $template['footer'],
		'title' => $setpage->title,
		'link' => $setpage->link,
		'topbar' => $template['topbar'],
		'sidebar' => $template['sidebar'],
		'ttd_checkbox' => $ttd_checkbox,
        'format_bulan' => strftime('%B', strtotime("$tahun-$bulan")),
		'format_tahun' => $tahun,
		'idrekening' => $idrekening,
		'tablenya' => $this->Mlrabapop->get_data_bapenda_wp($tahun, $idrekening),
		'total_apbd' => $totals->total_apbd,
		'total_apbdp' => $totals->total_apbdp,
        'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tgl_cetak)),

	];
	
	$tanda_tangan_data = $this->Msetup->get_tanda_tangan($ttd_checkbox, $tanda_tangan);

	if ($tanda_tangan_data) {
		$data['tanda_tangan'] = $tanda_tangan_data;
	}
    $rek_data = $this->Msetup->get_rekening($kdrekening);
	if ($rek_data) {
		$data['kdrekening'] = $rek_data;
	}
	$this->load->view('bukubesar/printbapop', $data ,array("Attachment" => 1));
	
	/* ob_start();
	$html = $this->load->view('bukubesar/printbapop', $data, true);
	ob_get_clean();
	

	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->render();
	$dompdf->stream("buku_besar_bapenda_op.pdf", array("Attachment" => 0)); */
}

public function get_wajib_pajak($idrekening) {
    $data = $this->Mlrabapop->get_wajib_pajak($idrekening);

    $response = array();
    foreach ($data as $item) {
        $response[] = array(
            'id' => $item->id,
            'npwpd' => $item->npwpd,
            'nomor' => $item->nomor
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}
}

?>
		
