<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

date_default_timezone_set("Asia/Jakarta");

class RekAnggaran extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('rekapitulasi/MRekAnggaran');
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
		$data['forminsert'] = implode($this->MRekAnggaran->formInsert());
		$this->load->view('rekapitulasi/anggaran',$data);
	}
	public function cetak() {
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
   
	$data['tgl_cetak'] = $this->input->post('tgl_cetak');
	
	$tanggal = $this->input->post('tanggal');
	$data['format_tanggal'] = strftime('%d %B %Y', strtotime($tanggal));
	
	$tanda_tangan = $this->input->post('tanda_tangan');

    $bulan =  $this->input->post('bulan');
    $data['format_bulan'] = strftime('%B', strtotime($bulan));
    
	$data['tahun'] = $this->input->post('tahun');
    $rekening = $this->input->post('rekening');
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
	/* $data['tablenya'] = $this->MLapBphtb->get_laporan_hari($tanggal); */
	
	ob_start();
	$html = $this->load->view('rekapitulasi/printanggaran', $data, true);
	ob_get_clean();

	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->render();
	$dompdf->stream("rekap_angggaran.pdf", array("Attachment" => 0));
}

}

?>
		
