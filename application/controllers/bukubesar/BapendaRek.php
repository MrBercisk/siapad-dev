<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
class BapendaRek extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('bukubesar/MBapendarek');
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
			'forminsert' =>  implode($this->MBapendarek->formInsert())
	
		];
		$this->load->view('bukubesar/bapendarek',$data);
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

	$tahun = $this->input->post('tahun');
	$kdrekening = $this->input->post('kdrekening');

	$tablenya =  $this->MBapendarek->ambildata($kdrekening, $tahun);
	/* echo '<pre>';
	var_dump($tablenya);
	echo '</pre>';
	die();
 */
	$totals = $this->MBapendarek->get_apbd_apbdp_total($tahun, $kdrekening);

	$data = [
		'footer' => $template['footer'],
		'title' => $setpage->title,
		'link' => $setpage->link,
		'topbar' => $template['topbar'],
		'sidebar' => $template['sidebar'],
		'ttd_checkbox' => $ttd_checkbox,
		'format_tahun' => $tahun,
		'tablenya' => $tablenya,
        'total_apbd' => $totals->total_apbd,
		'total_apbdp' => $totals->total_apbdp,
        'tgl_cetak_format' =>strftime('%d %B %Y', strtotime($tgl_cetak)),

	];
	
	$tanda_tangan_data = $this->Msetup->get_tanda_tangan($ttd_checkbox, $tanda_tangan);

	if ($tanda_tangan_data) {
		$data['tanda_tangan'] = $tanda_tangan_data;
	}
    $rek_data = $this->Msetup->get_rekening($kdrekening);
	if ($rek_data) {
		$data['kdrekening'] = $rek_data;
	}
	$this->load->view('bukubesar/printbaprek', $data );
	
	/* ob_start();
	$html = $this->load->view('bukubesar/printbaprek', $data, true);
	ob_get_clean();
	

	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper('legal', 'landscape');
	$dompdf->render();
	$dompdf->stream("bb_bapenda_rek.pdf", array("Attachment" => 0)); */
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
		
