<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
class SptpdBlmbayar extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('pembukuan/MSptpdblmbayar');
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
			'forminsert' =>  implode($this->MSptpdblmbayar->formInsert())
	
		];
		$this->load->view('pembukuan/sptpdblmbayar',$data);
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

        $tablenya = $this->MSptpdblmbayar->cetaktotal($tahun, $bulan, $kdrekening);
    
        $rekeningCumaIni = array(
            '4.1.1.01' => 'Pajak Hotel',
            '4.1.1.02' => 'Pajak Restoran',
            '4.1.1.03' => 'Pajak Hiburan',
            '4.1.1.07' => 'Pajak Parkir',
            '4.1.1.08' => 'Pajak Air Tanah',
            '4.1.1.11' => 'Pajak Mineral Batuan Bukan Logam'
        );
    
        $nmrekening = isset($rekeningCumaIni[$kdrekening]) ? $rekeningCumaIni[$kdrekening] : 'Unknown';
      
        $data = [
            'footer' => $template['footer'],
            'title' => $setpage->title,
            'link' => $setpage->link,
            'topbar' => $template['topbar'],
            'sidebar' => $template['sidebar'],
            'format_bulan' => strftime('%B', strtotime("$tahun-$bulan")),
            'format_tahun' => $tahun,
            'tglcetak' => $tglcetak,
            'tablenya' => $tablenya,
            'tgl_cetak_format' => strftime('%d %B %Y', strtotime($tglcetak)),
            'nmrekening' => $nmrekening ,
        ];
    
   
        ob_start();
        $html = $this->load->view('pembukuan/printsptpdblmbyr', $data, true);
        ob_get_clean();
    
        $dompdf = new Dompdf();
        $dompdf->set_option('isRemoteEnabled', true);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('legal', 'landscape');
        $dompdf->render();
        $dompdf->stream("sptpdblmbayar.pdf", array("Attachment" => 0));
    }
    

}

?>
		
