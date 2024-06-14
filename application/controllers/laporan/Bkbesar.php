<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
class Bkbesar extends CI_Controller {
	private $data = [];
	public function __construct() {
        parent::__construct();
		$this->load->model('laporan/MBkBesar');
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
		$data['jstable']	= NULL;
	 	$data['jsedit']		= NULL;
	 	$data['jsdelete']	= NULL;
		$data['forminsert'] = implode($this->MBkBesar->formInsert());
		$this->load->view('laporan/bkbesar',$data);
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
    $data['jstable']  = ''; // $Jssetup->jsDatatable2('#ftf','Api/ApiLradaerah/fetch_data');
	
	$data['tgl_cetak'] = $this->input->post('tgl_cetak');

	$tanggal = $this->input->post('tanggal');
	$data['format_tanggal'] = strftime('%d %B %Y', strtotime($tanggal));

	$tanda_tangan = $this->input->post('tanda_tangan');
	$ttd_checkbox = $this->input->post('ttd_checkbox') ? true : false;

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

	$data['tablenya'] = $this->MBkBesar->get_bk_besar($tanggal);
	
	ob_start();
	$html = $this->load->view('laporan/printbkbesar', $data, true);
	ob_get_clean();
	

	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->render();
	$dompdf->stream("laporan_buku_besar.pdf", array("Attachment" => 0));
}

}

?>
		
