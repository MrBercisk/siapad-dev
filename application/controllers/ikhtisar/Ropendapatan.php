<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
class Ropendapatan extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('ikhtisar/Mropendapatan');
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
			'forminsert' =>  implode($this->Mropendapatan->formInsert())
		];
		$this->load->view('ikhtisar/ropend',$data);
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
	$rekening = $this->input->post('rekening');

	
	$data = [
		'footer' => $template['footer'],
		'title' => $setpage->title,
		'link' => $setpage->link,
		'topbar' => $template['topbar'],
		'sidebar' => $template['sidebar'],
		'ttd_checkbox' => $ttd_checkbox,
		'format_bulan' => strftime('%B', strtotime($bulan)),
		'format_tahun' => strftime('%Y', strtotime($tahun)),
		'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tgl_cetak)),
		'tabelnya' => $this->Mropendapatan->get_laporan_bulanan($bulan, $tahun)
		
	];
	$cek = $this->Mropendapatan->get_laporan_bulanan($bulan, $tahun);
	echo '<pre>';
	var_dump($cek);
	echo '</pre>';
	die();


	
	$tanda_tangan_data = $this->Msetup->get_tanda_tangan($ttd_checkbox, $tanda_tangan);
	if ($tanda_tangan_data) {
		$data['tanda_tangan'] = $tanda_tangan_data;
	}
	$rek_data = $this->Msetup->get_rekening($rekening);
	if ($rek_data) {
		$data['rekening'] = $rek_data;
	}


    ob_start();
    $html = $this->load->view('ikhtisar/printlopen', $data, true);
    echo ob_get_clean();

	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->render();
	$dompdf->stream("laporan_perencanaan_objek.pdf", array("Attachment" => 0));
}

}

?>
		
