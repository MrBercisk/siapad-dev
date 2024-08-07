<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MLapBphtb extends CI_Model {
    private function get_saldo_awal($tahun, $bulan, $iduptd = null) {
        $this->db->select('SUM(a.total) as saldoawal')
                 ->from('trx_stsdetail a')
                 ->join('trx_stsmaster b', '
				 b.id = a.idstsmaster')
                 ->join('trx_rapbd c', 'c.id = a.idrapbd')
                 ->join('mst_rekening d', 'd.id = c.idrekening')
                 ->where('d.jenis', 'BPHTB')
                 ->where('MONTH(b.tanggal) <', $bulan)
                 ->where('b.tahun', $tahun);

        if ($iduptd !== null) {
            $this->db->where('a.iduptd', $iduptd);
        }

        $query = $this->db->get();
        $result = $query->row();
        return $result ? (float) $result->saldoawal : 0.00;
    }
    public function get_laporan_uptd($tahun, $bulan, $iduptd) {
        $saldoawal = $this->get_saldo_awal($tahun, $bulan, $iduptd);

        $this->db->select([
            'NULL AS nosspd',
            'NULL AS tanggal',
            'NULL AS nmwp',
            '0 AS total',
            "$saldoawal AS saldoawal"
        ]);
        $query1 = $this->db->get_compiled_select();
        $this->db->select([
            'a.nobukti AS nosspd',
            'b.tanggal',
            'c.nama AS nmwp',
            'a.total',
            "$saldoawal AS saldoawal"
        ])
        ->from('trx_stsdetail a')
        ->join('trx_stsmaster b', 'b.id = a.idstsmaster')
        ->join('mst_wajibpajak c', 'c.id = a.idwp')
        ->join('trx_rapbd d', 'd.id = a.idrapbd')
        ->join('mst_rekening e', 'e.id = d.idrekening')
        ->join('mst_uptd f', 'f.id = a.iduptd')
        ->where('e.jenis', 'BPHTB')
        ->where('a.iduptd', $iduptd)
        ->where('MONTH(b.tanggal)', $bulan)
        ->where('b.tahun', $tahun);
        $query2 = $this->db->get_compiled_select();

        $query = $this->db->query("$query1 UNION ALL $query2");
        return $query->result();
    }
    public function get_laporan_bulanan($tahun, $bulan) {
        $saldoawal = $this->get_saldo_awal($tahun, $bulan);

        $this->db->select([
            'NULL AS nmuptd',
            'NULL AS lembar',
            '0 AS total',
            "$saldoawal AS saldoawal"
        ]);
        $query1 = $this->db->get_compiled_select();
        $this->db->select([
            'f.nama AS nmuptd',
            'COUNT(*) AS lembar',
            'SUM(a.total) AS total',
            "$saldoawal AS saldoawal"
        ])
        ->from('trx_stsdetail a')
        ->join('trx_stsmaster b', 'b.id = a.idstsmaster')
        ->join('mst_wajibpajak c', 'c.id = a.idwp')
        ->join('trx_rapbd d', 'd.id = a.idrapbd')
        ->join('mst_rekening e', 'e.id = d.idrekening')
        ->join('mst_uptd f', 'f.id = a.iduptd')
        ->where('e.jenis', 'BPHTB')
        ->where('MONTH(b.tanggal)', $bulan)
        ->where('b.tahun', $tahun)
        ->group_by('a.iduptd');
        $query2 = $this->db->get_compiled_select();

        $query = $this->db->query("$query1 UNION ALL $query2");
        return $query->result();
    }
    public function get_laporan_harian($tanggal) {
        $tahun = date('Y', strtotime($tanggal));
        $saldoawal = $this->get_saldo_awal($tahun, date('m', strtotime($tanggal)));

        $this->db->select([
            'NULL AS nosspd',
            'NULL AS skpd',
            'NULL AS nmpejabat',
            'NULL AS nmwp',
            'NULL AS nmuptd',
            '0 AS total',
            "$saldoawal AS saldoawal"
        ]);
        $query1 = $this->db->get_compiled_select();
        $this->db->select([
            'a.nobukti AS nosspd',
            'a.formulir AS skpd',
            'a.nama AS nmpejabat',
            'c.nama AS nmwp',
            'IFNULL(f.nama, \'-\') AS nmuptd',
            'a.total',
            "$saldoawal AS saldoawal"
        ])
        ->from('trx_stsdetail a')
        ->join('trx_stsmaster b', 'b.id = a.idstsmaster')
        ->join('mst_wajibpajak c', 'c.id = a.idwp')
        ->join('trx_rapbd d', 'd.id = a.idrapbd')
        ->join('mst_rekening e', 'e.id = d.idrekening')
        ->join('mst_uptd f', 'f.id = a.iduptd')
        ->where('e.jenis', 'BPHTB')
        ->where('b.tanggal', $tanggal)
        ->where('b.tahun', $tahun);
        $query2 = $this->db->get_compiled_select();

        $query = $this->db->query("$query1 UNION ALL $query2");
        return $query->result();
    }
    public function get_real_hari_ini($idrekening, $iddinas, $tahun, $tanggal) {
        $this->db->select('SUM(a.total) AS total')
                 ->from('trx_stsdetail a')
                 ->join('trx_stsmaster b', 'b.id = a.idstsmaster')
                 ->join('trx_rapbd c', 'c.id = a.idrapbd')
                 ->where('c.idrekening', $idrekening)
                 ->where('b.iddinas', $iddinas)
                 ->where('b.tahun', $tahun)
                 ->where('b.tanggal', $tanggal);

        $query = $this->db->get();
        $result = $query->row();

        return $result ? (float) $result->total : 0.00;
    }
    public function get_laporan_hari($tanggal)
    {
   
        $this->db->select(
            '
             nobukti as nosspd, 
             formulir, 
             trx_stsdetail.nama as namapejabat,
             trx_stsmaster.tanggal, 
             blnpajak, 
             thnpajak, 
             jumlah as pokokpajak, 
             total as jumlsspd,
             IFNULL(mst_uptd.nama, \'-\') AS nmuptd,
             mst_wajibpajak.nama as namawp,
             mst_wajibpajak.id,
             trx_stsmaster.tahun,
             trx_stsmaster.tanggal,
             ');
             $this->db->from('trx_stsdetail');
             $this->db->join('trx_stsmaster', 'trx_stsdetail.idstsmaster = trx_stsmaster.id', 'left');
             $this->db->join('trx_rapbd', 'trx_stsdetail.idrapbd = trx_rapbd.id', 'left');
             $this->db->join('mst_rekening', 'trx_rapbd.idrekening = mst_rekening.id', 'left');
             $this->db->join('mst_uptd', 'trx_stsdetail.iduptd = mst_uptd.id', 'left');
             $this->db->join('mst_wajibpajak', 'trx_stsdetail.idwp = mst_wajibpajak.id', 'left');
             $this->db->where('mst_rekening.jenis', 'BPHTB'); 
             $this->db->where('trx_stsmaster.tanggal', $tanggal);
             $this->db->order_by('mst_rekening.id');

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
                <form action="' . site_url('laporan/RelBphtb/cetak') . '" class="form-row" method="post">
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
