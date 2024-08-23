<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

date_default_timezone_set("Asia/Jakarta");

class BukuBesaruptd extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('pembukuan/MBukuBesar');
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
		$data['forminsert'] = implode($this->MBukuBesar->formInsert());
		$this->load->view('pembukuan/bukubesaruptd',$data);
	}
	public function cetak() {
		if ($this->input->server('REQUEST_METHOD') !== 'POST') {
			redirect('404');
		}
	
		$base = $this->Msetup->setup();
		$setpage = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
		$template = $this->Msetup->loadTemplate($setpage->title);
	
		$tglcetak = $this->input->post('tglcetak');
		$iduptd = $this->input->post('iduptd');
		$tahun = $this->input->post('tahun');
		$bulan = $this->input->post('bulan');
		
		$tanda_tangan = $this->input->post('tanda_tangan');
		$tablenya = $this->MBukuBesar->ambildata($iduptd,$tahun,$bulan);
		$totals = $this->MBukuBesar->get_apbd_apbdp_total($tahun, $iduptd);
		/* echo"<pre>";
		var_dump($tablenya);
		die();
		echo"</pre>"; */
		$data = [
			'footer' => $template['footer'],
			'title' => $setpage->title,
			'link' => $setpage->link,
			'topbar' => $template['topbar'],
			'sidebar' => $template['sidebar'],
            'format_bulan' => strftime('%B', strtotime("$tahun-$bulan")),
            'format_tahun' => $tahun,
			'tglcetak' => $tglcetak,
			'tablenya' => $tablenya,
            'total_apbd' => $totals->total_apbd,
		    'total_apbdp' => $totals->total_apbdp,
			'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tglcetak)),
			
		];
	
		$tanda_tangan_data = $this->Msetup->get_tanda_tangan_tanpa_checbox($tanda_tangan);
		if ($tanda_tangan_data) {
			$data['tanda_tangan'] = $tanda_tangan_data;
		}
		$uptd_data = $this->Msetup->get_uptd($iduptd);
		if ($uptd_data) {
			$data['iduptd'] = $uptd_data;
		}
		$this->load->view('pembukuan/printbukubesar', $data);
/* 
		ob_start();
		$html = $this->load->view('rekapitulasi/printrekapbap', $data, true);
		ob_get_clean();
		
	
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('legal', 'landscape');
		$dompdf->render();
		$dompdf->stream("rekapbap.pdf", array("Attachment" => 0)); */
	}
	
	
	
}

?>
		
