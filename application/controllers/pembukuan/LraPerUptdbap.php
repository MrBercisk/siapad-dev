<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

date_default_timezone_set("Asia/Jakarta");

class LraPerUptdbap extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('pembukuan/MLraperuptdbap');
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
		$data['forminsert'] = implode($this->MLraperuptdbap->formInsert());
		$this->load->view('pembukuan/lraperuptdbap',$data);
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
		$bulan = $this->input->post('bulan');
		$iduptd = $this->input->post('iduptd');
		$wp = $this->input->post('wp') ? true : false;
		$apbdp_checkbox = $this->input->post('apbdp_checkbox') ? true : false;
		$tanda_tangan = $this->input->post('tanda_tangan');
		$tablenya = $this->MLraperuptdbap->ambildata($iduptd,$bulan,$tahun);
	
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
            'format_bulan' => strftime('%B', strtotime("$tahun-$bulan")),
            'format_tahun' => $tahun,
            'apbdp_checkbox' => $apbdp_checkbox,
            'wp' => $wp,
			'tglcetak' => $tglcetak,
			'tablenya' => $tablenya,
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
	
	/* 	$this->load->view('pembukuan/printlraperuptd', $data); */

		ob_start();
		$html = $this->load->view('pembukuan/printlraperuptd', $data, true);
		ob_get_clean();
		
	
		$dompdf = new Dompdf();
		$dompdf->set_option('isRemoteEnabled', true);
		$dompdf->loadHtml($html);
		$dompdf->setPaper('legal', 'landscape');
		$dompdf->render();
		$dompdf->stream("lraperuptd.pdf", array("Attachment" => 0));
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
		
