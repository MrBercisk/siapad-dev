<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Mrekening extends CI_Model {
    public function formInsert(){
		$headerData = $this->db->get('mst_rekening')->result();
        $opsiheader = '';
				foreach ($headerData as $header) {
					$opsiheader .= '<option value="'.$header->idheader.'">'.$header->nmrekening.' ('.$header->idheader.')</option>';
				}
		$enumtipe = ['H', 'D'];
		$enumjenis = ['HRH','REK','BPHTB','PPJ','AKUN','LAIN'];
		$form[] = '
		<form action="'.site_url('master/Rekening/aksi').'" method="post" enctype="multipart/form-data" class="form-row">
				<div class="row">
					 <div class="col-md-6 ">
                    <div class="form-group">
                        <label for="idheader">Id Header</label>
                        <select name="idheader" id="idheader" class="form-control select2" data-placeholder="Pilih Nama Rekening" style="width: 100%;">
                            '.$opsiheader.'
                        </select>
                    </div>
                </div>
					<div class="col-md-6 ">'
					.implode($this->Form->inputText('kdrekening','Kode Rekening')).
				   '</div>
					<div class="col-md-6 ">'
					.implode($this->Form->inputText('nmrekening','Nama Rekening')).
				   '</div>
					<div class="col-md-6 ">'
					.implode($this->Form->inputText('kdrekview','Kode Rekview')).
				   '</div>
				   	<div class="col-md-6 ">'
					.implode($this->Form->inputText('level','Level')).
				   '</div>
				   <div class="col-md-6">
                    '.$this->Form->inputEnumOptions('tipe', 'Tipe', $enumtipe).'
                	</div>
				   <div class="col-md-6">
                    '.$this->Form->inputEnumOptions('jenis', 'Jenis', $enumjenis).'
                	</div>
					 	<div class="col-md-3 ">'
					.implode($this->Form->inputCheckbox('islrauptd', 'islrauptd')).
				   '</div>
				    	<div class="col-md-3 ">'
					.implode($this->Form->inputCheckbox('isinsidentil', 'isinsidentil')).
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
