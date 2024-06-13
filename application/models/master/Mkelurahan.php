<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Mkelurahan extends CI_Model {
	
    public function formInsert(){
		$kecData = $this->db->get('mst_kecamatan')->result();
        $opsikec = '';
        foreach ($kecData as $kec) {
            $opsikec .= '<option value="'.$kec->id.'">'.$kec->nama.'</option>';
        }
		$form[] = '
		<form action="'.site_url('master/Kelurahan/aksi').'" method="post" enctype="multipart/form-data" class="form-row">
				<div class="row">
					<div class="col-md-6 offset-3">
				   '.implode($this->Form->inputText('kode','Kode')).
				   '</div>
					<div class="col-md-6 offset-3">'
					.implode($this->Form->inputText('nama','Kelurahan')).
				   '</div>
				    <div class="col-md-6 offset-3">
                    <div class="form-group">
                        <label for="iduptd">Nama Kecamatan</label>
                        <select name="idkecamatan" id="idkecamatan" class="form-control">
                            '.$opsikec.'
                        </select>
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
				</div>
               </form>
        </div>';
		return $form;
	}
}
