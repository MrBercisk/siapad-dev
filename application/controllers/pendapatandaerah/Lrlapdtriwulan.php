<?php defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;

setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

class Lrlapdtriwulan extends CI_Controller
{
	private $data = [];
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pendapatandaerah/Mrlapdtriwulan');
	}
	public function index()
	{

		$Jssetup	= $this->Jssetup;
		$base 		= $this->Msetup->setup();
		$setpage	= $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
		$template 	= $this->Msetup->loadTemplate($setpage->title);
		// $data['tahun']     = $this->Msetup->ambilTahun();

		$data['footer']		= $template['footer'];
		$data['title'] 		= $setpage->title;
		$data['link'] 		= $setpage->link;
		$data['topbar'] 	= $template['topbar'];
		$data['modalEdit'] 	= [];
		$data['modalDelete'] = [];
		$data['sidebar'] 	= $template['sidebar'];
		$data['jstable']	= NULL;
		$data['jsedit']		= NULL;
		$data['jsdelete']	= NULL;
		$data['datepicker']	= $this->Mrlapdtriwulan->datepicker();
		$data['forminsert'] = implode($this->Mrlapdtriwulan->formInsert());
		$data['escaped_link'] = htmlspecialchars("pendapatandaerah/lrlapdbtriwulan/detailTtd", ENT_QUOTES, 'UTF-8');

		$this->load->view('pendapatandaerah/rlapdtriwulan', $data);
	}
	public function cetak()
	{
		// var_dump($this->input->post());
		// die;
		if ($this->input->server('REQUEST_METHOD') !== 'POST') {
			redirect('404');
		}

		$Jssetup 		  = $this->Jssetup;
		$base 			  = $this->Msetup->setup();
		$setpage 		  = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
		$template 		  = $this->Msetup->loadTemplate($setpage->title);
		$data['footer']   = $template['footer'];
		$data['title'] 	  = $setpage->title;
		$data['link'] 	  = $setpage->link;
		$data['topbar']   = $template['topbar'];
		$data['sidebar']  = $template['sidebar'];
		$data['jstable']  = '';
		$data['ttd']	  = $this->Msetup->detailTtds($this->input->post('namattd'));
		$tahun = $this->input->post('tahun');
		$bulan = (int)$this->input->post('bulan');
		$data['tablenya'] = $this->Mrlapdtriwulan->get_data_triwulan($tahun, $bulan);

		// PR pembuatan model query untuk permodul antara lain mecah prosedure
		ob_start();
		$html = $this->load->view('pendapatandaerah/printlaptriwulan', $data, true);
		echo ob_get_clean();

		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper("A4", 'landscape');
		$dompdf->render();
		$dompdf->stream("lratriwulan.pdf", array("Attachment" => 0));
	}

	public function detailTtd()
	{
		$nama = $_POST["nama"];
		$detail = $this->Msetup->detailTtds($nama);
		if (!empty($detail)) {
			$response = array(
				'success' => true,
				'data' => $detail
			);
		} else {
			$response = array(
				'success' => false,
				'message' => 'Data tidak ditemukan.'
			);
		}

		echo json_encode($response);;
	}
}
