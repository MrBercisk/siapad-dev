<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MDinas extends CI_Model {
    public function formInsert(){
		$form[] = '
			<form id="forminput" class="form-row" method="post" enctype="multipart/form-data" action="'.site_url('master/Dinas/aksi').'">
				<div class="row">
					<div class="col-md-5 offset-1">'
					.implode($this->Form->inputText('nama','Nama Dinas')).
				   '</div>
					<div class="col-md-5">'
					.implode($this->Form->inputText('singkat','Singkatan')).
				   '</div><div class="col-md-1"></div>
					<div class="col-md-5 offset-1">'
					.implode($this->Form->inputText('urut','No. Urut')).
				   '</div>
					<div class="col-md-5">'
					.implode($this->Form->inputText('isdispenda','Type Nomor')).
				   '</div><div class="col-md-1"></div>'.
				   '<div class="col-md-12 text-center">
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
		return $form;
	}
}
