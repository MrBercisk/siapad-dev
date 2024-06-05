<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Mwp extends CI_Model {
    public function formInsert(){
		$form[] = '
			<form id="forminput" method="post" enctype="multipart/form-data" action="'.site_url('master/WP/aksi').'" class="form-row">
				<div class="row">
					<div class="col-md-6">
				   '.implode($this->Form->inputText('npwpd','NPWPD(REKLAME)')).
				   '</div>
					<div class="col-md-6">'
					.implode($this->Form->inputText('nomor','NOP/SKP/NPWPD')).
				   '</div>
					<div class="col-md-6">'
					.implode($this->Form->inputText('nama','NAMA')).
				   '</div>
					<div class="col-md-6">'
					.implode($this->Form->inputText('alamat','ALAMAT')).
				   '</div>
					<div class="col-md-6">'
					.implode($this->Form->inputText('no_type','Type Nomor')).
				   '</div>'
					.$this->Form->selectKec('kecamatan','kelurahan','Kecamatan','Kelurahan' , NULL, NULL, 'col-md-3').
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
		return implode('',$form);
	}
}
