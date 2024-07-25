<?php defined('BASEPATH') or exit('No direct script access allowed');
class Mwp extends CI_Model
{
	public function formInsert()
	{
		$status = '<option>-- Status --</option>
		<option value="1">Aktif</option>
		<option value="2">Tidak Aktif</option>
		';
		$form[] =
			'
			<style>
			.select2-container {
				width: 100% !important;
			}
		</style>
			<form id="forminput" method="post" enctype="multipart/form-data" action="' . site_url('master/WP/aksi') . '" class="form-row">
				<div class="row">
					<div class="col-md-6">
				   ' . implode($this->Form->inputText('nop', 'NOP')) .
			'</div>
					<div class="col-md-6">'
			. implode($this->Form->inputText('npwpd', 'NPWPD')) .
			'</div>
					<div class="col-md-6">'
			. implode($this->Form->inputText('nama', 'NAMA')) .
			'</div>
			<div class="col-md-6">'
			. implode($this->Form->inputSelectText('rekeningi', 'REKENING')) .
			'</div>
					<div class="col-md-6">'
			. implode($this->Form->inputText('alamat', 'ALAMAT')) .
			'</div>
					'
			. $this->Form->selectKec('kecamatan', 'kelurahan', 'Kecamatan', 'Kelurahan', NULL, NULL, 'col-md-3') .
			'
			<div class="col-md-6">'
			. implode($this->Form->inputSelectText('status', 'Status Aktif', '', $status)) .
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
					</di>
               </form>
        </div>
		
		<script type="text/javascript">
		$(document).ready(function() {
			$("#rekeningi").select2({
				ajax: {
					url: "' . site_url('master/wp/getRek') . '",
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
				placeholder: "Masukan Nama Rekening Pajak",
				templateResult: function(item) {
					if (item.loading) {
						return item.text;
					}
					var kode = item.kode || "-";
					return $("<span>" + kode + " - " + item.text + " </span>");
				},
				templateSelection: function(item) {
					if (!item.id) {
						return item.text;
					}
					var kode = item.kode || "-";
					return $("<span>" + kode + " - " + item.text + " </span>");
				}
			});
		});
	</script>
		';
		return implode('', $form);
	}
}
