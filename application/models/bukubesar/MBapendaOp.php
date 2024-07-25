<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MBapendaOp extends CI_Model {
    /* public function getRptBBPPKDinas($iddinas, $tahun, $bulan) {
        $query = $this->db->query("CALL spRptBBPPKDinas(?, ?, ?)", array($iddinas, $tahun, $bulan));
        return $query->result_array();
    } */

    public function get_apbd_apbdp_total($tahun) {
        $this->db->select('SUM(apbd) as total_apbd, SUM(apbdp) as total_apbdp');
        $this->db->from('trx_rapbd_wp');
        $this->db->where('tahun', $tahun);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_data_bapenda_wp($tahun, $kdrekening)
    {
        $sql = "
            SELECT
                " . $tahun . " AS tahun,
                MONTH(b.tanggal) AS bulan,
                b.tanggal,
                CASE 
                    WHEN (NOT a.tglpajak IS NULL AND a.tglpajak <> '') 
                    THEN CONCAT(a.tglpajak, '-', a.blnpajak, '-', a.thnpajak)
                    ELSE NULL 
                END AS masapajak,
                a.total, 
                a.keterangan, 
                a.nobukti, 
                e.nomor AS nomor_wp, 
                e.nama AS nama_wp
            FROM trx_stsdetail a
            INNER JOIN trx_stsmaster b ON b.id = a.idstsmaster
            INNER JOIN trx_rapbd c ON c.id = a.idrapbd
            INNER JOIN mst_rekening d ON d.id = c.idrekening
            INNER JOIN mst_wajibpajak e ON e.id = a.idwp
            WHERE YEAR(b.tanggal) = ?
                AND d.kdrekening LIKE CONCAT(?, '%')
            ORDER BY b.tanggal
        ";
    
        $query = $this->db->query($sql, array($tahun, $kdrekening));
        $results = $query->result_array();
        return $results;
    }
  /*   
    public function get_data_bapenda_wp($tahun, $kdrekening)
    {
        $sql = "
            SELECT
                $tahun AS tahun,
                MONTH(b.tanggal) AS bulan,
                b.tanggal,
                CASE 
                    WHEN (NOT a.tglpajak IS NULL AND a.tglpajak <> '') 
                    THEN CONCAT(a.tglpajak, '-', a.blnpajak, '-', a.thnpajak)
                    ELSE NULL 
                END as masapajak,
                a.total, 
                a.keterangan, 
                a.nobukti, 
                e.nomor AS nomor_wp, 
                e.nama AS nama_wp
                FROM trx_stsdetail a
                INNER JOIN trx_stsmaster b ON b.id = a.idstsmaster
                INNER JOIN trx_rapbd c ON c.id = a.idrapbd
                INNER JOIN mst_rekening d ON d.id = c.idrekening
                INNER JOIN mst_wajibpajak e ON e.id = a.idwp
            WHERE YEAR(b.tanggal) = '{$tahun}'
                AND d.kdrekening LIKE CONCAT('$kdrekening', '%')
            ORDER BY b.tanggal
        ";
        $query = $this->db->query($sql);
        $results = $query->result_array();
        return $results;
    } */
    
    

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
        $rekData = $this->db
        ->select('mst_rekening.id, mst_rekening.kdrekening, mst_rekening.nmrekening')
        ->from('mst_rekening')
        ->join('mst_wajibpajak', 'mst_wajibpajak.idrekening = mst_rekening.id')
        ->join('trx_rapbd_wp', 'trx_rapbd_wp.idwp = mst_wajibpajak.id')
        ->group_by('mst_rekening.id, mst_rekening.kdrekening, mst_rekening.nmrekening')
        ->get()
        ->result();

    $opsiRek = '<option></option>';
    foreach ($rekData as $rek) {
        $opsiRek .= '<option value="'.$rek->kdrekening.'">'.$rek->kdrekening.' - '.$rek->nmrekening.'</option>';
    }
        $form[] = '
        
        <div class="card">
            <div class="card-body">
                <form action="' . site_url('bukubesar/BapendaOp/cetak') . '" class="form-row" method="post">
                <div class="col-md-12 border-bottom border-secondary" style="border-bottom: 2px solid #dee2e6 !important;">
                        <h5>Parameters</h5>
                </div>
                <div class="col-md-10">
                    <div class="row">
                   <div class="col-md-4">
                        <div class="form-group">
                            <label for="tahun">Tahun:</label>
                            <input type="number" class="form-control" id="tahun" name="tahun" min="1900" max="9999" value="2024" required>
                        </div>
                    </div>
                   

                    <script>
                        document.getElementById("tahun").value = new Date().getFullYear();
                    </script>
    
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="rekening">Rekening:</label>
                              <select id="kdrekening" name="kdrekening" class="form-control select2" data-placeholder="Pilih Rekening" style="width: 100%;">
                                      '.$opsiRek.'
                              </select>
                        </div>
                    </div>
    
                    </div>
                </div>

                    <div class="col-md-2">
                        <label class="form-check-label" for="ttd">Penandatangan</label>
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="ttd_checkbox" name="ttd_checkbox">
                                    <label class="form-check-label" for="ttd">Ttd</label>
                                </div>
                            </div>
                    </div>
               
                    <div class="col-md-1">
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
