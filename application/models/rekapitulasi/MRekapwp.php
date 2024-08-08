<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MRekapwp extends CI_Model {
    public function ambildata($tahun,$kdrekening) {
        $mysqli = $this->db->conn_id; 
 
        $statment = $mysqli->prepare("CALL spRptRekapPendRinciObj(?, ?)");
        $statment->bind_param('ss', $tahun,$kdrekening);  
    
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
        $rekdata = $this->db
            ->select('mst_rekening.id, mst_rekening.kdrekening, mst_rekening.nmrekening, mst_rekening.islrauptd')
            ->from('mst_rekening')
            ->where('mst_rekening.tipe', "D")
            ->where("(kdrekening LIKE '4.1.1.01%' 
                    OR kdrekening LIKE '4.1.1.02%' 
                    OR kdrekening LIKE '4.1.1.03%' 
                    OR kdrekening LIKE '4.1.1.07%')")
            ->get()
            ->result();

        $opsirek = '<option></option>';
        foreach ($rekdata as $ttd) {
            $opsirek .= '<option value="'.$ttd->kdrekening.'">'.$ttd->nmrekening.'</option>';
        }

        $form[] = '
        
        <div class="card">
            <div class="card-body">
                <form action="' . site_url('rekapitulasi/RekapWp/cetak') . '" class="form-row" method="post">
                <div class="col-md-12 border-bottom border-secondary" style="border-bottom: 2px solid #dee2e6 !important;">
                        <h5>Parameters</h5>
                </div>
                <div class="col-md-12">
                    <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tahun">Tahun:</label>
                            <input type="number" class="form-control" id="tahun" name="tahun" min="1900" max="9999" value="2024" required>
                        </div>
                    </div>
                             <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dinas">Rekening:</label>
                                    <select id="kdrekening" name="kdrekening" class="form-control select2" data-placeholder="Pilih Rekening" style="width: 100%;">
                                        '.$opsirek.'
                                    </select>
                                </div>
                            </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ttd">Tanda Tangan:</label>
                              <select id="tanda_tangan" name="tanda_tangan" class="form-control select2" data-placeholder="Pilih Tanda Tangan" style="width: 100%;">
                                      '.$opsittd.'
                              </select>
                        </div>
                    </div>
                    
                   <div class="col-md-3 mt-2">
                     
                        <div class="form-group">
                            
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="denda_checbox" name="denda_checbox">
                                <label class="form-check-label" for="denda">Denda</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pokok_checbox" name="pokok_checbox">
                                <label class="form-check-label" for="pokok">Pokok</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="jumlah_checkbox" name="jumlah_checkbox">
                                <label class="form-check-label" for="jumlah">Jumlah</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tgl_cetak">Tgl. Cetak:</label>
                            <input type="date" class="form-control" id="tglcetak" name="tglcetak" required>
                        </div>
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
        </div>';
        return $form;
    }

    public function iniopsirekening() {
        $rekeningCumaIni = array(
            '4.1.1.01' => 'Pajak Hotel',
            '4.1.1.02' => 'Pajak Restoran',
            '4.1.1.03' => 'Pajak Hiburan',
            '4.1.1.04' => 'Pajak Reklame',
            '4.1.1.05' => 'Pajak Penerangan Jalan',
            '4.1.1.07' => 'Pajak Parkir',
            '4.1.1.08' => 'Pajak Air Tanah',
            '4.1.1.11' => 'Pajak Mineral Batuan Bukan Logam',
            '4.1.1.12' => 'Pajak Bumi dan Bangunan Pedesaan dan Perkotaan',
            '4.1.1.13' => 'Bea Perolehan Hak Atas Tanah dan Bangunan',
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
