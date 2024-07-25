<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MSkpd extends CI_Model {
	public function getSkpdData($thnpajak, $blnpajak) {
        $this->db->select("
            trx_skpdreklame.id as id_skpd, 
            trx_skpdreklame.idwp,
            trx_skpdreklame.tanggal,
            trx_skpdreklame.teks,
            trx_skpdreklame.blnpajak,
            trx_skpdreklame.thnpajak,
            trx_skpdreklame.jumlah,
            trx_skpdreklame.bunga,
            trx_skpdreklame.total,
            trx_skpdreklame.isbayar,
            trx_skpdreklame.tglbayar,
            trx_skpdreklame.keterangan,
            mst_wajibpajak.id,
            mst_wajibpajak.nama as wajibpajak,
            mst_wajibpajak.nomor as noskpd,
            mst_wajibpajak.tgljthtmp,
            mst_wajibpajak.alamat,
            mst_wajibpajak.tglskp
        ");
        $this->db->from('trx_skpdreklame');
        $this->db->join('mst_wajibpajak', 'mst_wajibpajak.id = trx_skpdreklame.idwp', 'inner');
        $this->db->where('trx_skpdreklame.thnpajak', $thnpajak);
        $this->db->where('trx_skpdreklame.blnpajak', $blnpajak);
        $this->db->order_by('mst_wajibpajak.tglskp');

        $query = $this->db->get();
        return $query->result();
    }
    public function formInsert(){
		
		$form[] = '

		<script>
                $(document).ready(function() {
						function formatBulan(value) {
							return value < 10 ? "0" + value : value;
						}

						$(".blnpajak").on("input change", function() {
							let value = parseInt($(this).val());
							if (value < 1) value = 1;
							if (value > 12) value = 12;
							$(this).val(formatBulan(value));
						});
                    function calculateTotal() {
                        var jumlah = parseFloat($(".jumlah2").val()) || 0;
                        var bunga = parseFloat($(".bunga2").val()) || 0;
                        var total = jumlah + bunga;
                        $(".total2").val(total);
                    }
    
                    $(".jumlah2, .bunga2").on("input", calculateTotal);
					$(".blnpajak").val(formatBulan($(".blnpajak").val())); 
                });
                </script>
			<form id="forminput" method="post" enctype="multipart/form-data" action="'.site_url('transaksi/SkpdReklame/aksi').'" class="form-row">
				<div class="row">
					<div class="col-md-3">
				   		<div class="form-group">
                            <label for="wp">Wajib Pajak:</label>
                              <select id="opsiwp" name="idwp" class="form-control opsiwp select2" data-placeholder="Pilih WP" style="width: 100%;" required>
                                    
                              </select>
                        </div>
				   </div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="nomor">Nomor:</label>
							<input type="text" id="nomor" class="form-control nomor" name="nomor" readonly>
						</div>
				   </div>
				    <div class="col-md-3">
						<div class="form-group">
							<label for="teks">Teks:</label>
							<input type="text" id="teks" class="form-control" name="teks">
						</div>
				   </div>
				    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tanggal">Tgl.Jth.Tmp:</label>
                            <input type="text" id="tgljthtmp" class="form-control tgljthtmp" name="tgljthtmp" readonly>
                        </div>
                   </div>
				  
				    <div class="col-md-3">
						<div class="form-group">
                            <label for="bulan">Bulan:</label>
                            <input type="number" name="blnpajak" id="blnpajak" class="form-control blnpajak min="1" max="12" value="1">    
						</div>	
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="tahun">Tahun:</label>
                            <input type="number" class="form-control" id="thnpajak" name="thnpajak" min="1900" max="9999" value="2024" required>
                        </div>
                    </div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="jumlah">Jumlah:</label>
							<input type="number" id="jumlah" class="form-control jumlah2" name="jumlah">
						</div>
				   </div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="bunga">Bunga:</label>
							<input type="number" id="bunga" class="form-control bunga2" name="bunga">
						</div>
				   </div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="total">Total:</label>
							<input type="number" id="total" class="form-control total2" name="total" readonly>
						</div>
				   </div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="tglbayar">Tgl Bayar:</label>
							<input type="date" id="tglbayar" class="form-control" name="tglbayar">
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
    public function formcetak(){
		$ttddata = $this->db
        ->select('mst_tandatangan.id, mst_tandatangan.nip, mst_tandatangan.nama, mst_tandatangan.jabatan1, mst_tandatangan.jabatan2')
        ->from('mst_tandatangan')
        ->get()
        ->result();
        $opsittd = '<option disabled selected>Pilih Tanda Tangan</option>';
        foreach ($ttddata as $ttd) {
            $opsittd .= '<option value="'.$ttd->id.'">'.$ttd->nama.'</option>';
        }
		$form[] = '

		<script>
                $(document).ready(function() {
						function formatBulan(value) {
							return value < 10 ? "0" + value : value;
						}

						$(".blnpajak").on("input change", function() {
							let value = parseInt($(this).val());
							if (value < 1) value = 1;
							if (value > 12) value = 12;
							$(this).val(formatBulan(value));
						});
                    function calculateTotal() {
                        var jumlah = parseFloat($(".jumlah2").val()) || 0;
                        var bunga = parseFloat($(".bunga2").val()) || 0;
                        var total = jumlah + bunga;
                        $(".total2").val(total);
                    }
    
                    $(".jumlah2, .bunga2").on("input", calculateTotal);
					$(".blnpajak").val(formatBulan($(".blnpajak").val())); 
                });
                </script>
			<form id="formcetak" method="post" enctype="multipart/form-data" action="'.site_url('transaksi/SkpdReklame/cetak').'" class="form-row">
				<div class="row">
				
				  <div class="col-md-12 border-bottom border-secondary" style="border-bottom: 2px solid #dee2e6 !important;">
                        <h5>Masa Pajak</h5>
                  </div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="tahun">Tahun:</label>
							<input type="number" class="form-control" id="thnpajak" name="thnpajak" min="1900" max="9999" value="2024" required>
						</div>
					</div>
				    <div class="col-md-3">
						<div class="form-group">
                            <label for="bulan">Bulan:</label>
                            <input type="number" name="blnpajak" id="blnpajak" class="form-control blnpajak min="1" max="12" value="1">    
						</div>	
                    </div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="tanggal">Tgl.Cetak:</label>
							<input type="date" id="tgl_cetak" class="form-control " name="tgl_cetak">
						</div>
				    </div>
					
				</div>
				<div class="row">
					
					<div class="col-md-12 border-bottom border-secondary" style="border-bottom: 2px solid #dee2e6 !important;">
							<h5>Kepala Seksi Penetapan</h5>
					</div>
					<div class="col-md-8">
                        <div class="form-group">
                            <label for="ttd">Tanda Tangan:</label>
                              <select id="tanda_tangan_2" name="tanda_tangan_2" class="form-control tanda_tangan_2" data-placeholder="Pilih Tanda Tangan" style="width: 100%;">
                                      '.$opsittd.'
                              </select>
                        </div>
                    </div>
				</div>
				<div class="row">
					
					<div class="col-md-12 border-bottom border-secondary" style="border-bottom: 2px solid #dee2e6 !important;">
							<h5>Kabid Pendaftaran dan Penetapan</h5>
					</div>
					<div class="col-md-8">
                        <div class="form-group">
                            <label for="ttd">Tanda Tangan:</label>
                              <select id="tanda_tangan_1" name="tanda_tangan_1" class="form-control tanda_tangan_1 " data-placeholder="Pilih Tanda Tangan" style="width: 100%;">
                                      '.$opsittd.'
                              </select>
                        </div>
                    </div>
				</div>
					
				<div class="col-md-12 text-center">
					<div class="btn-group">
						<button class="btn btn-outline-primary" type="submit" name="AKSI" value="Save">
							<i class="fa fa-pdf"></i> Cetak
						</button>
					</div>
				</div>
               </form>
        </div>';
		return $form;
	}
}
