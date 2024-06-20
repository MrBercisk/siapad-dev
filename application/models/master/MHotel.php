<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MHotel extends CI_Model {
    
    public function formInsert(){
        $wpdata = $this->db
        ->select('mst_wajibpajak.id, mst_wajibpajak.nama')
        ->from('mst_wajibpajak')
        ->join('mst_wphotel', 'mst_wphotel.idwp = mst_wajibpajak.id')
        ->get()
        ->result();
        $opsiwp = '<option></option>';
        foreach ($wpdata as $wp) {
            $opsiwp .= '<option value="'.$wp->id.'">'.$wp->nama.'</option>';
        }
		$form[] = '
			  <form action="'.site_url('master/Hotel/aksi').'" method="post" enctype="multipart/form-data" class="form-row">
            <div class="row">
                <div class="col-md-6">
                    '.implode($this->Form->inputText('golkamar','Gol Kamar')).'
                </div>
                <div class="col-md-6">
                    '.implode($this->Form->inputText('tarif','Tarif')).'
                </div>
                <div class="col-md-6">
                    '.implode($this->Form->inputText('jmlkamar','Jml Kamar')).'
                </div>
                <div class="col-md-6">
                        <label for="idwp">Nama Hotel</label>
                       <select id="idwp" name="idwp" class="form-control select2" data-placeholder="Pilih Nama Hotel" style="width: 100%;">
                            '.$opsiwp.'
                        </select>
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
        </form>';
    return $form;
	}
    
    
}
