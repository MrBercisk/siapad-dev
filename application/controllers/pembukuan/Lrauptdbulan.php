<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

date_default_timezone_set("Asia/Jakarta");

class Lrauptdbulan extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('pembukuan/MLrauptdbulan');
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
		$data['forminsert'] = implode($this->MLrauptdbulan->formInsert());
		$this->load->view('pembukuan/lrauptdbulan',$data);
	}
	public function cetak() {
		if ($this->input->server('REQUEST_METHOD') !== 'POST') {
			redirect('404');
		}
	
		$base = $this->Msetup->setup();
		$setpage = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
		$template = $this->Msetup->loadTemplate($setpage->title);
	
		$tglcetak = $this->input->post('tglcetak');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$apbdp_checkbox = $this->input->post('apbdp_checkbox') ? true : false;;
		$wp = $this->input->post('wp') ? true : false;
		
		$tanggal_awal = date('Y-m-01', strtotime("$tahun-$bulan-01")); // Hari pertama bulan
		$tanggal_akhir = date('Y-m-t', strtotime("$tahun-$bulan-01")); // Hari terakhir bulan

		$tanda_tangan = $this->input->post('tanda_tangan');
		$tablenya = $this->MLrauptdbulan->ambildata($bulan, $tahun);
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
			'format_tahun' => $tahun,
			'tablenya' => $tablenya,
            'tgl_awal_format' =>strftime('%d %B %Y', strtotime($tanggal_awal)),
            'tgl_akhir_format' =>strftime('%d %B %Y', strtotime($tanggal_akhir)),
            'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tglcetak)),
		];
	
		$tanda_tangan_data = $this->Msetup->get_tanda_tangan_tanpa_checbox($tanda_tangan);
		if ($tanda_tangan_data) {
			$data['tanda_tangan'] = $tanda_tangan_data;
		}
		$this->load->view('pembukuan/printlrauptdbulan', $data);
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
		
