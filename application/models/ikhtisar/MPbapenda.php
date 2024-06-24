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

    public function get_saldo_awal($tanggal) {
        $tahun = date('Y', strtotime($tanggal));
    
        $this->db->select('COALESCE(SUM(a.total), 0) as saldoawal')
                 ->from('trx_stsdetail a')
                 ->join('trx_stsmaster b', 'b.id = a.idstsmaster')
                 ->join('trx_rapbd c', 'c.id = a.idrapbd')
                 ->join('mst_rekening d', 'd.id = c.idrekening')
                 ->where('b.tanggal <', $tanggal)
                 ->where('YEAR(b.tanggal)', $tahun)
                 ->where('(d.kdrekening LIKE \'4.1.1.01%\' OR d.kdrekening LIKE \'4.1.1.02%\' OR d.kdrekening LIKE \'4.1.1.03%\' OR d.kdrekening LIKE \'4.1.1.04%\'
                          OR d.kdrekening LIKE \'4.1.1.05%\' OR d.kdrekening LIKE \'4.1.1.07%\' OR d.kdrekening LIKE \'4.1.1.08%\' OR d.kdrekening LIKE \'4.1.1.11%\'
                          OR d.kdrekening LIKE \'4.1.1.12%\' OR d.kdrekening LIKE \'4.1.1.13%\' OR d.kdrekening LIKE \'4.1.1.08%\' OR d.kdrekening LIKE \'4.1.1.11%\')');
    
        $query = $this->db->get();
        $result = $query->row();
        return $result ? (float) $result->saldoawal : 0.00;
    }    
    
    public function get_data_hari_ini($tanggal) {
        $tahun = date('Y', strtotime($tanggal));
        $this->db->select(
           'idstsmaster, 
            nobukti as nomor, 
            idrapbd, 
            tglpajak, 
            blnpajak, 
            thnpajak, 
            trx_stsdetail.keterangan, 
            jumlah as pokokpajak, 
            prs_denda as persendenda, 
            nil_denda as jumlahdenda, 
            total as jumlahdibayar, 
            mst_rekening.nmrekening as namarekening, 
            mst_uptd.singkat as singkatanupt, 
            mst_wajibpajak.nama as namawp,
            mst_wajibpajak.idrekening ,
            trx_stsmaster.tahun,
            trx_stsmaster.tanggal,
            '
            );
        $this->db->from('trx_stsdetail');
        $this->db->join('trx_stsmaster', 'trx_stsdetail.idstsmaster = trx_stsmaster.id', 'inner');
        $this->db->join('trx_rapbd', 'trx_stsdetail.idrapbd = trx_rapbd.id', 'inner');
        $this->db->join('mst_rekening', 'trx_rapbd.idrekening = mst_rekening.id', 'inner');
        $this->db->join('mst_uptd', 'trx_stsdetail.iduptd = mst_uptd.id', 'left');
        $this->db->join('mst_wajibpajak', 'trx_stsdetail.idwp = mst_wajibpajak.id', 'left');
        $this->db->where('trx_stsmaster.tahun', $tahun);
        $this->db->where('trx_stsmaster.tanggal', $tanggal);

        $query = $this->db->get();
        $results = $query->result_array();

        return $results;
    }
  
    public function getdata($tanggal) {
        $tahun = date('Y', strtotime($tanggal));
        $this->db->select(
           'idstsmaster, 
            nobukti as nomor, 
            idrapbd, 
            tglpajak, 
            blnpajak, 
            thnpajak, 
            trx_stsdetail.keterangan, 
            jumlah as pokokpajak, 
            prs_denda as persendenda, 
            nil_denda as jumlahdenda, 
            total as jumlahdibayar, 
            mst_rekening.nmrekening as namarekening, 
            mst_uptd.singkat as singkatanupt, 
            mst_wajibpajak.nama as namawp,
            mst_wajibpajak.idrekening ,
            trx_stsmaster.tahun,
            trx_stsmaster.tanggal,
            '
            );
        $this->db->from('trx_stsdetail');
        $this->db->join('trx_stsmaster', 'trx_stsdetail.idstsmaster = trx_stsmaster.id', 'inner');
        $this->db->join('trx_rapbd', 'trx_stsdetail.idrapbd = trx_rapbd.id', 'inner');
        $this->db->join('mst_rekening', 'trx_rapbd.idrekening = mst_rekening.id', 'inner');
        $this->db->join('mst_uptd', 'trx_stsdetail.iduptd = mst_uptd.id', 'left');
        $this->db->join('mst_wajibpajak', 'trx_stsdetail.idwp = mst_wajibpajak.id', 'left');
        $this->db->where('trx_stsmaster.tahun', $tahun);
        $this->db->where('trx_stsmaster.tanggal', $tanggal);
        $this->db->where("(nobukti LIKE '%PBB%' OR nobukti LIKE '/pbb%')");

        $query = $this->db->get();
        $results = $query->result_array();

        return $results;
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
