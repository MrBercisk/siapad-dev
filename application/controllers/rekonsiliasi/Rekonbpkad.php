<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

date_default_timezone_set("Asia/Jakarta");

class Rekonbpkad extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('rekonsiliasi/MRekonbpkad');
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
		$data['forminsert'] = implode($this->MRekonbpkad->formInsert());
		$this->load->view('rekonsiliasi/rekonbpkad',$data);
	}
	public function cetak() {
		if ($this->input->server('REQUEST_METHOD') !== 'POST') {
			redirect('404');
		}
	
		$base = $this->Msetup->setup();
		$setpage = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
		$template = $this->Msetup->loadTemplate($setpage->title);
	
		$tglcetak = $this->input->post('tglcetak');
		$apbdp_checkbox = $this->input->post('apbdp_checkbox') ? true : false;
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		
		$tanda_tangan = $this->input->post('tanda_tangan');
		$tablenya = $this->MRekonbpkad->ambildata($tahun,$bulan);
		/* echo '<pre>';
		var_dump($tablenya);
		die();
		echo '</pre>'; */
		
		$data = [
			'footer' => $template['footer'],
			'title' => $setpage->title,
			'link' => $setpage->link,
			'topbar' => $template['topbar'],
			'sidebar' => $template['sidebar'],
		/* 	'format_bulan' => $format_bulan, */
            'apbdp_checkbox' => $apbdp_checkbox,
            'format_bulan' => strftime('%B', strtotime("$tahun-$bulan")),
			'format_tahun' => $tahun,
			'tglcetak' => $tglcetak,
			'tablenya' => $tablenya,
		];
	
		$tanda_tangan_data = $this->Msetup->get_tanda_tangan_tanpa_checbox($tanda_tangan);
		if ($tanda_tangan_data) {
			$data['tanda_tangan'] = $tanda_tangan_data;
		}
		$this->load->view('rekonsiliasi/printrekonbpkad', $data);

		/* ob_start();
		$html = $this->load->view('rekonsiliasi/printrekonbpkad', $data, true);
		ob_get_clean();
		
	
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('legal', 'landscape');
		$dompdf->render();
		$dompdf->stream("rekonbpkad.pdf", array("Attachment" => 0)); */
	}
	
	
}

?>
		
