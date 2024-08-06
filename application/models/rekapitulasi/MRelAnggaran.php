<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MRelAnggaran extends CI_Model {
    public function ambildata($bulan,$tahun) {
        $mysqli = $this->db->conn_id; 
 
        $statment = $mysqli->prepare("CALL spRptRekapRealisasiBln(?, ?)");
        $statment->bind_param('ss', $bulan,$tahun);  
    
        $statment->execute();
        $result = $statment->get_result();  
    
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        while ($mysqli->more_results()) {
            $mysqli->next_result(); 
        }
    
        return $data;
   
    }

    public function gettotallalu($bulan, $tahun)
    {
        $query="DROP TEMPORARY TABLE IF EXISTS `tmp_bulanlalu`;
                    CREATE TEMPORARY TABLE `tmp_bulanlalu`(
                        `id` INT(10) NOT NULL AUTO_INCREMENT,
                        `kdrek` VARCHAR(15) NOT NULL,
                        `jmlrp` DECIMAL(20,2) DEFAULT 0,
                        PRIMARY KEY  (`id`)
                    )ENGINE=INNODB DEFAULT CHARSET=utf8;
                    
        INSERT INTO tmp_bulanlalu(kdrek, jmlrp)
                    SELECT LEFT(d.kdrekening, 8) AS kdrek, SUM(a.total) AS jmlrp
                    FROM trx_stsdetail a
                    INNER JOIN trx_stsmaster b ON b.id = a.idstsmaster
                    INNER JOIN trx_rapbd c ON c.id = a.idrapbd
                    INNER JOIN mst_rekening d ON d.id = c.idrekening
                    WHERE YEAR(b.tanggal) = $tahun AND MONTH(b.tanggal) < $bulan
                        AND (d.kdrekening LIKE '4.1.1.01%' OR d.kdrekening LIKE '4.1.1.02%' OR d.kdrekening LIKE '4.1.1.03%' OR d.kdrekening LIKE '4.1.1.04%'
                        OR d.kdrekening LIKE '4.1.1.05%' OR d.kdrekening LIKE '4.1.1.07%' OR d.kdrekening LIKE '4.1.1.12%' OR d.kdrekening LIKE '4.1.1.13%')
                    GROUP BY kdrek";

                    $query = $this->db->query($sql, array($bulan, $tahun));
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
                <form action="' . site_url('rekapitulasi/RelAnggaran/cetak') . '" class="form-row" method="post">
                <div class="col-md-12 border-bottom border-secondary" style="border-bottom: 2px solid #dee2e6 !important;">
                        <h5>Parameters</h5>
                </div>
                <div class="col-md-9">
                    <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tahun">Tahun:</label>
                            <input type="number" class="form-control" id="tahun" name="tahun" min="1900" max="9999" value="2024" required>
                        </div>
                    </div>
                      <div class="col-md-4">
                        <div class="form-group">
                            <label for="bulan">Bulan:</label>
                            <select class="form-control select2" id="bulan" name="bulan" required>
                                <option value="" disabled selected>Pilih Bulan</option>
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                    </div>
    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tgl_cetak">Tgl. Cetak:</label>
                            <input type="date" class="form-control" id="tglcetak" name="tglcetak" required>
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
                    <div class="col-md-1 mt-3">
                        
                        <div class="button-group">
                            <button type="submit" class="btn btn-primary">Cetak Laporan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>';
        return $form;
    }

    
}
