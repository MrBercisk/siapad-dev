<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mlrabapop extends CI_Model {
    public function ambildata($kdrekening, $tahun) {
        $mysqli = $this->db->conn_id; 
 
        $statment = $mysqli->prepare("CALL spRptBBPPDBPPRDPerWP(?, ?)");
        $statment->bind_param('ss', $kdrekening, $tahun);  
    
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
    

    public function get_apbd_apbdp_total($tahun, $idwp) {
        $this->db->select('SUM(a.apbd) as total_apbd, SUM(a.apbdp) as total_apbdp');
        $this->db->from('trx_rapbd_wp a');
        $this->db->join('mst_wajibpajak b', 'b.id = a.idwp', 'inner');
        $this->db->where('a.tahun', $tahun);
        $this->db->where('a.idwp', $idwp);
        $query = $this->db->get();
        return $query->row();
    }
    
    public function ambildatanya($tahun, $kdrekening, $idwp)
    {
        $this->db->select("
            $tahun AS tahun,
            MONTH(b.tanggal) AS bulan,
            b.tanggal,
            CASE 
                WHEN a.tglpajak IS NOT NULL AND a.tglpajak <> '' THEN CONCAT(a.tglpajak, '-', a.blnpajak, '-', a.thnpajak)
                WHEN a.blnpajak IS NOT NULL THEN CONCAT(
                    CASE a.blnpajak
                        WHEN 1 THEN 'Januari'
                        WHEN 2 THEN 'Februari'
                        WHEN 3 THEN 'Maret'
                        WHEN 4 THEN 'April'
                        WHEN 5 THEN 'Mei'
                        WHEN 6 THEN 'Juni'
                        WHEN 7 THEN 'Juli'
                        WHEN 8 THEN 'Agustus'
                        WHEN 9 THEN 'September'
                        WHEN 10 THEN 'Oktober'
                        WHEN 11 THEN 'November'
                        WHEN 12 THEN 'Desember'
                        ELSE ' '
                    END, ' ', a.thnpajak
                )
                ELSE '-' 
            END AS masapajak,
            a.total,
            a.keterangan, 
            a.nobukti, 
            e.nomor AS nomor_wp, 
            e.nama AS nama_wp
        ");
        
        $this->db->from('trx_stsdetail a');
        $this->db->join('trx_stsmaster b', 'b.id = a.idstsmaster', 'inner');
        $this->db->join('trx_rapbd c', 'c.id = a.idrapbd', 'inner');
        $this->db->join('mst_rekening d', 'd.id = c.idrekening', 'inner');
        $this->db->join('mst_wajibpajak e', 'e.id = a.idwp', 'inner');
        $this->db->where('YEAR(b.tanggal)', $tahun);
        $this->db->where('d.kdrekening', $kdrekening);
        $this->db->where('a.idwp', $idwp);
        $this->db->order_by('b.tanggal', 'asc');
    
        $query = $this->db->get();
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

        $opsiRek = $this->iniopsirekening();

       /*  $opsiwp = '<option value="">Pilih Wajib Pajak</option>'; */
        $form[] = '
        <div class="card">
            <div class="card-body">
                <form action="' . site_url('bukubesar/lrabapendaop/cetak') . '" class="form-row" method="post">
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
                              <select id="opsirekwp" name="kdrekening" class="form-control select2" data-placeholder="Pilih Rekening" style="width: 100%;">
                                      '.$opsiRek.'
                              </select>
                        </div>
                    </div>
                     <div class="col-md-4">
                        <div class="form-group">
                            <label for="wajib_pajak">Wajib Pajak:</label>
                            <select id="wajib_pajak" name="idwp" class="form-control select2" data-placeholder="Pilih Wajib Pajak" style="width: 100%;">
                              
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
        </div>
        
        ';
        return $form;
    }
    public function iniopsirekening() {
        $rekeningCumaIni = array(
            '4.1.1.01.01' => 'Pajak Hotel',
            '4.1.1.02.01' => 'Pajak Restoran',
            '4.1.1.03.01' => 'Pajak Hiburan',
            '4.1.1.04.01' => 'Pajak Reklame',
            '4.1.1.07.01' => 'Pajak Parkir',
            '4.1.1.08.01' => 'Pajak Air Tanah',
            '4.1.1.11.37' => 'Pajak Mineral Batuan Bukan Logam'
        );

        $rekData = $this->db
            ->select('mst_rekening.id, mst_rekening.kdrekening')
            ->from('mst_rekening')
            ->where_in('kdrekening', array_keys($rekeningCumaIni))
            ->get()
            ->result();
    
        $opsiRek = '<option></option>';
        foreach ($rekData as $rek) {
            $namaRek = isset($rekeningCumaIni[$rek->kdrekening]) ? $rekeningCumaIni[$rek->kdrekening] : $rek->kdrekening;
            $opsiRek .= '<option value="'.$rek->kdrekening.'">'.$namaRek.'</option>';
        }
    
        return $opsiRek;
    }
    
}
