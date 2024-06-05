<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ApiLradaerah extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('laporan/Mlradaerah');
    }
    public function fetch_data() {
        $tanggal 		 = $this->input->get('tanggal');
        $periode 		 = $this->input->get('periode');
        $test 			 = $this->Mlradaerah->get_data('2024-5-1', 'harian');
		foreach ($test as $row){
			$subarray[]  = htmlspecialchars($row['kdrekening']);
			$subarray[]  = htmlspecialchars($row['nmdinas']);
			$subarray[]  = number_format($row['apbd'], 2);
			$subarray[]  = number_format($row['apbdp'], 2);
			$subarray[]  = number_format($row['totlalu'], 2);
			$subarray[]  = number_format($row['totini'], 2);
			$subarray[]  = number_format($row['totlalu'] + $row['totini'], 2);
			$subarray[]  = ($row['apbd'] > 0) ? number_format((($row['totlalu'] + $row['totini']) / $row['apbd']) * 100, 2) : '0.00';
			$subarray[]  = number_format($row['apbd'] - ($row['totlalu'] + $row['totini']), 2);
			$subarray[]  = ($row['apbd'] > 0) ? number_format( ($row['apbd'] - ($row['totlalu'] + $row['totini']))/ $row['apbd'] * 100, 2) : '0.00';
			$subarray[]  = htmlspecialchars($row['kdrekening']);
			$subarray[]  = htmlspecialchars($row['kdrekening']);
			$data[] 	 = $subarray;
		}
        $recordsTotal 	 = count($data); 
        $recordsFiltered = count($data); 
        $output 		 = array(
            "draw" 		 		=> intval($this->input->post('draw')),
            "recordsTotal" 		=> $recordsTotal,
            "recordsFiltered" 	=> $recordsFiltered,
            "data" 				=> $data);
        echo json_encode($output);
    }
}
?>
