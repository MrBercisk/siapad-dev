<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mrekonsspd extends CI_Model {
    public function ambildataskpdn($tahun,$bulan,$tahunsawal,$tahunsakhir,$kdrekening,$tgl) {
        $mysqli = $this->db->conn_id; 
 
        $statment = $mysqli->prepare("CALL spRptRekonSKPDN(?, ?, ?, ?, ?, ?)");
        $statment->bind_param('ssssss', $tahun,$bulan,$tahunsawal,$tahunsakhir,$kdrekening,$tgl);  
    
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
    public function ambildataskpdnrek($tahun,$bulan,$tahunsawal,$tahunsakhir,$kdrekening,$tgl) {
        $mysqli = $this->db->conn_id; 
 
        $statment = $mysqli->prepare("CALL spRptRekonSKPDNRek(?, ?, ?, ?, ?, ?)");
        $statment->bind_param('ssssss', $tahun,$bulan,$tahunsawal,$tahunsakhir,$kdrekening,$tgl);  
    
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
    
    public function get_apbd_apbdp_total($tahun, $kdrekening) {
        $this->db->select('SUM(a.apbd) as total_apbd, SUM(a.apbdp) as total_apbdp');
        $this->db->from('trx_rapbd a');
        $this->db->join('mst_rekening b', 'b.id = a.idrekening', 'inner');
        $this->db->where('a.tahun', $tahun);
        $this->db->where('LEFT(b.kdrekening, LENGTH(' . $this->db->escape($kdrekening) . ')) =', $kdrekening);
        $query = $this->db->get();
        return $query->row();
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
        $form[] = '
        <div class="card">
            <div class="card-body">
                <form action="' . site_url('pembukuanskpd/rekonsspd/cetak') . '" class="form-row" method="post" target="printFrame">
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
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tahun">Tahun SKPD Awal:</label>
                                    <input type="number" class="form-control" id="tahunsawal" name="tahunsawal" min="1900" max="9999" value="2024" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tahun">Tahun SKPD Akhir:</label>
                                    <input type="number" class="form-control" id="tahunsakhir" name="tahunsakhir" min="1900" max="9999" value="2024" required>
                                </div>
                            </div>
                            <script>
                                document.getElementById("tahun").value = new Date().getFullYear();
                            </script>
    
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tgl_cetak">Tgl. Cetak:</label>
                                    <input type="date" class="form-control" id="tglcetak" name="tglcetak" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dinas">Rekening:</label>
                                    <select id="kdrekening" name="kdrekening" class="form-control select2" data-placeholder="Pilih Rekening" style="width: 100%;">
                                        '.$opsiRek.'
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="ttd">Tanda Tangan:</label>
                                    <select id="tanda_tangan_1" name="tanda_tangan_1" class="form-control tanda_tangan_1 " data-placeholder="Pilih Tanda Tangan" style="width: 100%;" required>
                                            '.$opsittd.'
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ttd">Tanda Tangan:</label>
                                    <select id="tanda_tangan_2" name="tanda_tangan_2" class="form-control tanda_tangan_2" data-placeholder="Pilih Tanda Tangan" style="width: 100%;" required>
                                            '.$opsittd.'
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                    </div>
    
                    <div class="col-md-2">
                        <label class="form-check-label" for="ttd">Pakai Awal Tahun</label>
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="cektgl" name="cektgl" value="1" >
                                <label class="form-check-label" for="ttd"></label>
                            </div>
                        
                        </div>
                    </div>
    
                    <div class="col-md-1">
                        <div class="button-group">
                          <button type="submit" class="btn btn-primary">Cetak Laporan</button>
                           
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script>
                        document.getElementById("tahun").value = new Date().getFullYear();
                
                        document.getElementById("cektgl").addEventListener(\'change\', function() {
                            var tahunsawalInput = document.getElementById("tahunsawal");
                            if (this.checked) {
                                tahunsawalInput.disabled = false;
                                tahunsawalInput.value = ""; 
                            } else {
                                tahunsawalInput.disabled = true;
                                tahunsawalInput.value = "2024"; 
                            }
                        });


                        (function() {
                            var checkbox = document.getElementById("cektgl");
                            var tahunsawalInput = document.getElementById("tahunsawal");
                            var hiddenCheckboxValue = document.getElementById("ttd_checkbox_value");
                            tahunsawalInput.disabled = !checkbox.checked;
                            if (!checkbox.checked) {
                                tahunsawalInput.value = "2024"; 
                                hiddenCheckboxValue.value = "";
                            } else {
                                hiddenCheckboxValue.value = "true";
                            }
                        })();
                    </script>
                  

        ';
        return $form;
    }
    
    public function iniopsirekening() {
        $rekeningCumaIni = array(
            '4.1.1.01' => 'Pajak Hotel',
            '4.1.1.02' => 'Pajak Restoran',
            '4.1.1.03' => 'Pajak Hiburan',
            '4.1.1.04' => 'Pajak Reklame',
            '4.1.1.07' => 'Pajak Parkir',
            '4.1.1.08' => 'Pajak Air Tanah',
            '4.1.1.11' => 'Pajak Mineral Batuan Bukan Logam'
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
            $opsiRek .= '<option value="'.$rek->kdrekening.'">'.$rek->kdrekening.' - '.$namaRek.'</option>';
        }
    
        return $opsiRek;
    }
    
}
