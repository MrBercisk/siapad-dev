<?php defined('BASEPATH') or exit('No direct script access allowed');
class MformSptpd extends CI_Model
{
	public function formInsert()
	{
		$milehTahun = $this->Jstambah->milehTahun((int)date("Y"));
		$milehSasi = $this->Jstambah->milehBulan((int)date("m"));
		// $wajibPajak = $this->Jstambah->milihWajibPajak();
		// var_dump($wajibPajak);
		// die;
		$form[] = '
		<style>
			.select2-container {
				width: 100% !important;
			}
		</style>
			<form id="forminput" class="form-row" method="post" enctype="multipart/form-data" action="' . site_url('transaksi/FormSptpd/aksi') . '">
			
			<div class="row mt-3">
					<div class="col-6">'
			. implode($this->Form->inputRowsText('nomors', 'Nomor', 'col-sm-3', 'form-control-sm')) .
			'</div>
					<div class="col-6">'
			. implode($this->Form->inputRowsText('rekenings', 'Rekening', 'col-sm-3', 'form-control-sm', '', 'readonly')) .
			'</div>
				<div class="col-6">'
			. implode($this->Form->inputRowsText('npwpds', 'NPWPD', 'col-sm-3', 'form-control-sm', '', 'readonly')) .
			'</div>
			<div class="col-6">'
			. implode($this->Form->inputRowsText('nops', 'NOP', 'col-sm-3', 'form-control-sm', '', 'readonly')) .
			'</div>
				<div class="col-6">'
			. implode($this->Form->inputRowsSelect('wajibpajaks', 'Wajib Pajak', 'col-sm-3', 'form-control-sm ')) .
			'</div>
				<div class="col-6">'
			. implode($this->Form->inputRowsText('tanggals', 'Tanggal', 'col-sm-3', 'form-control-sm datepicker')) .
			'</div>
				<div class="col-6">'
			. implode($this->Form->inputRowsText('tglterbits', 'Tgl. Terbit', 'col-sm-3', 'form-control-sm datepicker')) .
			'</div>
				<div class="col-6">'
			. implode($this->Form->inputRowsSelect('bulans', 'Bulan', 'col-sm-3', 'form-control-sm', $milehSasi)) .
			'</div>
				<div class="col-6">'
			. implode($this->Form->inputRowsSelect('tahuns', 'Tahun', 'col-sm-3', 'form-control-sm', $milehTahun)) .
			'</div>
				<div class="col-6">'
			. implode($this->Form->inputRowsText('pokoks', 'Pokok', 'col-sm-3', 'form-control-sm')) .
			'</div>
				<div class="col-6">'
			. implode($this->Form->inputRowsText('dendas', 'Denda', 'col-sm-3', 'form-control-sm')) .
			'</div>
				<div class="col-6">'
			. implode($this->Form->inputRowsText('jumlahs', 'Jumlah', 'col-sm-3', 'form-control-sm')) .
			'</div>
				<div class="col-6">'
			. implode($this->Form->inputRowsTextArea('keterangans', 'Keterangan', 'col-sm-3', 'form-control-sm')) .
			'</div>

			<div class="col-6">'
			. implode($this->Form->inputTextHidden('idwps', '', 'col-sm-3', 'form-control-sm')) .
			implode($this->Form->inputTextHidden('idreks', '', 'col-sm-3', 'form-control-sm')) .
			'</div>

			

			<div class="col-md-1"></div>' .
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
        </div>
	
		 <script type="text/javascript">
				$(document).ready(function() {
					$("#wajibpajaks , #wajibpajak").select2({
						ajax: {
							url: "http://localhost/siapad-dev/transaksi/formsptpd/get_data",
							type: "POST",
							dataType: "json",
							delay: 250,
							data: function(params) {
								return {
									search: params.term,
									page: params.page || 1
								};
							},
							processResults: function(data) {
								return {
									results: data.items,
									pagination: {
										more: data.pagination.more
									}
								};
							},
							cache: true
						},
						minimumInputLength: 5,
						width: "resolve",
						placeholder:"Masukan Nama Wajib Pajak"
					}).on("select2:select", function(e) {
						if(e.params.data.selected === true){
						var nop =e.params.data.nop || "-";
						var npwpd =e.params.data.npwpd || "-";
						var idwp =e.params.data.id || "-";
						var idrek =e.params.data.idrek || "-";
							$("#rekenings").val(e.params.data.rekening);
							$("#nops").val(nop);
							$("#npwpds").val(npwpd);
							$("#idwps").val(idwp);				
							$("#idreks").val(idrek);				
						}else{
							console.log("huh");
						}
					});

					$("#ttd").select2({
					ajax: {
							url: "http://localhost/siapad-dev/Api/getdata/getTtd",
							type: "POST",
							dataType: "json",
							delay: 250,
							data: function(params) {
								return {
									search: params.term,
									page: params.page || 1
								};
							},
							processResults: function(data) {
								return {
									results: data.items,
									pagination: {
										more: data.pagination.more
									}
								};
							},
							cache: true
						},
						minimumInputLength: 3,
						placeholder:"Masukan Nama Penanda Tangan",
						templateResult: function(item) {
							if (item.loading) {
								return item.text;
							}
							var jabatan = item.jabatan2 || "-";
							var nip = item.nip || "-";
							return $("<span>" + item.text + " - " + jabatan + " (" + nip + ")</span>");
						},
						templateSelection: function(item) {
							if (!item.id) {
								return item.text;
							}
							var jabatan = item.jabatan2 || "-";
							var nip = item.nip || "-";
							return item.text + " - " + jabatan + " (" + nip + ")";
						}
					}).on("select2:select", function(e) {
						if(e.params.data.selected === true){
						var jab1 =e.params.data.jabatan1 || "-";
						var jab2 =e.params.data.jabatan2 || "-";
						var nip =e.params.data.nip || "-";
							$("#nipttd").val(nip);
							$("#jabatan1").val(jab1);
							$("#jabatan2").val(jab2);			
						}else{
							console.log("huh");
						}
					});

					$("#forminput").on("submit", function(e) {
							if ($(tglcetak).val() === null || $(tglcetak).val() === "") {
								e.preventDefault();
								alert("Silakan Pilih Tanggal Cetak terlebih dahulu.");
							}else if ($(jnspajak).val() === null || $(jnspajak).val() === "") {
								e.preventDefault();
								alert("Silakan Pilih Jenis Pajak terlebih dahulu.");
							}else if ($(pembuat).val() === null || $(pembuat).val() === "") {
								e.preventDefault();
								alert("Silakan Masukan Nama Pembuat terlebih dahulu.");
							}else if ($(ttd).val() === null || $(ttd).val() === "") {
								e.preventDefault();
								alert("Silakan Pilih Penanda Tangan terlebih dahulu.");
							}
						
					});
					
				});
		</script>
		';
		return $form;
	}

	public function filters()
	{
		$milehTahun = $this->Jstambah->milehTahun((int)date("Y"));
		$milehBulan = $this->Jstambah->milehBulan((int)date("m"));
		$milehJenis = $this->Jstambah->milihJenisPajak();

		$form[] = '
			<form action="' . site_url('transaksi/FormSptpd/Cetak') . '" id="forminput" class="form-row" method="post" enctype="multipart/form-data" target="_blank">
				<div class="row mt-3">
					<div class="col-6">'
			. implode($this->Form->inputRowsText('nomor', 'Nomor', 'col-sm-3', 'form-control-sm')) .
			'</div>	
				<div class="col-6">'
			. implode($this->Form->inputRowsText('npwpd', 'NPWPD', 'col-sm-3', 'form-control-sm', '')) .
			'</div>			
				<div class="col-6">'
			. implode($this->Form->inputRowsSelect('wajibpajak', 'Wajib Pajak', 'col-sm-3', 'form-control-sm ')) .
			'</div>
			<div class="col-6">'
			. implode($this->Form->inputRowsSelect('tahun', 'Tahun', 'col-sm-3', 'form-control-sm', $milehTahun)) .
			'</div>
			
			
			<div class="col-md-1"></div>
					<div class="col-md-12">
						<div class="text-right mb-2">
							<a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#">
							<i class="fas fa-plus"></i></a>
						</div>
					<div class="collapse text-left" id="mycard-collapse" style="">
				<div class="row">	
				<div class="col-6">'
			. implode($this->Form->inputRowsSelect('bulan', 'Bulan', 'col-sm-3', 'form-control-sm', $milehBulan)) .
			'</div>	
			<div class="col-6">'
			. implode($this->Form->inputRowsSelect('jnspajak', 'Jenis Pajak', 'col-sm-3', 'form-control-sm', $milehJenis)) .
			'</div>	

					<div class="col-6">'
			. implode($this->Form->inputRowsText('tglcetak', 'Tgl. Cetak', 'col-sm-3', 'form-control-sm datepicker', '', 'readonly')) .
			'</div>	
						<div class="col-12 ">'
			. implode($this->Form->inputRowsText('pembuat', 'Nama Pembuat', 'col-sm-3', 'form-control-sm', '')) .
			'</div>
			<div class="col-12">'
			. implode($this->Form->inputRowsText('nip', 'NIP Pembuat', 'col-sm-3', 'form-control-sm', '')) .
			'</div>	
			<div class="col-12">'
			. implode($this->Form->inputRowsSelect('ttd', 'Penanda Tangan', 'col-sm-3', 'form-control-sm')) .
			'</div>
			<div class="col-12">'
			. implode($this->Form->inputRowsText('nipttd', 'NIP', 'col-sm-3', 'form-control-sm', '', 'readonly')) .
			'</div>	
			<div class="col-12">'
			. implode($this->Form->inputRowsText('jabatan1', 'Jabatan', 'col-sm-3', 'form-control-sm', '', 'readonly')) .
			'</div>	
			<div class="col-12">'
			. implode($this->Form->inputRowsText('jabatan2', '', 'col-sm-3', 'form-control-sm', '', 'readonly')) .
			'</div>	
			</div>
					</div>	
					</div>	
			' .
			'<div class="col-md-12 text-center">
						<div class="btn-group">
							<button class="btn btn-outline-primary mr-1" type="button" id="filters">
								<i class="fas fa-search"></i> Cari
							</button>
					
							<button class="btn btn-outline-success" type="submit" id="Cetak">
								<i class="fas fa-print"></i> Cetak
							</button>
						</div>
					</div>
				</di>
				
             </form>
        </div>';
		return $form;
	}
	public function datepicker()
	{
		$datepicker = $this->Jssetup->datePicker('.datepicker');

		return $datepicker;
	}

	public function isExists($nomor, $idwp, $bln, $thn)
	{
		if (!empty($nomor)) {
			$total = 	$this->db
				->where('nomor', $nomor)
				// ->where('idwp', $idwp)
				// ->where('blnpajak', $bln)
				// ->where('thnpajak', $thn)
				->count_all_results('trx_sptpd');
		} else {
			$total = 	$this->db
				->where('nomor', $nomor)
				->where('idwp', $idwp)
				->where('blnpajak', $bln)
				->where('thnpajak', $thn)
				->count_all_results('trx_sptpd');
		}

		return ($total > 0 ? true : false);
	}

	public function getDataForm($nomor, $npwpd, $rekening, $wajibpajak, $tahun, $bulan, $kdrekening)
	{
		$this->db->select(
			"a.tanggal, a.nomor, b.nama AS nmwp, a.jumlah, a.keterangan"
		)
			->from('trx_sptpd a')
			->join('mst_wajibpajak b', 'b.id = a.idwp', 'inner')
			->join('mst_rekening c', 'c.id = a.idrekening', 'inner');

		if (!empty($nomor)) {
			$this->db->like("a.nomor", $nomor, "after");
		}
		if (!empty($rekening)) {
			$this->db->like("c.nmrekening", $rekening);
		}
		if (!empty($npwpd)) {
			$this->db->where("b.nomor", $npwpd);
		}
		if (!empty($wajibpajak)) {
			$this->db->like("b.id", $wajibpajak);
		}
		if (!empty($tahun)) {
			$this->db->where("a.thnpajak", $tahun);
		}
		if (!empty($bulan)) {
			$this->db->where("a.blnpajak", $bulan);
		}
		if (!empty($kdrekening)) {
			$this->db->where("LEFT(c.kdrekening, 8) =", $kdrekening);
		}
		$this->db->order_by('a.nomor', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getPajakByKdRekening($kdrekening)
	{
		$jnspajak = $this->Msetup->jupukJenisPajak();
		if (array_key_exists($kdrekening, $jnspajak)) {
			return $jnspajak[$kdrekening];
		} else {
			return null; // atau bisa return pesan lain, misalnya "Kode tidak ditemukan"
		}
	}
	public function getPajakBulanIndo($bulan)
	{
		$bulanind = $this->Msetup->jupukSasiIndo();
		if (array_key_exists($bulan, $bulanind)) {
			return $bulanind[$bulan];
		} else {
			return null; // atau bisa return pesan lain, misalnya "Kode tidak ditemukan"
		}
	}
}
