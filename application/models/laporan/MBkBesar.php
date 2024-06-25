<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MBkBesar extends CI_Model {
   
    public function get_saldo_awal($tanggal) {
        $tahun = date('Y', strtotime($tanggal));
        
        $this->db->select("COALESCE(SUM(jumlah), 0) as saldoawal")
                 ->from("(
                    SELECT SUM(b.total) AS jumlah
                    FROM trx_stsmaster a
                    INNER JOIN trx_stsdetail b ON b.idstsmaster = a.id
                    INNER JOIN trx_rapbd c ON c.id = b.idrapbd
                    INNER JOIN mst_rekening d ON d.id = c.idrekening
                    WHERE a.tanggal < '$tanggal' 
                        AND YEAR(a.tanggal) = $tahun
                        AND NOT d.kdrekening LIKE '4.1.4.18%'
                        AND NOT d.kdrekening LIKE '4.3.1.01.01.02%'
                        AND NOT (d.kdrekening LIKE '4.3.1.01.06%' OR d.kdrekening LIKE '4.1.4.19.01%')
                        AND a.isnonkas = 0
                 ) as saldoawal");
    
        $query = $this->db->get();
        $result = $query->row();
        return $result ? (float) $result->saldoawal : 0.00;
    }
    public function get_pembiayaan($tanggal) {
        $tahun = date('Y', strtotime($tanggal));
        
        $this->db->select("IFNULL(SUM(IFNULL(b.total, 0)), 0) AS jumlah, d.nmrekening")
                 ->from("trx_stsmaster a")
                 ->join("trx_stsdetail b", "b.idstsmaster = a.id")
                 ->join("trx_rapbd c", "c.id = b.idrapbd")
                 ->join("mst_rekening d", "d.id = c.idrekening")
                 ->where("a.tanggal <=", $tanggal)
                 ->where("YEAR(a.tanggal) =", $tahun)
                 ->where("a.isnonkas", 1)
                 ->where("d.kdrekening NOT LIKE '4.1.4.18%'")
                 ->where("d.kdrekening NOT LIKE '4.3.1.01.01.02%'")
                 ->where("(d.kdrekening NOT LIKE '4.3.1.01.06%' AND d.kdrekening NOT LIKE '4.1.4.19.01%')");
    
        $query = $this->db->get();
        $result = $query->row();
        return $result ? (float) $result->jumlah : 0.00;
    }
    /* Get data blud keterangan */
    public function get_blud($tanggal)
    {
        $tahun = date('Y', strtotime($tanggal));
        $this->db->select("IFNULL(SUM(IFNULL(b.total, 0)), 0) AS jumlah")
        ->from("trx_stsmaster a")
        ->join("trx_stsdetail b", "b.idstsmaster = a.id")
        ->join("trx_rapbd c", "c.id = b.idrapbd")
        ->join("mst_rekening d", "d.id = c.idrekening")
        ->where("a.tanggal <=", $tanggal)
        ->where("YEAR(a.tanggal) =", $tahun)
        ->where("d.kdrekening LIKE '4.1.4.16%'");
        
        $query = $this->db->get();  
        $result = $query->row();
        return $result ? (float) $result->jumlah : 0.00;
    }
    /* Get data bos keterangan */
    public function get_bos($tanggal)
    {
        $tahun = date('Y', strtotime($tanggal));
        $this->db->select("IFNULL(SUM(IFNULL(b.total, 0)), 0) AS jumlah")
        ->from("trx_stsmaster a")
        ->join("trx_stsdetail b", "b.idstsmaster = a.id")
        ->join("trx_rapbd c", "c.id = b.idrapbd")
        ->join("mst_rekening d", "d.id = c.idrekening")
        ->where("a.tanggal <=", $tanggal)
        ->where("YEAR(a.tanggal) =", $tahun)
        ->where("d.kdrekening LIKE '4.3.1.01.06%' OR d.kdrekening LIKE '4.1.4.19.01%'");
        
        $query = $this->db->get();  
        $result = $query->row();
        return $result ? (float) $result->jumlah : 0.00;
    }
    /* Get data rilau keterangan */
    public function get_rilau($tanggal)
    {
        $tahun = date('Y', strtotime($tanggal));
        $this->db->select("IFNULL(SUM(IFNULL(b.total, 0)), 0) AS jumlah")
        ->from("trx_stsmaster a")
        ->join("trx_stsdetail b", "b.idstsmaster = a.id")
        ->join("trx_rapbd c", "c.id = b.idrapbd")
        ->join("mst_rekening d", "d.id = c.idrekening")
        ->where("a.tanggal <=", $tanggal)
        ->where("YEAR(a.tanggal) =", $tahun)
        ->where("d.kdrekening LIKE '4.3.1.01.01.02%'");
        
        $query = $this->db->get();  
        $result = $query->row();
        return $result ? (float) $result->jumlah : 0.00;
    }
    public function get_total($tanggal)
    {
        $tahun = date('Y', strtotime($tanggal));
        $this->db->select(
            'a.id, b.total'
        );
        $this->db->from('trx_stsmaster a');
        $this->db->join('trx_stsdetail b', 'b.idstsmaster = a.id', 'inner');
        $this->db->join('trx_rapbd c', 'c.id = b.idrapbd', 'inner');
        $this->db->join('mst_rekening d', 'd.id = c.idrekening', 'inner');
        $this->db->where('a.tanggal', $tanggal);
        $this->db->where('YEAR(a.tanggal)', $tahun);
        $this->db->where("d.kdrekening NOT LIKE '4.1.4.18%'", '', false);
        $this->db->where("d.kdrekening NOT LIKE '4.3.1.01.01.02%'", '', false);
        $this->db->where("(d.kdrekening NOT LIKE '4.3.1.01.06%' OR d.kdrekening NOT LIKE '4.1.4.19.01%')", '', false);
        $this->db->where('a.isnonkas', 0);
        $this->db->group_by('a.id');
    
        $query = $this->db->get();  
        $result = $query->row();
    
        return $result ? (float) $result->total : 0.00;
    }
    
    public function get_data_hari_ini($tanggal) {
        $tahun = date('Y', strtotime($tanggal));
        $this->db->select(
           'a.id AS idstsmaster, 
            a.nomor AS nomor, 
            b.idrapbd, 
            b.keterangan, 
            b.jumlah AS pokokpajak, 
            b.total AS jumlahdibayar, 
            e.kdrekening AS koderekening, 
            e.nmrekening AS namarekening, 
            c.singkat AS singkatanupt, 
            c.singkat AS singkatdinas ,
            a.tahun,
            a.tanggal'
        );
        $this->db->from('trx_stsmaster a');
        $this->db->join('trx_stsdetail b', 'b.idstsmaster = a.id', 'inner');
        $this->db->join('mst_dinas c', 'c.id = a.iddinas', 'inner');
        $this->db->join('trx_rapbd d', 'd.id = b.idrapbd', 'inner');
        $this->db->join('mst_rekening e', 'e.id = d.idrekening', 'inner');
        $this->db->join('mst_rekening f', 'f.id = e.idheader', 'left');
        $this->db->join('mst_rekening g', 'g.id = f.idheader', 'left');
        $this->db->where('a.tanggal', $tanggal);
        $this->db->where('YEAR(a.tanggal)', $tahun);
        $this->db->where("NOT e.kdrekening LIKE '4.1.4.18%'", '', false);
        $this->db->where("NOT e.kdrekening LIKE '4.3.1.01.01.02%'", '', false);
        $this->db->where("(NOT e.kdrekening LIKE '4.3.1.01.06%' OR e.kdrekening NOT LIKE '4.1.4.19.01%')", '', false);
        $this->db->where('a.isnonkas', 0);
        $this->db->group_by('a.iddinas, a.id, e.kdrekening');
    
        $query = $this->db->get();
        $results = $query->result_array();
    
        return $results;
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
                <form action="' . site_url('laporan/Bkbesar/cetak') . '" class="form-row" method="post">
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
