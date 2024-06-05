<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mlradaerah extends CI_Model {
    public function get_data($tanggal) {
        $query = $this->db->query("CALL spRptLRAHarian(?)", array($tanggal));
        return $query->result_array();
    }
	public function formInsert() {
    $form[] = '
    <div class="card">
        <div class="card-body">
            <form action="' . site_url('laporan/Rladaerah/printlap') . '" class="form-row" method="post">
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
                        <label for="pg_break">Pg. Break:</label>
                        <input type="text" class="form-control" id="pg_break" name="pg_break">
                    </div>
                </div>
				</div>
			</div>
			<div class="col-md-2">
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="no_baris" name="no_baris">
                            <label class="form-check-label" for="no_baris">No. Baris</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="apbdp" name="apbdp" checked>
                            <label class="form-check-label" for="apbdp">APBDP</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="un_audited" name="un_audited">
                            <label class="form-check-label" for="un_audited">Un-Audited BPK</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="audited" name="audited">
                            <label class="form-check-label" for="audited">Audited BPK</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 border-bottom border-secondary" style="border-bottom: 2px solid #dee2e6 !important;">
                    <h5>Validator</h5>
                </div>
				<div class="col-md-10">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="nama">Nama:</label>
								<input type="text" class="form-control" id="nama" name="nama" value="Drs. YANWARDI, MM." required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="nip">NIP:</label>
								<input type="text" class="form-control" id="nip" name="nip" value="196401151986101001" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="jabatan">Jabatan:</label>
								<input type="text" class="form-control" id="jabatan" name="jabatan" value="KEPALA BADAN PENGELOLA PAJAK DAN RETRIBUSI DAERAH" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="kota">Kota:</label>
								<input type="text" class="form-control" id="kota" name="kota" value="KOTA BANDAR LAMPUNG" required>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-1">
					<label class="form-check-label" for="ttd">Penandatangan</label>
					<div class="form-check">
                       <input type="checkbox" class="form-check-input" id="ttd" name="ttd" checked>
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
