<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MDisSkpd extends CI_Model {
    function selectById($id=0){
        $result = $this->db
            ->select("a.id, b.nomor AS noskpd, b.tglskp AS tglskpd, b.tgljthtmp, a.idwp, b.nama AS nmwp, a.teks, a.blnpajak, a.thnpajak, 
                a.jumlah, a.bunga, a.total, a.tglbayar, a.isbayar, a.isdispen, b.idrekening, c.nmrekening, 
                e.iduptd, f.nama AS nmuptd, f.singkat AS nmuptdsingkat, a.keterangan", false)
            ->join('mst_wajibpajak b', 'b.id=a.idwp')
            ->join('mst_rekening c', 'c.id=b.idrekening')
            ->join('mst_kelurahan d', 'd.id=b.idkelurahan', 'left')
            ->join('mst_kecamatan e', 'e.id=d.idkecamatan', 'left')
            ->join('mst_uptd f', 'f.id=e.iduptd', 'left')
            ->where('a.id', $id)
            ->get('trx_skpdreklame a');

        return $result;
    }
    public function formInsert(){
		
		$form[] = '
			<form id="forminput" method="post" enctype="multipart/form-data" action="'.site_url('transaksi/DispenSkpd/aksi').'" class="form-row">
				<div class="row">
                     <input type="hidden" id="idskpdrek" name="idskpdrek">
					<div class="col-md-4">
				   		<div class="form-group">
                            <label for="wp">Wajib Pajak:</label>
                              <select id="opsiwp2" name="idwp" class="form-control select2" data-placeholder="Pilih WP" style="width: 100%;">
                                    
                              </select>
                        </div>
				   </div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="nomor">No. SKPD:</label>
							<input type="text" id="nomor" class="form-control" name="nomor" readonly>
						</div>
				   </div>
				    <div class="col-md-4">
						<div class="form-group">
							<label for="teks">Teks:</label>
							<input type="text" id="teks" class="form-control" name="teks" readonly>
						</div>
				   </div>
				    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tanggal">Tgl.SKPD</label>
                            <input type="text" id="tglskp" class="form-control" name="tglskp" readonly>
                        </div>
                   </div>
				  
				    <div class="col-md-4">
						<div class="form-group">
                            <label for="bulan">Masa Pajak:</label>
                            <input type="text" name="masapajak" id="masapajak" class="form-control" readonly>    
						</div>	
                    </div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="jumlah">Jumlah:</label>
							<input type="number" id="jumlah" class="form-control" name="jumlah" readonly>
						</div>
				   </div>
				
					<div class="col-md-4">
						<div class="form-group">
							<label for="keterangan">Keterangan:</label>
							<input type="text" id="keterangan" class="form-control" name="keterangan">
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
		return $form;
	}
}
