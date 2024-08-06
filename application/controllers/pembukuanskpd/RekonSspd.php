<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
class RekonSspd extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('pembukuanskpd/Mrekonsspd');
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
			'forminsert' =>  implode($this->Mrekonsspd->formInsert())
	
		];
		$this->load->view('pembukuanskpd/laprekonsspd',$data);
	}

	public function cetak() {
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
        redirect('404');
    }
    $base 			  = $this->Msetup->setup();
    $setpage 		  = $this->Msetup->get_title($base['halaman'] . '/' . $base['fungsi']);
    $template 		  = $this->Msetup->loadTemplate($setpage->title);

	$tglcetak = $this->input->post('tglcetak');
    

	$tahun = $this->input->post('tahun');
	$bulan = $this->input->post('bulan');
	$tahunsawal = $this->input->post('tahunsawal');
	$tahunsakhir = $this->input->post('tahunsakhir');
	$kdrekening = $this->input->post('kdrekening');
    $tgl = $this->input->post('cektgl') ? 'true' : 'false';

    if ($kdrekening === '4.1.1.04'){
       $tablenya =  $this->Mrekonsspd->ambildataskpdnrek($tahun,$bulan,$kdrekening,$tahunsawal,$tahunsakhir,$tgl);
    } else{
        $tablenya =  $this->Mrekonsspd->ambildataskpdn($tahun,$bulan,$kdrekening,$tahunsawal,$tahunsakhir,$tgl);
    }

    $tanda_tangan_1 = $this->input->post('tanda_tangan_1');
    $tanda_tangan_2 = $this->input->post('tanda_tangan_2');
    
	/* echo '<pre>';

	var_dump($tablenya);
	echo '</pre>';
	die(); */

	/* $totals = $this->Mlrabapop->get_apbd_apbdp_total($tahun);
 */
	$data = [
		'footer' => $template['footer'],
		'title' => $setpage->title,
		'link' => $setpage->link,
		'topbar' => $template['topbar'],
		'sidebar' => $template['sidebar'],
        'format_bulan' => strftime('%B', strtotime("$tahun-$bulan")),
		'format_tahun' => $tahun,
		'tablenya' => $tablenya,
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

    $rek_data = $this->Msetup->get_rekening($kdrekening);
	if ($rek_data) {
		$data['kdrekening'] = $rek_data;
	}
   /*  $this->load->view('pembukuanskpd/printrekonsspd', $data); */
	/* $html = $this->load->view('bukubesar/printlrabaprek', $data, true); */
/* 
    $this->output
        ->set_content_type('application/pdf')
        ->set_output($html)
        ->set_header('Content-Disposition: inline; filename="lra_bapenda_rekening.pdf"'); */
	
	ob_start();
	$html = $this->load->view('pembukuanskpd/printrekonsspd', $data, true);
	ob_get_clean();
	

	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper('legal', 'landscape');
	$dompdf->render();
	$dompdf->stream("laprekonsspd.pdf", array("Attachment" => 0));
}

public function getWajibPajakByRekening()
{
    $id= $this->input->get('id');
    
    if (!$id) {
        echo json_encode(['tidak ada datanya']);
        return;
    }

    $this->load->model('master/Mwp');
    $data = $this->Mwp->getWajibPajakByRekening($id);

    echo json_encode($data);
}

/* public function getWajibPajakByRekening() {
    $idrekening = $this->input->post('id');

    if ($idrekening) {
        $this->db->select('id, nama');
        $this->db->from('mst_wajibpajak');
        $this->db->join('mst_rekening','mst_rekening.id = mst_Wajibpajak.idrekening');
        $this->db->where('idrekening', $idrekening); 
        $query = $this->db->get();
        
        $data = $query->result();
        echo json_encode($data);
    } else {
        echo json_encode([]);
    }
} */

}

?>
		
