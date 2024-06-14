<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MPbapenda extends CI_Model {
    public function romawi($no) {
        $nomor = intval($no);
        $result = '';
        $lookup = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
        foreach ($lookup as $angka => $value) {
            $konversi = intval($nomor / $value);
            $result .= str_repeat($angka, $konversi);
            $nomor = $nomor % $value;
        }
    }
    public function get_data_bapenda($tanggal) {
        $query = $this->db->query("CALL spRptIkhtisarBPPRD(?)", array($tanggal));
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
    $form[] = '
    <div class="card">
        <div class="card-body">
            <form action="' . site_url('ikhtisar/Pdbapenda/cetak') . '" class="form-row" method="post">
            <div class="col-md-12 border-bottom border-secondary" style="border-bottom: 2px solid #dee2e6 !important;">
                    <h5>Parameters</h5>
            </div>
			  <div class="col-md-10">
                    <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tanggal">Tanggal:</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
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
