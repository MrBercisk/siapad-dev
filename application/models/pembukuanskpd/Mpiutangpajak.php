<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mpiutangpajak extends CI_Model {
    public function ambildata($tanggal,$bulan, $tahun, $iduptd) {
        $mysqli = $this->db->conn_id; 
 
        $statment = $mysqli->prepare("CALL spRptTunggakanPiutang(?, ?, ?, ?)");
        $statment->bind_param('ssss',$tanggal, $bulan, $tahun, $iduptd);  
    
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
        $uptddata = $this->db
        ->select('mst_uptd.id,  mst_uptd.nama, mst_uptd.singkat')
        ->from('mst_uptd')
        ->get()
        ->result();
        $opsiuptd = '<option value="">(Semua)</option>';
        foreach ($uptddata as $uptd) {
            $opsiuptd .= '<option value="'.$uptd->id.'">'.$uptd->nama.'</option>';
        }



       /*  $opsiwp = '<option value="">Pilih Wajib Pajak</option>'; */
        $form[] = '
        <div class="card">
            <div class="card-body">
                <form action="' . site_url('pembukuanskpd/piutangpajak/cetak') . '" class="form-row" method="post">
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
                            <label for="ttd">UPTD:</label>
                              <select id="uptd" name="iduptd" class="form-control" data-placeholder="Pilih UPTD" style="width: 100%;">
                                      '.$opsiuptd.'
                              </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tgl_cetak">Tgl. Cetak:</label>
                            <input type="date" class="form-control" id="tgl_cetak" name="tgl_cetak" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nomor">Nomor:</label>
                            <input type="text" class="form-control" id="nomor" name="nomor" >
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
