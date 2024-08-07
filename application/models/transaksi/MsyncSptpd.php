<?php defined('BASEPATH') or exit('No direct script access allowed');
class MsyncSptpd extends CI_Model
{

	public function filters()
	{
		$form[] = '
			<form action="" id="forminput" class="form-row" method="post" enctype="multipart/form-data">
				<div class="row col-12">
					<div class="col-9">'
			. implode($this->Form->inputRowsText('tanggals', 'Pilih Tanggal', 'col-sm-3', 'form-control-sm datepicker', date("Y-m-d"), 'readonly')) .
			'</div>	
				<div class="col-3">
						<div class="btn-group">
								<button class="btn btn-outline-primary mr-1" type="button" id="filters">
									<i class="fas fa-search"></i> Cari
								</button>
						
								<button class="btn btn-outline-success" type="button" id="simpan" name="AKSI" value="Save">
									<i class="fas fa-print"></i> Simpan
								</button>
							</div>
				</div>
				</div>
				
             </form>';
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
