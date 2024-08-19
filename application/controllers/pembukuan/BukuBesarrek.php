<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

date_default_timezone_set("Asia/Jakarta");

class BukuBesarrek extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('pembukuan/Mbukubesarrek');
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
		$data['forminsert'] = implode($this->Mbukubesarrek->formInsert());
		$this->load->view('pembukuan/bukubesarrek',$data);
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
		$idrekheader = $this->input->post('idrekheader');
		$tahun = $this->input->post('tahun');
		$bulan = $this->input->post('bulan');
		$bulanakhir = $this->input->post('bulanakhir');
		
		$tanda_tangan = $this->input->post('tanda_tangan');
		$tablenya = $this->Mbukubesarrek->ambildata($iduptd,$idrekheader,$tahun,$bulan,$bulanakhir);
		$totals = $this->Mbukubesarrek->get_apbd_apbdp_total($tahun, $iduptd, $idrekheader);
		/* echo"<pre>";
		var_dump($tablenya);
		die(); */
		echo"</pre>";
		$data = [
			'footer' => $template['footer'],
			'title' => $setpage->title,
			'link' => $setpage->link,
			'topbar' => $template['topbar'],
			'sidebar' => $template['sidebar'],
            'format_bulan' => strftime('%B', strtotime("$tahun-$bulan")),
            'format_bulan_akhir' => strftime('%B', strtotime("$tahun-$bulanakhir")),
            'format_tahun' => $tahun,
            'idrekheader' => $idrekheader,
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
		$rek_data = $this->get_rekening($idrekheader);
		if ($rek_data) {
			$data['idrekheader'] = $rek_data;
		}
		$this->load->view('pembukuan/printbukubesarrek', $data);
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
		
