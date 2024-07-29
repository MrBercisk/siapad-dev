<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
class PiutangReklame extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('pembukuanskpd/Mpiutangrek');
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
			'forminsert' =>  implode($this->Mpiutangrek->formInsert())
	
		];
		$this->load->view('pembukuanskpd/lappiutangrek',$data);
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

	$tahun = intval($tahun);

	/* $tablenya = $this->Mlapbulanan->ambilskpdbulanan($bulan, $tahun, $tipe);
	echo '<pre>';
	var_dump($tablenya);
	echo '</pre>';
	die(); */

	$data = [
		'footer' => $template['footer'],
		'title' => $setpage->title,
		'link' => $setpage->link,
		'topbar' => $template['topbar'],
		'sidebar' => $template['sidebar'],
		'format_tahun' => $tahun,
		'tablenya' => $this->Mpiutangrek->ambilpiutangrek($tahun),
        'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tgl_cetak)),

	];
	
	$tanda_tangan_data = $this->Msetup->get_tanda_tangan_tanpa_checbox($tanda_tangan);
	if ($tanda_tangan_data) {
		$data['tanda_tangan'] = $tanda_tangan_data;
	}
 

	/* $this->load->view('pembukuanskpd/printpiutangrek', $data); */
	ob_start();
	$html = $this->load->view('pembukuanskpd/printpiutangrek', $data, true);
	ob_get_clean();
	

	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->render();
	$dompdf->stream("buku_besar_per_dinas.pdf", array("Attachment" => 0));
}


}

?>
		
