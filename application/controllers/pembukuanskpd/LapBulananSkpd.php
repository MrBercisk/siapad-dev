<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
class LapBulananSkpd extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('pembukuanskpd/Mlapbulanan');
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
			'forminsert' =>  implode($this->Mlapbulanan->formInsert())
	
		];
		$this->load->view('pembukuanskpd/lapbulskpd',$data);
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

	$bulan =  $this->input->post('bulan');
	$tahun = $this->input->post('tahun');
    $tipe = $this->input->post('tipe');

	$bulan = intval($bulan);
	$tahun = intval($tahun);

	$tgl_sebelum = new DateTime("$tahun-$bulan-01"); 
	$tgl_sebelum->modify('-1 month'); 
	$bulan_sebelum = $tgl_sebelum->format('m');
	$tahun_sebelum = $tgl_sebelum->format('Y'); 

	/* $tablenya = $this->Mlapbulanan->ambilskpdbulanan($bulan, $tahun, $tipe);
	echo '<pre>';
	var_dump($tablenya);
	echo '</pre>';
	die(); */

	$saldonyaSdhbayar = $this->Mlapbulanan->get_saldo_sudahbayar($tahun, $bulan);
	$saldonyasemua = $this->Mlapbulanan->get_saldo_semua($tahun, $bulan);
	$data = [
		'footer' => $template['footer'],
		'title' => $setpage->title,
		'link' => $setpage->link,
		'topbar' => $template['topbar'],
		'sidebar' => $template['sidebar'],
        'format_bulan' => strftime('%B', strtotime("$tahun-$bulan")),
        'format_bulan_sebelum' => strftime('%B %Y', strtotime("$tahun_sebelum-$bulan_sebelum")),
		'format_tahun' => $tahun,
		'tipe' => $tipe,
		'saldo_sdhbayar' => $saldonyaSdhbayar,
		'saldonyasemua' => $saldonyasemua,
		'tablenya' => $this->Mlapbulanan->ambilskpdbulanan($bulan, $tahun, $tipe),
        'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tgl_cetak)),

	];
	
	$tanda_tangan_data = $this->Msetup->get_tanda_tangan_tanpa_checbox($tanda_tangan);
	if ($tanda_tangan_data) {
		$data['tanda_tangan'] = $tanda_tangan_data;
	}
 

	$this->load->view('pembukuanskpd/printlapbulskpd', $data);
	/* ob_start();
	$html = $this->load->view('pembukuanskpd/printlapbulskpd', $data, true);
	ob_get_clean();
	

	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->render();
	$dompdf->stream("buku_besar_per_dinas.pdf", array("Attachment" => 0)); */
}


}

?>
		
