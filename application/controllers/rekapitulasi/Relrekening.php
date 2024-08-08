<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

date_default_timezone_set("Asia/Jakarta");

class Relrekening extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('rekapitulasi/Mrelrekening');
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
		$data['forminsert'] = implode($this->Mrelrekening->formInsert());
		$this->load->view('rekapitulasi/relrekening',$data);
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
		$kdrekening = $this->input->post('kdrekening');
		function lastday($bulan, $tahun) {
			return date("Y-m-t", strtotime("$tahun-$bulan-01"));
		}
	
		$tglakhir = lastday($bulan, $tahun);
	
	
		if ($bulan == '01') {
			$bulan_sebelumnya = '12';
			$tahun_sebelumnya = $tahun - 1;
			$tglsebelum = lastday($bulan_sebelumnya, $tahun_sebelumnya);
			$total_sampai_bulan = 0; 
		} else {
			$bulan_sebelumnya = str_pad($bulan - 1, 2, '0', STR_PAD_LEFT);
			$tahun_sebelumnya = $tahun;
			$tglsebelum = lastday($bulan_sebelumnya, $tahun_sebelumnya);
	
			$tablenya_sampai_bulan = $this->Mrelrekening->ambildata($kdrekening,$bulan_sebelumnya, $tahun_sebelumnya);
			$total_sampai_bulan = 0;
			foreach ($tablenya_sampai_bulan as $row) {
				$total_sampai_bulan += $row['jumlah'];
			}
		}
	
		$tanda_tangan = $this->input->post('tanda_tangan');
		$tablenya = $this->Mrelrekening->ambildata($kdrekening,$bulan,$tahun, $tglsebelum ?? null, $tglakhir);
		function bulanIndonesia($bulan) {
			$bulanIndo = [
				'01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
				'05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
				'09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
			];
			return $bulanIndo[$bulan];
		}
	
		
		$format_bulan = bulanIndonesia($bulan);
		$tgl_cetak_format = date('d', strtotime($tglcetak)) . ' ' . $format_bulan . ' ' . date('Y', strtotime($tglcetak));
		
		
		$tglsebelum_format = isset($tglsebelum) ? date('d', strtotime($tglsebelum)) . ' ' . bulanIndonesia(date('m', strtotime($tglsebelum))) . ' ' . date('Y', strtotime($tglsebelum)) : 'N/A';
		$tglakhir_format = date('d', strtotime($tglakhir)) . ' ' . bulanIndonesia(date('m', strtotime($tglakhir))) . ' ' . date('Y', strtotime($tglakhir));

		$rekeningCumaIni = array(
			'4.1.1.01' => 'Pajak Hotel',
            '4.1.1.02' => 'Pajak Restoran',
            '4.1.1.03' => 'Pajak Hiburan',
            '4.1.1.04' => 'Pajak Reklame',
            '4.1.1.05' => 'Pajak Penerangan Jalan',
            '4.1.1.07' => 'Pajak Parkir',
            '4.1.1.08' => 'Pajak Air Tanah',
            '4.1.1.11' => 'Pajak Mineral Batuan Bukan Logam',
            '4.1.1.12' => 'Pajak Bumi dan Bangunan Pedesaan dan Perkotaan',
            '4.1.1.13' => 'Bea Perolehan Hak Atas Tanah dan Bangunan',
        );
		$nmrekening = isset($rekeningCumaIni[$kdrekening]) ? $rekeningCumaIni[$kdrekening] : 'Unknown';
		$data = [
			'footer' => $template['footer'],
			'title' => $setpage->title,
			'link' => $setpage->link,
			'topbar' => $template['topbar'],
			'sidebar' => $template['sidebar'],
		/* 	'format_bulan' => $format_bulan, */
			'format_tahun' => $tahun,
			'tglcetak' => $tglcetak,
			'tablenya' => $tablenya,
			'tglakhir_format' => $tglakhir_format,
			'tgl_cetak_format' => $tgl_cetak_format,
			'tglsebelum_format' => $tglsebelum_format,
			
			'total_sampai_bulan' => $total_sampai_bulan,
			'nmrekening' => $nmrekening ,
		];
	
		$tanda_tangan_data = $this->Msetup->get_tanda_tangan_tanpa_checbox($tanda_tangan);
		if ($tanda_tangan_data) {
			$data['tanda_tangan'] = $tanda_tangan_data;
		}
	
		/* $this->load->view('rekapitulasi/printrelrekening', $data); */

		ob_start();
		$html = $this->load->view('rekapitulasi/printrelrekening', $data, true);
		ob_get_clean();
		
	
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('legal', 'landscape');
		$dompdf->render();
		$dompdf->stream("realisasirek.pdf", array("Attachment" => 0));
	}
	
	
	
}

?>
		
