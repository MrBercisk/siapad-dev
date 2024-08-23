<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MRekonsptd extends CI_Model {
    public function ambildata($tahun,$bulan) {
        $mysqli = $this->db->conn_id; 
 
        $statment = $mysqli->prepare("CALL spRptRekonBPKADLampiran(?, ?)");
        $statment->bind_param('ss', $tahun,$bulan);  
    
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
        $opsittd = '<option disabled selected>Pilih Tanda Tangan</option>';
        foreach ($ttddata as $ttd) {
            $opsittd .= '<option value="'.$ttd->id.'">'.$ttd->nama.'</option>';
        }
        $opsiRek = $this->iniopsirekening();

        $form[] = '
        
        <div class="card">
            <div class="card-body">
                <form action="' . site_url('rekonsiliasi/rekonsptd/cetak') . '" class="form-row" method="post">
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
                                    <label for="dinas">Jenis Pajak:</label>
                                    <select id="kdrekening" name="kdrekening" class="form-control select2" data-placeholder="Pilih Jenis Pajak" style="width: 100%;">
                                        '.$opsiRek.'
                                    </select>
                                </div>
                            </div>
                
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tgl_cetak">Tgl. Cetak:</label>
                            <input type="date" class="form-control" id="tglcetak" name="tglcetak" required>
                        </div>
                    </div>

               
        
                </div>
                <div class="row">
					
					<div class="col-md-12 border-bottom border-secondary" style="border-bottom: 2px solid #dee2e6 !important;">
							<h5>Kabid Buklap</h5>
					</div>
					<div class="col-md-8">
                        <div class="form-group">
                            <label for="ttd">Tanda Tangan:</label>
                              <select id="tanda_tangan_2" name="tanda_tangan_2" class="form-control tanda_tangan_2" data-placeholder="Pilih Tanda Tangan" style="width: 100%;" required>
                                      '.$opsittd.'
                              </select>
                        </div>
                    </div>
				</div>
				<div class="row">
					
					<div class="col-md-12 border-bottom border-secondary" style="border-bottom: 2px solid #dee2e6 !important;" >
							<h5>Kabid Akuntansi</h5>
					</div>
					<div class="col-md-8">
                        <div class="form-group">
                            <label for="ttd">Tanda Tangan:</label>
                              <select id="tanda_tangan_1" name="tanda_tangan_1" class="form-control tanda_tangan_1 " data-placeholder="Pilih Tanda Tangan" style="width: 100%;" required>
                                      '.$opsittd.'
                              </select>
                        </div>
                    </div>
				</div>
                  <div class="col-md-1 mt-3">
                         <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="apbdp_checkbox" name="apbdp_checkbox" >
                            <label class="form-check-label" for="apbdp">APBDP</label>
                        </div>
                        <div class="button-group mt-2">
                            <button type="submit" class="btn btn-primary">Cetak Laporan</button>
                        </div>
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
            '4.1.1.07' => 'Pajak Parkir',
            '4.1.1.08' => 'Pajak Air Tanah',
            '4.1.1.11' => 'Pajak Mineral Batuan Bukan Logam'
        );

        $rekData = $this->db
            ->select('mst_rekening.id, mst_rekening.kdrekening, mst_rekening.nmrekening')
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
