<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mropendapatan extends CI_Model {
    public function get_laporan_bulanan($bulan, $tahun) {
        $this->db->select("
            $tahun AS tahun, 
            d.kdrekening, 
            d.nmrekening, 
            b.tanggal,
            e.nama AS nmwp, 
            IFNULL(f.singkat, '-') AS uptd, 
            CONCAT(a.blnpajak, '-', a.thnpajak) AS masapajak, 
            a.nobukti AS nomor,
            a.formulir AS skpd,
            a.tgl_input AS tgl, 
            a.jumlah AS pokok, 
            a.nil_denda AS denda,
            0 AS pokok_lalu,  
            0 AS denda_lalu  
        ");
    
        $this->db->from('trx_stsdetail a');
        $this->db->join('trx_stsmaster b', 'b.id = a.idstsmaster', 'inner');
        $this->db->join('trx_rapbd c', 'c.id = a.idrapbd', 'inner');
        $this->db->join('mst_rekening d', 'd.id = c.idrekening', 'inner');
        $this->db->join('mst_wajibpajak e', 'e.id = a.idwp', 'inner');
        $this->db->join('mst_uptd f', 'f.id = a.iduptd', 'left');
        $this->db->join('trx_sptpd g', 'g.id = a.idskpd', 'left');
    
        $this->db->where('MONTH(b.tanggal)', $bulan);
        $this->db->where('YEAR(b.tanggal)', $tahun);
        $this->db->order_by('b.tanggal', 'ASC');
    
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
        $rekdata = $this->db
        ->select('mst_rekening.id, mst_rekening.kdrekening, mst_rekening.nmrekening, mst_rekening.islrauptd')
        ->from('mst_rekening')
        ->where('mst_rekening.idheader', 3)
        ->get()
        ->result();
        $opsirek = '<option></option>';
        foreach ($rekdata as $ttd) {
            $opsirek .= '<option value="'.$ttd->id.'">'.$ttd->nmrekening.'</option>';
        }
        $form[] = '
        
        <div class="card">
            <div class="card-body">
                <form action="' . site_url('ikhtisar/Ropendapatan/cetak') . '" class="form-row" method="post">
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
                            <label for="pembuat">Pembuat Dokumen:</label>
                              <select id="pembuat" name="pembuat" class="form-control select2" data-placeholder="Pilih Pembuat Dokumen" style="width: 100%;">
                                      '.$opsittd.'
                              </select>
                        </div>
                    </div>
    

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="rekening">Rekening:</label>
                              <select id="rekening" name="rekening" class="form-control select2" data-placeholder="Pilih Rekening" style="width: 100%;" required>
                                      '.$opsirek.'
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
