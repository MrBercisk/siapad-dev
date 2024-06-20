<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

date_default_timezone_set("Asia/Jakarta");
class RelBphtb extends CI_Controller {
	private $data = [];
	public function __construct() {
        parent::__construct();
		$this->load->model('laporan/MLapBphtb');
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
			'forminsert' =>  implode($this->MLapBphtb->formInsert())
	
		];
		$this->load->view('laporan/relbphtb',$data);
	}
	public function cetak() {
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
        redirect('404');
    }
    $base 			  = $this->Msetup->setup();
    $setpage 		  = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
    $template 		  = $this->Msetup->loadTemplate($setpage->title);
	
	$tanggal = $this->input->post('tanggal');
	$tanggal_sebelumnya = date('Y-m-d', strtotime('-1 day', strtotime($tanggal)));
	
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
		'tablenya' => $this->MLapBphtb->get_laporan_hari($tanggal)
	];

	$tanda_tangan_data = $this->Msetup->get_tanda_tangan($ttd_checkbox, $tanda_tangan);
	if ($tanda_tangan_data) {
		$data['tanda_tangan'] = $tanda_tangan_data;
	}

	$data['tablenya'] = $this->MLapBphtb->get_laporan_hari($tanggal);
	
	ob_start();
	$html = $this->load->view('laporan/printbphtb', $data, true);
	ob_get_clean();

	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->render();
	$dompdf->stream("laporan_bphtb.pdf", array("Attachment" => 0));
}

}

?>
		
