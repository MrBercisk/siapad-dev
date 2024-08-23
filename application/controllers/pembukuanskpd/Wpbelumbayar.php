<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
class Wpbelumbayar extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('pembukuanskpd/Mwpbelumbayar');
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
			'forminsert' =>  implode($this->Mwpbelumbayar->formInsert())
	
		];
		$this->load->view('pembukuanskpd/wpblmbayar',$data);
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
    
        $tanda_tangan = $this->input->post('tanda_tangan');
        $tablenya = $this->Mwpbelumbayar->ambildatawp($bulan, $tahun);
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
            'format_bulan' => strftime('%B', strtotime("$tahun-$bulan")),
            'format_tahun' => $tahun,
            'tglcetak' => $tglcetak,
            'tablenya' => $tablenya,
            'tgl_cetak_format' => strftime('%d %B %Y', strtotime($tglcetak)),
           
        ];
    
	$tanda_tangan_data = $this->Msetup->get_tanda_tangan_tanpa_checbox($tanda_tangan);
	if ($tanda_tangan_data) {
		$data['tanda_tangan'] = $tanda_tangan_data;
	}
    $this->load->view('pembukuanskpd/printwpblmbayar', $data);
 
      /*   ob_start();
        $html = $this->load->view('pembukuanskpd/printwpblmbayar', $data, true);
        ob_get_clean();
    
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('legal', 'landscape');
        $dompdf->render();
        $dompdf->stream("beritaacarattdop.pdf", array("Attachment" => 0)); */
    
}


}

?>
		
