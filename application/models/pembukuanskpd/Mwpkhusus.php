<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mwpkhusus extends CI_Model {
    public function ambildata($tglawal, $tglakhir, $tipe, $tahun) {
        $mysqli = $this->db->conn_id; 
 
        $statment = $mysqli->prepare("CALL spRptWPDispensasi(?, ?, ?, ?)");
        $statment->bind_param('ssss',$tglawal, $tglakhir, $tipe, $tahun);  
    
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
                <form action="' . site_url('pembukuanskpd/wpkhusus/cetak') . '" class="form-row" method="post">
                <div class="col-md-12 border-bottom border-secondary" style="border-bottom: 2px solid #dee2e6 !important;">
                        <h5>Parameters</h5>
                </div>
                <div class="col-md-10">
                    <div class="row">
                   <div class="col-md-2">
                        <div class="form-group">
                            <label for="tahun">Tahun:</label>
                            <input type="number" class="form-control" id="tahun" name="tahun" min="1900" max="9999" value="2024" required>
                        </div>
                    </div>
                  
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tanggal">Tanggal Awal:</label>
                            <input type="date" class="form-control" id="tgl_awal" name="tglawal" required>
                         
                        </div>
                    </div>
    
                    <div class="col-md-3">
                        <div class="form-group">
                        <label for="tanggal">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="tgl_akhir" name="tglakhir" required>
                        </div>
                    </div>
                    <script>
                        document.getElementById("tahun").value = new Date().getFullYear();
                    </script>
    
                    <div class="col-md-3">
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

                  
    
                    <div class="col-md-2">
                        <label class="form-check-label" for="ttd">Bayar</label>
                        <div class="form-group">
                              <div class="form-check">
                                <input type="radio" class="form-check-input" id="semua" name="tipe" value="A" checked>
                                <label class="form-check-label" for="sudah">Semua Data</label>
                            </div>       
                              <div class="form-check">
                                <input type="radio" class="form-check-input" id="sementara" name="tipe" value="S" >
                                <label class="form-check-label" for="sudah">Sementara</label>
                            </div>       
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="tutup" name="tipe" value="T">
                                <label class="form-check-label" for="tutup">Tutup</label>
                            </div>     
                             <div class="form-check">
                                <input type="radio" class="form-check-input" id="baru" name="tipe" value="B">
                                <label class="form-check-label" for="baru">Baru</label>
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
