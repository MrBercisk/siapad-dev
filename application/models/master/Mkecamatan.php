<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mkecamatan extends CI_Model {

    public function formInsert() {
        $uptdData = $this->db->get('mst_uptd')->result();
        $opsiuptd = '';
        foreach ($uptdData as $uptd) {
            $opsiuptd .= '<option value="'.$uptd->id.'">'.$uptd->nama.'</option>';
        }

        $form[] = '
        <form action="'.site_url('master/Kecamatan/aksi').'" method="post" enctype="multipart/form-data" class="form-row">
            <div class="row">
                <div class="col-md-6 offset-3">
                    '.implode($this->Form->inputText('kode','Kode')).'
                </div>
                <div class="col-md-6 offset-3">
                    '.implode($this->Form->inputText('nama','Kecamatan')).'
                </div>
                <div class="col-md-6 offset-3">
                    <div class="form-group">
                        <label for="iduptd">UPTD</label>
                        <select name="iduptd" id="iduptd" class="form-control">
                            '.$opsiuptd.'
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
        </form>';

        return $form;
    }
}
