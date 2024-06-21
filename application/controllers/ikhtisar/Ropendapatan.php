<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
class Ropendapatan extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('ikhtisar/Mropendapatan');
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
			'forminsert' =>  implode($this->Mropendapatan->formInsert())
		];
		$this->load->view('ikhtisar/ropend',$data);
	}
	public function cetak() {
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
        redirect('404');
    }
    $base 			  = $this->Msetup->setup();
    $setpage 		  = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
    $template 		  = $this->Msetup->loadTemplate($setpage->title);
	
	$tgl_cetak = $this->input->post('tgl_cetak');
	$tanda_tangan = $this->input->post('tanda_tangan');
	$ttd_checkbox = $this->input->post('ttd_checkbox') ? true : false;
	
	$data = [
		'footer' => $template['footer'],
		'title' => $setpage->title,
		'link' => $setpage->link,
		'topbar' => $template['topbar'],
		'sidebar' => $template['sidebar'],
		'ttd_checkbox' => $ttd_checkbox,
		'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tgl_cetak)),
	];
	
	$data['tgl_cetak'] = $this->input->post('tgl_cetak');

	$bulan =  $this->input->post('bulan');
    $data['format_bulan'] = strftime('%B', strtotime($bulan));

	$data['tahun'] = $this->input->post('tahun');
	
	$rekening = $this->input->post('rekening');
	
	$tanda_tangan = $this->input->post('tanda_tangan');
	$ttd_checkbox = $this->input->post('ttd_checkbox') ? true : false;

	
    if($rekening){
        $rekdetail = $this->db
        ->select('id,kdrekening, nmrekening')
		->from('mst_rekening')
		->where('id', $rekening)
		->get()
		->row_array();
		$data['rekening'] = $rekdetail;
    }
	if($ttd_checkbox && $tanda_tangan){
		$ttddetail = $this->db
		->select('id, nama, nip, jabatan1, jabatan2')
		->from('mst_tandatangan')
		->where('id', $tanda_tangan)
		->get()
		->row_array();
		$data['tanda_tangan'] = $ttddetail;
	}
	$data['ttd_checkbox'] = $ttd_checkbox;

    ob_start();
    $this->load->view('ikhtisar/printlopen', $data);
    echo ob_get_clean();
}

}

?>
		
