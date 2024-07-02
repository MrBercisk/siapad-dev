<?php defined('BASEPATH') or exit('No direct script access allowed');
class MApbd extends CI_Model
{
  public function formInsert()
  {
    $rekdata = $this->db
      ->select('mst_rekening.id, mst_rekening.kdrekening, mst_rekening.nmrekening, mst_rekening.islrauptd')
      ->from('mst_rekening')
      ->like('kdrekening', '4.1')
      // ->where('mst_rekening.idheader', 3)
      ->get()
      ->result();
    $opsirek = '<option></option>';
    foreach ($rekdata as $ttd) {
      $opsirek .= '<option value="' . $ttd->id . '">' . $ttd->kdrekening . ' - ' . $ttd->nmrekening . '</option>';
    }
    $form[] = '
			<form id="forminput" class="form-row" method="post" enctype="multipart/form-data" action="' . site_url('transaksi/Apbd/aksi') . '">
      <div class="card-body"> 
      <div class="container row">  
          <div class="col-6">
            <div class="form-group row">
                  <label for="mtahun" class="col-sm-3 col-form-label">Tahun</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="mtahun" name="tahun" readonly>
                    </div>
              </div>
            </div>
          <div class="col-6">
            <div class="form-group row">
                  <label for="dinas" class="col-sm-3 col-form-label">Dinas</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="mdinas" name="dinas" placeholder="Silahkan Pilih Dinas Diatas" readonly>
                    </div>
              </div>
            </div> 
            <div class="col-6">
            <div class="form-group row">
                <label for="rekening" class="col-sm-3 col-form-label">Rekening</label>
                  <div class="col-sm-9">
                      <select class="js-example-basic-single select2" id="select2-modal" name="rekening" style="width: 100%;" data-placeholder="SIlahkan Pilih Rekening">
                          ' . $opsirek . '
                          </select>
                  </div>
            </div>
          </div>
            <div class="col-6">
            <div class="form-group row">
                <label for="uraian" class="col-sm-3 col-form-label">Uraian</label>
                  <div class="col-sm-9">
                     <textarea class="form-control" id="uraian" name="uraian" rows="4"></textarea>
                  </div>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group row">
                  <label for="apbd" class="col-sm-3 col-form-label">APBD</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="apbd" name="apbd" placeholder="0">
                    </div>
              </div>
            </div> 
            <div class="col-6">
            <div class="form-group row">
                  <label for="apbdp" class="col-sm-3 col-form-label">APBDP</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="apbdp" name="apbdp" placeholder="0">
                    </div>
              </div>
            </div> 

            <div class="col-md-1"></div>
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
          </div>
          </form>
          ';
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

  public function formModal()
  {
    $rekdata = $this->db
      ->select('mst_rekening.id, mst_rekening.kdrekening, mst_rekening.nmrekening, mst_rekening.islrauptd')
      ->from('mst_rekening')
      // ->where('mst_rekening.idheader', 3)
      ->get()
      ->result();
    $opsirek = '<option></option>';
    foreach ($rekdata as $ttd) {
      $opsirek .= '<option value="' . $ttd->id . '">' . $ttd->kdrekening . ' - ' . $ttd->nmrekening . '</option>';
    }
    $formTambah[] = '
    <form action="" method="POST">
				<div class="row ml-4 mr-3">	
                        <div class="col-12">
                          <div class="form-group row mt-1 mb-1">
                            <div class="col-sm-3">
                                 <label for="mtahun">Tahun</label>
                            </div>
                            <div class="col-sm-9">
                              <input type="text" class="form-control form-control-sm" id="mtahun" name="tahun" readonly>
                            </div>
                          </div>
                        </div>
                    </div>
                     <div class="row ml-4 mr-3">	
                        <div class="col-12">
                          <div class="form-group row mt-1 mb-1">
                            <div class="col-sm-3">
                                   <label for="mdinas">Dinas</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="mdinas" name="dinas" readonly placeholder="Silahkan Pilih Dinas">
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="row ml-4 mr-3">	
                        <div class="col-12">
                          <div class="form-group row mt-1 mb-1">
                            <div class="col-sm-3">
                                   <label for="rekening">Rekening</label>
                            </div>
                            <div class="col-sm-9">
        					        <select class="js-example-basic-single select2" id="select2-modal" name="state" style="width: 100%;">
                          ' . $opsirek . '
                          </select>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="row ml-4 mr-3">	
                        <div class="col-12">
                          <div class="form-group row mt-1 mb-1">
                            <div class="col-sm-3">
                                   <label for="uraian">Uraian</label>
                            </div>
                            <div class="col-sm-9">
                               <textarea class="form-control" id="uraian" name="uraian" rows="4"></textarea>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="row ml-4 mr-3">	
                        <div class="col-12">
                          <div class="form-group row mt-1 mb-1">
                            <div class="col-sm-3">
                                   <label for="apbd">APBD</label>
                            </div>
                            <div class="col-sm-9">
                              <input type="text" class="form-control form-control-sm" id="apbdp" name="apbdp" placeholder="0">
                            </div>
                          </div>
                        </div>
                    </div>
                     <div class="row ml-4 mr-3">	
                        <div class="col-12">
                          <div class="form-group row mt-1 mb-1">
                            <div class="col-sm-3">
                                   <label for="apbdp">APBDP</label>
                            </div>
                            <div class="col-sm-9">
                              <input type="text" class="form-control form-control-sm" id="apbdp" name="apbdp" placeholder="0">
                            </div>
                          </div>
                        </div>
                    </div>
                <form>
				';

    return $formTambah;
  }

  public function dinasByName($kunci, $where, $table, $selected)
  {

    $query = $this->db
      ->select($selected)
      ->from($table)
      ->where($kunci, $where)
      ->get()
      ->result();

    return $query;
  }

  public function temporaryTable($dinas, $tahun)
  {
    //   Query temporary
    $sql = "DROP TEMPORARY TABLE IF EXISTS temp_apbd";
    $this->db->query($sql);

    $sql = "CREATE TEMPORARY TABLE `tmp_apbd` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `idrapbd` INT NOT NULL,
                `idrekening` INT NOT NULL,
                `kdrekening` VARCHAR(15) DEFAULT NULL,
                `kdrekview` VARCHAR(15) DEFAULT NULL,
                `nmrekening` VARCHAR(255) DEFAULT NULL,
                `apbd` DECIMAL(20,2) DEFAULT NULL,
                `apbdp` DECIMAL(20,2) DEFAULT NULL,
                `tipe` ENUM('H','D') DEFAULT 'H',
                `level` TINYINT DEFAULT 1,
                PRIMARY KEY (`id`)
            ) ENGINE=INNODB DEFAULT CHARSET=utf8";
    $this->db->query($sql);

    // hittung level max
    $this->db->select('MAX(b.level) AS maxlevel')
      ->from('trx_rapbd a')
      ->join('mst_rekening b', 'b.id=a.idrekening')
      ->where('a.iddinas', $dinas)
      ->where('a.tahun', $tahun);
    $query = $this->db->get();
    $row = $query->row_array();
    $maxlevel = $row['maxlevel'];

    $i = 1;
    $sqlend = '';
    $sqlbegin = 'INSERT INTO tmp_apbd(`idrapbd`, `idrekening`, `kdrekening`, `kdrekview`, `nmrekening`, `apbd`, `apbdp`, `tipe`, `level`) ';
    $sqlend = '';

    while ($i < $maxlevel) {
      $sqlend .= 'INNER JOIN mst_rekening ' . chr(98 + $i) . ' ON ' . chr(98 + $i) . '.id = ' . chr(97 + $i) . '.idheader ';
      $sqlselect = 'SELECT DISTINCT 0, ' . chr(98 + $i) . '.id, ' . chr(98 + $i) . '.kdrekening, ' . chr(98 + $i) . '.kdrekview, ' . chr(98 + $i) . '.nmrekening, fnGetAPBDByRekening(' . chr(98 + $i) . '.id, ' . $dinas . ', ' . $tahun . '), '
        . 'fnGetAPBDPByRekening(' . chr(98 + $i) . '.id, ' . $dinas . ', ' . $tahun . '), '
        . chr(98 + $i) . '.tipe, ' . chr(98 + $i) . '.level ';

      $stmt = $sqlbegin . $sqlselect . 'FROM trx_rapbd a INNER JOIN mst_rekening b ON b.id = a.idrekening ' . $sqlend . 'WHERE a.iddinas=' . $dinas . ' AND a.tahun=' . $tahun;

      $this->db->query($stmt);

      $i++;
    }
  }
}
