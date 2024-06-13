<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mlradaerah extends CI_Model {
    public function get_data_harian($tanggal) {
        $query = $this->db->query("CALL spRptLRAHarian(?)", array($tanggal));
        return $query->result_array();
    }
  
 
	public function formInsert1() {
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
            <form action="' . site_url('laporan/Lradaerah/cetak') . '" class="form-row" method="post">
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
			<div class="col-md-2">
                    <div class="form-group">
                        <div class="form-check">
                           <input type="checkbox" class="form-check-input" id="ttd_checkbox" name="ttd_checkbox" checked>
                           <label class="form-check-label" for="ttd">Ttd</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="apbdp_checkbox" name="apbdp_checkbox" checked>
                            <label class="form-check-label" for="apbdp">APBDP</label>
                        </div>
                        <label class="label mt-2" for="label"><b>BPK</b></label>
                       <div class="form-check">
                            <input type="radio" class="form-check-input" id="no_choice" name="audit_status" value="" checked>
                            <label class="form-check-label" for="no_choice">Tidak keduanya</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="un_audited" name="audit_status" value="un_audited">
                            <label class="form-check-label" for="un_audited">Un-Audited BPK</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="audited" name="audit_status" value="audited">
                            <label class="form-check-label" for="audited">Audited BPK</label>
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
    </div>';
    return $form;
}


}
