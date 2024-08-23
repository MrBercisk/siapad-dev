<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

date_default_timezone_set("Asia/Jakarta");

class Bukuuptd extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('pembukuan/MBukuuptdbap');
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
		$data['forminsert'] = implode($this->MBukuuptdbap->formInsert());
		$this->load->view('pembukuan/bukuuptdbap',$data);
	}
	public function cetak() {
		if ($this->input->server('REQUEST_METHOD') !== 'POST') {
			redirect('404');
		}
	
		$base = $this->Msetup->setup();
		$setpage = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
		$template = $this->Msetup->loadTemplate($setpage->title);
	
		$tglcetak = $this->input->post('tglcetak');
		$tanggal = $this->input->post('tanggal');
		$iduptd = $this->input->post('iduptd');
		
		$tanda_tangan = $this->input->post('tanda_tangan');
		$tablenya = $this->MBukuuptdbap->ambildata($tanggal,$iduptd);
		
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
			'tglcetak' => $tglcetak,
			'tablenya' => $tablenya,
			'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tglcetak)),
			'tgl_format' =>strftime('%d %B %Y', strtotime($tanggal)),
		];
	
		$tanda_tangan_data = $this->Msetup->get_tanda_tangan_tanpa_checbox($tanda_tangan);
		if ($tanda_tangan_data) {
			$data['tanda_tangan'] = $tanda_tangan_data;
		}
		$uptd_data = $this->Msetup->get_uptd($iduptd);
		if ($uptd_data) {
			$data['iduptd'] = $uptd_data;
		}
		$this->load->view('pembukuan/printbukuuptdbap', $data);
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
		
