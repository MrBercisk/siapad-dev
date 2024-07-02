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
	$dinas = $this->input->post('dinas');

	$data = [
		'footer' => $template['footer'],
		'title' => $setpage->title,
		'link' => $setpage->link,
		'topbar' => $template['topbar'],
		'sidebar' => $template['sidebar'],
		'ttd_checkbox' => $ttd_checkbox,
        'format_bulan' => strftime('%B', strtotime("$tahun-$bulan")),
		'format_tahun' => $tahun,
        'dinas' => $dinas,
		'tablenya' => $this->MKasDinas->getdatabukubesar($dinas, $tahun, $bulan),
        'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tgl_cetak)),

	];
	$tanda_tangan_data = $this->Msetup->get_tanda_tangan($ttd_checkbox, $tanda_tangan);

	if ($tanda_tangan_data) {
		$data['tanda_tangan'] = $tanda_tangan_data;
	}
    $dinas_data = $this->Msetup->get_dinas($dinas);
	if ($dinas_data) {
		$data['dinas'] = $dinas_data;
	}
	
	ob_start();
	$html = $this->load->view('bukubesar/printbkdinas', $data, true);
	ob_get_clean();
	

	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->render();
	$dompdf->stream("buku_besar_per_dinas.pdf", array("Attachment" => 0));
}


}

?>
		
