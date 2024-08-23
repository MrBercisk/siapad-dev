<?php defined('BASEPATH') OR exit('No direct script access allowed');
class ApiSimpada extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('backend/Location');
		$this->load->model('transaksi/Mpend');
    }
    public function getapisimpada()
    {
        $nosptpd = empty($this->input->get('nosptpd')) ? 0 : $this->input->get('nosptpd');
        
        $urlApi = ENDPOINT_API_SIMPATDA . "?kodeBayar=$nosptpd";
    
        $data = [
            'nosptpd' => $nosptpd
        ];
        $payload = json_encode($data);
       
        $curl = curl_init($urlApi);
    
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
    
        $response = curl_exec($curl);
    
        if (curl_errno($curl)) {
            echo "Terjadi Kesalahan pada Curl: " . curl_error($curl);
        } else {
            $responseData = json_decode($response, true);
            $prettyResponse = json_encode($responseData, JSON_PRETTY_PRINT); 
            echo $prettyResponse;
        }
    
        // Menutup koneksi Curl
        curl_close($curl);
    }

  /*   public function fetchData() {
        $nosptpd = empty($this->input->get('nosptpd')) ? 0 : $this->input->get('nosptpd');

        $urlApi = ENDPOINT_API_SIMPATDA . "?kodeBayar=$nosptpd";
        $data = [
            'nosptpd' => $nosptpd
        ];
        $headers = array('Content-Type: application/json');

        $ch = curl_init($urlApi);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if ($result['code'] == "00") {
            $data_to_insert = array(
                'kodebayar' => $nosptpd,
                'nosptpd' => $result['data']['nosptpd'],
                'TglKirim' => $result['data']['TglKirim'],
                'TglTerbitSPTPD' => $result['data']['TglTerbitSPTPD'],
                'TglBayarSSPD' => $result['data']['TglBayarSSPD'],
                'NoSSPD' => $result['data']['NoSSPD'],
                'statusbayar' => $result['data']['statusbayar'],
                'totalbayar' => $result['data']['totalbayar'],
                'jumlahbayar' => $result['data']['jumlahbayar'],
                'denda' => $result['data']['denda'],
                'masapajak' => $result['data']['masapajak'],
                'tahunpajak' => $result['data']['tahunpajak'],
                'namaop' => $result['data']['namaop'],
            );

            $this->db->insert('trx_stsdetail_temp', $data_to_insert);
            $data['fetched_data'] = $data_to_insert;
            $this->load->view('transaksi/pnddaerah', $data);
        } else {
            // Handle error
            $this->load->view('transaksi/pnddaerah', array('error' => 'Failed to fetch data'));
        }
    } */
    public function getapisimpadasvr()
    {
        $nosptpd = empty($this->input->get('nosptpd')) ? 0 : $this->input->get('nosptpd');
        
        $urlApi = ENDPOINT_API_SIMPATDA_SVR . "?kodeBayar=$nosptpd";
    
        $data = [
            'nosptpd' => $nosptpd
        ];
        $payload = json_encode($data);
       
        $curl = curl_init($urlApi);
    
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
    
        $response = curl_exec($curl);
    
        if (curl_errno($curl)) {
            echo "Terjadi Kesalahan pada Curl: " . curl_error($curl);
        } else {
            $responseData = json_decode($response, true);
            $prettyResponse = json_encode($responseData, JSON_PRETTY_PRINT); 
            echo $prettyResponse;
        }
    
        // Menutup koneksi Curl
        curl_close($curl);
    }
    
    public function getapisimpadabphtb()
    {
       $noformulir = empty($this->input->get('noformulir')) ? 0 : $this->input->get('noformulir');
       $urlApi = ENDPOINT_API_SIMPATDA_BPHTB . $noformulir;

       $curl = curl_init($urlApi);
            
       curl_setopt($curl, CURLOPT_POST, true);
       curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($curl, CURLOPT_HTTPGET, true);

       $response = curl_exec($curl);  
       if (curl_errno($curl)) {
        echo "Terjadi Kesalahan pada Curl " . curl_error($curl);
        } else {
            echo $response;
        }

        curl_close($curl);
             
           
    }
   
    
          
}
?>
		