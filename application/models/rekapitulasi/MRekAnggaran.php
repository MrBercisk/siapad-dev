<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MRekAnggaran extends CI_Model {
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
                <form action="' . site_url('rekapitulasi/RekAnggaran/cetak') . '" class="form-row" method="post">
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
                              <select id="rekening" name="rekening" class="form-control select2" data-placeholder="Pilih Rekening" style="width: 100%;">
                                      '.$opsirek.'
                              </select>
                        </div>
                    </div>
    
                    </div>
                </div>
                 <div class="col-md-1">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="denda_checkbox" name="denda_checkbox">
                            <label class="form-check-label" for="denda_checkbox">Denda</label>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="pokok_checkbox" name="pokok_checkbox">
                            <label class="form-check-label" for="pokok_checkbox">Pokok</label>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="jumlah_checkbox" name="jumlah_checkbox">
                            <label class="form-check-label" for="jumlah_checkbox">Jumlah</label>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label class="form-check-label" for="ttd">Penandatangan</label>  
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="ttd_checkbox" name="ttd_checkbox" checked>
                            <label class="form-check-label" for="ttd_checkbox">Ttd</label>
                        </div>
                     
                        
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