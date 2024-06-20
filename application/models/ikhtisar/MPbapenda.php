<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MPbapenda extends CI_Model {
    public function roman($num)
    {
        $n = intval($num);
        $result = '';
        $numerals = array(
            'M'  => 1000,
            'CM' => 900,
            'D'  => 500,
            'CD' => 400,
            'C'  => 100,
            'XC' => 90,
            'L'  => 50,
            'XL' => 40,
            'X'  => 10,
            'IX' => 9,
            'V'  => 5,
            'IV' => 4,
            'I'  => 1
        );
    
        foreach ($numerals as $key => $value) {
            $matches = intval($n / $value);
            $result .= str_repeat($key, $matches);
            $n = $n % $value;
        }
    
        return $result;
    }
    public function get_data($tanggal) {
        $date_format = explode('-', $tanggal);
        $tahun = $date_format[0];
        $bulan = $date_format[1];
        $hari = $date_format[2];

        $this->db->select(
           'idstsmaster, 
            nobukti as nomor, 
            idrapbd, 
            tglpajak, 
            blnpajak, 
            thnpajak, 
            keterangan, 
            jumlah as pokokpajak, 
            prs_denda as persendenda, 
            nil_denda as jumlahdenda, 
            total as jumlahdibayar, 
            mst_rekening.nmrekening as namarekening, 
            mst_uptd.singkat as singkatanupt, 
            mst_wajibpajak.nama as namawp,
            mst_wajibpajak.idrekening 
            ');
        $this->db->from('trx_stsdetail');
        $this->db->join('trx_rapbd', 'trx_stsdetail.idrapbd = trx_rapbd.id', 'left');
        $this->db->join('mst_rekening', 'trx_rapbd.idrekening = mst_rekening.id', 'left');
        $this->db->join('mst_uptd', 'trx_stsdetail.iduptd = mst_uptd.id', 'left');
        $this->db->join('mst_wajibpajak', 'trx_stsdetail.idwp = mst_wajibpajak.id', 'left');
        $this->db->where('tglpajak', $hari); 
        $this->db->where('blnpajak', $bulan); 
        $this->db->where('thnpajak', $tahun);
        $this->db->order_by('mst_rekening.id');
        $query = $this->db->get();
        $results = $query->result_array();
        
        $bulan_map = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        foreach ($results as &$row) {
            if (isset($bulan_map[$row['blnpajak']])) {
                $row['blnpajak'] = $bulan_map[$row['blnpajak']];
            }
        }

        return $results;
    }
    public function get_saldo($tanggal) {
        $date_format = explode('-', $tanggal);
        $tahun = $date_format[0];
        $hari = $date_format[2];
        
        $this->db->select_sum('total', 'saldo');
        $this->db->where('thnpajak', $tahun);
        $this->db->where('tglpajak <=', $hari);
        $query = $this->db->get('trx_stsdetail');
        
        $result = $query->row();
        return $result->saldo;
    }
    
    public function get_data_bapenda($tanggal) {
        $query = $this->db->query("CALL spRptIkhtisarBPPRD(?)", array($tanggal));
        return $query->result_array();
    }
	public function formInsert() {
        $ttddata = $this->db
        ->select('mst_tandatangan.id, mst_tandatangan.nip, mst_tandatangan.nama, mst_tandatangan.jabatan1, mst_tandatangan.jabatan2')
        ->from('mst_tandatangan')
        ->get()
        ->result();
        $opsittd = '<option></option>';
        foreach ($ttddata as $ttd) {
            $opsittd .= '<option value="'.$ttd->id.'">'.$ttd->nama.'</option>';
        }
    $form[] = '
    <div class="card">
        <div class="card-body">
            <form action="' . site_url('ikhtisar/Pdbapenda/cetak') . '" class="form-row" method="post">
            <div class="col-md-12 border-bottom border-secondary" style="border-bottom: 2px solid #dee2e6 !important;">
                    <h5>Parameters</h5>
            </div>
			  <div class="col-md-10">
                    <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tanggal">Tanggal:</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>
                    </div>
    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tgl_cetak">Tgl. Cetak:</label>
                            <input type="date" class="form-control" id="tgl_cetak" name="tgl_cetak" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ttd">Tanda Tangan:</label>
                              <select id="tanda_tangan" name="tanda_tangan" class="form-control select2" data-placeholder="Pilih Tanda Tangan" style="width: 100%;">
                                      '.$opsittd.'
                              </select>
                        </div>
                    </div>
    
                    </div>
                </div>
    
                    <div class="col-md-1">
                        <label class="form-check-label" for="ttd">Penandatangan</label>
                        <div class="form-check">
                           <input type="checkbox" class="form-check-input" id="ttd_checkbox" name="ttd_checkbox" checked>
                        <label class="form-check-label" for="ttd">Ttd</label>
                        <div class="button-group">
                            <button type="submit" class="btn btn-primary">Cetak Laporan</button>
                        </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>';
    return $form;
}


}
