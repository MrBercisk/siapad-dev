<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MHotel extends CI_Model {
    
    public function formInsert(){
        $wpdata = $this->db->get('mst_wajibpajak')->result();
        $opsiwp = '';
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
                           <div class="form-group">
                    <label for="wp">Hotel</label>
                    <select class="form-control select2" id="wp" name="wp" style="width: 100%;">
                        <option value="">Pilih Nama Hotel</option>
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

        $form[] = '
    <script>
        $(document).ready(function() {
          $(".select2").select2({
            ajax: {
                url: "' . site_url('master/Hotel/getWpData') . '",
                dataType: "json",
                delay: 250,
                 data: function (params) {
                return {
                    term: params.term
                };
            },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            },
            minimumInputLength: 2
        });
        });
    </script>';

    return $form;
	}
    
}
