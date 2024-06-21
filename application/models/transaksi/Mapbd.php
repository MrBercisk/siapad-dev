<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Mapbd extends CI_Model {
    public function formInsert(){
        $dinasData = $this->db->get('mst_dinas')->result();
        $opsidin = '';
        foreach ($dinasData as $din) {
            $opsidin .= '<option value="'.$din->id.'">'.$din->nama.'</option>';
        }
        $rekData = $this->db->get('mst_rekening')->result();
        $opsirek = '';
        foreach ($rekData as $rek) {
            $opsirek .= '<option value="'.$rek->id.'">'.$rek->nmrekening.'</option>';
        }
		$form[] = '
			<form id="forminput" method="post" enctype="multipart/form-data" action="'.site_url('transaksi/apbd/aksi').'" class="form-row">
				<div class="row">
					<div class="col-md-6">
                        <div class="form-group">
                            <label for="iddinas">Nama Dinas</label>
                            <select name="iddinas" id="iddinas" class="form-control select2" data-placeholder="Pilih Nama Dinas" style="width: 100%;">
                                '.$opsidin.'
                            </select>
                        </div>
                    </div>
					<div class="col-md-6">
                        <div class="form-group">
                            <label for="tahun">Tahun:</label>
                            <input type="number" class="form-control" id="tahun" name="tahun" min="1900" max="9999" value="2024" required>
                        </div>
                    </div>
					<div class="col-md-6">
                        <div class="form-group">
                            <label for="idrekening">Nama Rekening</label>
                            <select name="idrekening" id="idrekening" class="form-control select2" data-placeholder="Pilih Nama Rekening" style="width: 100%;">
                                '.$opsirek.'
                            </select>
                        </div>
                    </div>
					<div class="col-md-6">
                        <div class="form-group">
                            <label for="apbd">APBD</label>
                            <input type="number" class="form-control" id="apbd" name="apbd" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="apbdp">APBDP</label>
                            <input type="number" class="form-control" id="apbdp" name="apbdp" step="0.01" required>
                        </div>
                    </div>
				   <div class="col-md-12 text-center">
						<div class="btn-group">
							<button class="btn btn-outline-danger mr-1" type="reset">
								<i class="fa fa-undo"></i> Reset
							</button>
							<button class="btn btn-outline-primary" type="submit" name="AKSI" value="Save">
								<i class="fa fa-save"></i> Simpan
							</button>
						</div>
					</div>
					</di>
               </form>
        </div>';
		return implode('',$form);
	}
}
