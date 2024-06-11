<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MTtd extends CI_Model {
    public function formInsert(){
		$form[] = '
		<form action="'.site_url('master/Ttd/aksi').'" method="post" enctype="multipart/form-data" class="form-row">
				<div class="row">
					<div class="col-md-6">
				   '.implode($this->Form->inputText('nip','Nip')).
				   '</div>
					<div class="col-md-6">'
					.implode($this->Form->inputText('nama','Nama')).
				   '</div>
				   	<div class="col-md-6">'
					.implode($this->Form->inputText('jabatan1','Jabatan 1')).
				   '</div>
				   	<div class="col-md-6">'
					.implode($this->Form->inputText('jabatan2','Jabatan 2')).
				   '</div>
				   	<div class="col-md-6">'
					.implode($this->Form->inputText('tipe','Tipe')).
				   '</div>
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
