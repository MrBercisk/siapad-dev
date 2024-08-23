<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

date_default_timezone_set("Asia/Jakarta");

class Lrauptdbap extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('pembukuan/MLrauptdbap');
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
		$data['forminsert'] = implode($this->MLrauptdbap->formInsert());
		$this->load->view('pembukuan/lrauptdbap',$data);
	}
	public function cetak() {
		if ($this->input->server('REQUEST_METHOD') !== 'POST') {
			redirect('404');
		}
	
		$base = $this->Msetup->setup();
		$setpage = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
		$template = $this->Msetup->loadTemplate($setpage->title);
	
		$tglcetak = $this->input->post('tglcetak');
		$tahun = $this->input->post('tahun');
		$tglawal = $this->input->post('tglawal');	
	    $tglakhir = $this->input->post('tglakhir');	
		$apbdp_checkbox = $this->input->post('apbdp_checkbox') ? true : false;;
		$wp = $this->input->post('wp') ? true : false;
		
		$tanda_tangan = $this->input->post('tanda_tangan');
		$tablenya = $this->MLrauptdbap->ambildata($tglawal, $tglakhir, $tahun);
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
			'apbdp_checkbox' => $apbdp_checkbox,
			'wp' => $wp,
		/* 	'format_bulan' => $format_bulan, */
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir,
			'format_tahun' => $tahun,
			'tablenya' => $tablenya,
            'tgl_awal_format' =>strftime('%d %B %Y', strtotime($tglawal)),
            'tgl_akhir_format' =>strftime('%d %B %Y', strtotime($tglakhir)),
            'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tglcetak)),
		];
	
		$tanda_tangan_data = $this->Msetup->get_tanda_tangan_tanpa_checbox($tanda_tangan);
		if ($tanda_tangan_data) {
			$data['tanda_tangan'] = $tanda_tangan_data;
		}
		$this->load->view('pembukuan/printlrauptdbap', $data);
/* 
		ob_start();
		$html = $this->load->view('rekapitulasi/printpenddimuka', $data, true);
		ob_get_clean();
		
	
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('legal', 'landscape');
		$dompdf->render();
		$dompdf->stream("rekapbap.pdf", array("Attachment" => 0)); */
	}
	
	
	
}

?>
		
