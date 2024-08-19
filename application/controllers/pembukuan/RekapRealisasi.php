<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

date_default_timezone_set("Asia/Jakarta");

class RekapRealisasi extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('pembukuan/MRekaprealisasi');
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
		$data['forminsert'] = implode($this->MRekaprealisasi->formInsert());
		$this->load->view('pembukuan/rekaprealisasi',$data);
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
		/* $nobaris_checkbox = $this->input->post('nobaris_checkbox') ? true : false; */
		$tanda_tangan = $this->input->post('tanda_tangan');
		$tablenya = $this->MRekaprealisasi->ambildata($tahun);
	
		/* echo"<pre>";
		var_dump($iduptd,$tahun,$bulan,$wp,$apbdp_checkbox);
		die();
		echo"</pre>"; */
		$data = [
			'footer' => $template['footer'],
			'title' => $setpage->title,
			'link' => $setpage->link,
			'topbar' => $template['topbar'],
			'sidebar' => $template['sidebar'],
            'format_tahun' => $tahun,
            /* 'nobaris_checkbox' => $nobaris_checkbox, */
			'tglcetak' => $tglcetak,
			'tablenya' => $tablenya,
			'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tglcetak)),
			
		];
	
		$tanda_tangan_data = $this->Msetup->get_tanda_tangan_tanpa_checbox($tanda_tangan);
		if ($tanda_tangan_data) {
			$data['tanda_tangan'] = $tanda_tangan_data;
		}

	
		$this->load->view('pembukuan/printrekaprealisasi', $data);
/* 
		ob_start();
		$html = $this->load->view('pembukuan/printbukubesarrek', $data, true);
		ob_get_clean();
		
	
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('legal', 'landscape');
		$dompdf->render();
		$dompdf->stream("rekapbap.pdf", array("Attachment" => 0)); */
	}
	
	public function get_rekening($idrekheader) {
		if($idrekheader){
			$rekdetail = $this->db
				->select('id,idheader,kdrekening, nmrekening')
				->from('mst_rekening')
				->where('id', $idrekheader)
				->get()
				->row_array();
			return $rekdetail;
		}
	}
	
}

?>
		
