<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ApiReklame extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('backend/Location');
        $this->load->model('transaksi/Mbyrskpd');
    }

    public function getReklame() {
        $kodebayar = $this->input->get('kodebayar') ?: 0;
    
        $this->db->select('
            a.id as idskpd, 
            a.idwp, 
            a.tanggal, 
            a.nomor, 
            a.teks, 
            a.blnpajak, 
            a.thnpajak, 
            a.jumlah, 
            a.bunga, 
            a.tglbayar, 
            a.total, 
            a.keterangan, 
            a.isbayar, 
            b.nop, 
            b.npwpd, 
            b.nama as nmwp, 
            b.alamat, 
            a.kodebayar'
        );
        $this->db->from('trx_skpdreklame a');
        $this->db->join('mst_wajibpajak b', 'a.idwp = b.id', 'left'); 
        $this->db->where('a.kodebayar', $kodebayar);
        $rows = $this->db->get()->result();
    
        if (empty($rows)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Cek kode bayar, data reklame tidak ada'
                ]));
        }
    
        $result = [];
        foreach ($rows as $row) {
            $encrypted_idskpd = base64_encode($row->idskpd); 
            $encrypted_idwp = base64_encode($row->idwp);
    
            $result[] = array(
                "idskpd" => $encrypted_idskpd,
                "idwp" => $encrypted_idwp,
                "tglskpd" => $row->tanggal,
                "noskpd" => $row->nomor,
                "teks" => $row->teks,
                "bulan" => $row->blnpajak,
                "tahun" => $row->thnpajak,
                "jumlah" => $row->jumlah,
                "bunga" => $row->bunga,
                "tglbayar" => $row->tglbayar,
                "total" => $row->total,
                "keterangan" => $row->keterangan,
                "statusbayar" => $row->isbayar,
                "nop" => $row->nop,
                "npwpd" => $row->npwpd,
                "namawp" => $row->nmwp,
                "alamatwp" => $row->alamat,
                "kodebayar" => $row->kodebayar
            );
        }
    
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'success' => true,
                'data' => $result
            ]));
    }
    
}
?>

