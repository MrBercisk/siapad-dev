<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
setlocale(LC_ALL, 'id-ID', 'id_ID');
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
class BeritaAcarattd extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('pembukuanskpd/MBattd');
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
			'forminsert' =>  implode($this->MBattd->formInsert())
	
		];
		$this->load->view('pembukuanskpd/battd',$data);
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
    
        $tanda_tangan_1 = $this->input->post('tanda_tangan_1');
        $tanda_tangan_2 = $this->input->post('tanda_tangan_2');
        $tanda_tangan_3 = $this->input->post('tanda_tangan_3');
        $tanda_tangan_4 = $this->input->post('tanda_tangan_4');
        $tanda_tangan_5 = $this->input->post('tanda_tangan_5');
    
        $tablenya = $this->MBattd->cetaktotal($tahun, $bulan, $kdrekening);
    
        $rekeningCumaIni = array(
            '4.1.1.01' => 'Pajak Hotel',
            '4.1.1.02' => 'Pajak Restoran',
            '4.1.1.03' => 'Pajak Hiburan',
            '4.1.1.07' => 'Pajak Parkir',
            '4.1.1.08' => 'Pajak Air Tanah',
            '4.1.1.11' => 'Pajak Mineral Batuan Bukan Logam'
        );
    
        $nmrekening = isset($rekeningCumaIni[$kdrekening]) ? $rekeningCumaIni[$kdrekening] : 'Unknown';
        $tanda_tangan_data_1 = $this->Msetup->get_tanda_tangan_skpd($tanda_tangan_1);
        $tanda_tangan_data_2 = $this->Msetup->get_tanda_tangan_skpd($tanda_tangan_2);
        $tanda_tangan_data_3 = $this->Msetup->get_tanda_tangan_skpd($tanda_tangan_3);
        $tanda_tangan_data_4 = $this->Msetup->get_tanda_tangan_skpd($tanda_tangan_4);
        $tanda_tangan_data_5 = $this->Msetup->get_tanda_tangan_skpd($tanda_tangan_5);
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
            'nama_1' => isset($tanda_tangan_data_1['nama']) ? $tanda_tangan_data_1['nama'] : '',
            'nip_1' => isset($tanda_tangan_data_1['nip']) ? $tanda_tangan_data_1['nip'] : '',
            'nama_2' => isset($tanda_tangan_data_2['nama']) ? $tanda_tangan_data_2['nama'] : '',
            'nip_2' => isset($tanda_tangan_data_2['nip']) ? $tanda_tangan_data_2['nip'] : '',
            'nama_3' => isset($tanda_tangan_data_3['nama']) ? $tanda_tangan_data_3['nama'] : '',
            'nip_3' => isset($tanda_tangan_data_3['nip']) ? $tanda_tangan_data_3['nip'] : '',
            'nama_4' => isset($tanda_tangan_data_4['nama']) ? $tanda_tangan_data_4['nama'] : '',
            'nip_4' => isset($tanda_tangan_data_4['nip']) ? $tanda_tangan_data_4['nip'] : '',
            'nama_5' => isset($tanda_tangan_data_5['nama']) ? $tanda_tangan_data_5['nama'] : '',
            'nip_5' => isset($tanda_tangan_data_5['nip']) ? $tanda_tangan_data_5['nip'] : '',
        ];
    
        foreach ($data as $key => $value) {
            if (isset($value['nama'])) {
                $data[str_replace('tanda_tangan_', 'nama_', $key)] = $value['nama'];
            }
            if (isset($value['nip'])) {
                $data[str_replace('tanda_tangan_', 'nip_', $key)] = $value['nip'];
            }
        }
   
        ob_start();
        $html = $this->load->view('pembukuanskpd/beritaacarattd', $data, true);
        ob_get_clean();
    
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('legal', 'landscape');
        $dompdf->render();
        $dompdf->stream("beritaacarattd.pdf", array("Attachment" => 0));
    }
    

}

?>
		
