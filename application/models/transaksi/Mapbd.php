<?php defined('BASEPATH') or exit('No direct script access allowed');
class MApbd extends CI_Model
{
	public function formInsert()
	{
		$form[] = '
			<form id="forminput" class="form-row" method="post" enctype="multipart/form-data" action="' . site_url('master/Dinas/aksi') . '">
				<div class="row">
					<div class="col-md-5 offset-1">'
			. implode($this->Form->inputText('nama', 'Nama Dinas')) .
			'</div>
					<div class="col-md-5">'
			. implode($this->Form->inputText('singkat', 'Singkatan')) .
			'</div><div class="col-md-1"></div>
					<div class="col-md-5 offset-1">'
			. implode($this->Form->inputText('urut', 'No. Urut')) .
			'</div>
					<div class="col-md-5">'
			. implode($this->Form->inputText('isdispenda', 'Type Nomor')) .
			'</div><div class="col-md-1"></div>' .
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
		return $form;
	}

	public function formCari()
	{
		$datadinas = $this->db
			->select('nama, singkat, urut, id')
			->from('mst_dinas')
			->get()
			->result();
		$opsidinas = '<option></option>';
		foreach ($datadinas as $dinas) {
			$opsidinas .= '<option value="' . $dinas->id . '">' . $dinas->nama . '</option>';
		}


		$currentYear = (int)date('Y');
		$lastFiveYears = '';
		for ($i = 0; $i < 5; $i++) {
			$lastFiveYears .= '<option value="' . ($currentYear - $i) . '">' . ($currentYear - $i) . '</option>';
		}

		$formCari[] = '
			<div class="row ml-4 mr-3">	
				<div class="col-8 colek">
					<div class="form-group row mt-4">
						<div class="col-sm-3">
                                 <label for="dinas">Dinas</label>
						</div>
						<div class="col-sm-9">
							<select id="dinas" name="dinas" class="form-control select2 " data-placeholder="Pilih Dinas">
									' . $opsidinas . '
							</select>
						</div>
					</div>
				</div>
				<div class="col-4 colek"> 
					<div class="form-group row mt-4">
						<div class="col-sm-3">
									<label for="tahun">Tahun</label>
						</div>
						<div class="col-sm-9">
							<select id="tahun" name="tahun" class="form-control" data-placeholder="Pilih Dinas">
									' . $lastFiveYears . '
							</select>
						</div>
					</div>
				</div>
		</div>
		';
		return $formCari;
	}
}
