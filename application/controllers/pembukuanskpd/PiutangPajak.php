<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
class PiutangPajak extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('pembukuanskpd/Mpiutangpajak');
    }
	public function index()
	{	
		$base 		= $this->Msetup->setup();
		$setpage	= $this->Msetup->get_title($base['halaman'].'/'.$base['fungsi']);
		$template 	= $this->Msetup->loadTemplate($setpage->title);
		$data = [
			'footer' => $template['footer'],
			'topbar' => $template['topbar'],
			'sidebar' => $template['sidebar'],
			'title' => $setpage->title,
			'link' => $setpage->link,
			'forminsert' =>  implode($this->Mpiutangpajak->formInsert())
	
		];
		$this->load->view('pembukuanskpd/lappiutangpajak',$data);
	}

	public function cetak() {
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
        redirect('404');
    }
    $base 			  = $this->Msetup->setup();
    $setpage 		  = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
    $template 		  = $this->Msetup->loadTemplate($setpage->title);

	$tanggal = $this->input->post('tgl_cetak');

	$tanda_tangan = $this->input->post('tanda_tangan');

	$tahun = $this->input->post('tahun');
	$bulan = $this->input->post('bulan');
	$iduptd = $this->input->post('iduptd');	
	$nomor = $this->input->post('nomor');	
   
    $bulan_arr = array(
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    );

    $bulan_php = array_search($bulan, $bulan_arr);
    if ($bulan_php === false) {
        $bulan_php = 'January'; 
    }


    $akhirtgl = date('d F Y', strtotime("last day of $bulan_php $tahun"));

	$tablenya = $this->Mpiutangpajak->ambildata($tanggal,$bulan, $tahun, $iduptd);
	/* echo '<pre>';
	var_dump($tablenya);
	echo '</pre>';
	die(); */


	$data = [
		'footer' => $template['footer'],
		'title' => $setpage->title,
		'link' => $setpage->link,
		'topbar' => $template['topbar'],
		'sidebar' => $template['sidebar'],
		'format_tahun' => $tahun,
        'nomor' => $nomor,
        'tablenya' => $tablenya,
        'akhirtgl' => $akhirtgl,
        'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tanggal))

	];
	
	$tanda_tangan_data = $this->Msetup->get_tanda_tangan_tanpa_checbox($tanda_tangan);
	if ($tanda_tangan_data) {
		$data['tanda_tangan'] = $tanda_tangan_data;
	}
 
    $uptd_data = $this->Msetup->get_uptd($iduptd);
	if ($uptd_data) {
		$data['iduptd'] = $uptd_data;
	}
	$this->load->view('pembukuanskpd/printlappiutangpjk', $data );
	
	/* ob_start();
	$html = $this->load->view('pembukuanskpd/printlappiutangpjk', $data, true);
	ob_get_clean();
	

	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->render();
	$dompdf->stream("wp_keterangan_khusus.pdf", array("Attachment" => 0)); */
}





}

?>
		
