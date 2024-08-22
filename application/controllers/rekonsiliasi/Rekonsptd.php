<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

date_default_timezone_set("Asia/Jakarta");

class Rekonsptd extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('rekonsiliasi/MRekonsptd');
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
		$data['forminsert'] = implode($this->MRekonsptd->formInsert());
		$this->load->view('rekonsiliasi/rekonsptd',$data);
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
		$bulanakhir = $this->input->post('bulanakhir');

		$tanda_tangan_1 = $this->input->post('tanda_tangan_1');
        $tanda_tangan_2 = $this->input->post('tanda_tangan_2');

		/* $tablenya = $this->MRekonsptd->ambildata($tahun,$bulan,$bulanakhir); */
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
            'format_bulan' => strftime('%B', strtotime("$tahun-$bulan")),
            'format_bulan_akhir' => strftime('%B', strtotime("$tahun-$bulanakhir")),
			'format_tahun' => $tahun,
			'tglcetak' => $tglcetak,
			/* 'tablenya' => $tablenya, */
			'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tglcetak)),
		];
	
	 	$tanda_tangan_data_1 = $this->Msetup->get_tanda_tangan_skpd_1($tanda_tangan_1);
        $tanda_tangan_data_2 = $this->Msetup->get_tanda_tangan_skpd_2($tanda_tangan_2);
    
        if ($tanda_tangan_data_1) {
            $data['tanda_tangan_1'] = $tanda_tangan_data_1;
        }
        if ($tanda_tangan_data_2) {
            $data['tanda_tangan_2'] = $tanda_tangan_data_2;
        }
 
		$this->load->view('rekonsiliasi/printrekonsptd', $data);

		/* ob_start();
		$html = $this->load->view('rekonsiliasi/printrekonbpkad', $data, true);
		ob_get_clean();
		
	
		$dompdf = new Dompdf();
		$dompdf->set_option('isRemoteEnabled', true);
		$dompdf->loadHtml($html);
		$dompdf->setPaper('legal', 'landscape');
		$dompdf->render();
		$dompdf->stream("rekonbpkad.pdf", array("Attachment" => 0)); */
	}
	
	
}

?>
		
