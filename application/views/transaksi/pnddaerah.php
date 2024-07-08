<?php 

$theme['alert'][] = '';
$theme['main'][]  = '';
if($this->session->flashdata('message')): 
$theme['alert'][] ='<div class="alert alert-success">'.
						$this->session->flashdata('message').'
					</div>';
endif; 
$theme['main'][]  = implode($sidebar);
$datatables 	  = '<script type="text/javascript">
                          
                        $(document).ready(function(){
                        document.getElementById("tahun").value = new Date().getFullYear();

         
   
            '.$jsedit.$jsdelete.'  
            
          });
					 </script>       
           <style>
      
            #submitButton:hover {
                opacity: 0.5; /* Opacity tetap saat kursor di atas tombol */
            }
                .disabled-button {
                opacity: 0.5;
                cursor: not-allowed;
            }

            #pendapatan thead th,
            #pendapatan tbody td {
                padding: 10px;
            }


            .dataTables_wrapper {
                width: 100%;
                overflow-x: auto;
            }
               .action-buttons .btn {
                 margin: 5px 0; /* 
}


           </style>              
            
';
        $dinasData = $this->db->get('mst_dinas')->result();
        $opsidin = '<option></option>';
        foreach ($dinasData as $din) {
            $opsidin .= '<option value="'.$din->id.'">'.$din->nama.'</option>';
        }
        $uptdData = $this->db->get('mst_uptd')->result();
        $opsiUptd = '<option disabled selected>Pilih UPTD</option>';
        foreach ($uptdData as $uptd) {
            $opsiUptd .= '<option value="'.$uptd->id.'">'.$uptd->nama.' ('.$uptd->singkat.')</option>';
        }

        
        $wpData = $this->db->get('mst_wajibpajak')->result();
        $opsiwp = '<option  disabled selected>Pilih WP</option>';
        foreach ($wpData as $wp) {
          $opsiwp .= '<option value="'.$wp->id.'">'.$wp->nama.'</option>';
        }
          $this->db->select('id, nomor');
          $recData = $this->db->get('trx_stsmaster')->result();

          $opsiRec = '<option disabled selected></option>';
          foreach ($recData as $record) {
              $opsiRec .= '<option value="'.$record->id.'">'.$record->nomor.'</option>';
          }

        $idstsmaster = 79959; 

        $apbdData = $this->db
        ->select('trx_rapbd.id,trx_rapbd.idrekening, trx_rapbd.iddinas, mst_rekening.idheader,mst_rekening.kdrekview, mst_rekening.kdrekening, mst_rekening.nmrekening, mst_rekening.islrauptd')
        ->from('trx_rapbd')
        ->join('mst_rekening', 'trx_rapbd.idrekening = mst_rekening.id', 'left')
        ->where('trx_rapbd.iddinas', 1) 
        ->get()
        ->result();
 
        $opsiapbd = '<option disabled selected></option>';
        foreach ($apbdData as $apbd) {
            $opsiapbd .= '<option value="'.$apbd->id.'">'.$apbd->nmrekening.'('.$apbd->kdrekview.')</option>'; // Menggunakan $apbd->idheader untuk nilai option
        }
      
        
        $rekdata = $this->db
        ->select('mst_rekening.id, mst_rekening.kdrekening, mst_rekening.nmrekening, mst_rekening.islrauptd')
        ->from('mst_rekening')
        ->where('mst_rekening.idheader', 3)
        ->get()
        ->result();
        $opsirek = '<option disabled selected>Pilih Rekening</option>';
        foreach ($rekdata as $ttd) {
            $opsirek .= '<option value="'.$ttd->kdrekening.'">'.$ttd->nmrekening.'</option>';
        }
  
          $select_options = '';
          for ($i = 1; $i <= 31; $i++) {
              $select_options .= '<option value="' . $i . '">' . $i . '</option>';
          }

$theme['main'][] = 

    '<div id="page-title" class="page-title" data-title="'.$title.'"></div>
    <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>PENDAPATAN DAERAH</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="'.base_url().'"><i class="bx bxs-home"></i>Home</a></div>
              <div class="breadcrumb-item"><a href="#">'.$title.'</a></div>
            </div>
          </div>
          
            <div class="container-fluid">
              <div class="section-body">
                <div class="row">           
                  <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            '.implode('',$theme['alert']).'
                            <button type="button" class="btn btn mb-2" data-toggle="modal" data-target="#addDataModal" style="background-color: #28a745; border-color: #28a745; color:white;">
                                Tambah Record Baru
                            </button>

                            <form id="editForm" action="'.('PendDaerah/update_record_data') .'" method="post">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="idrecord">Record</label>
                                            <select name="id" id="idrecord" class="form-control select2" data-placeholder="Pilih Record" style="width: 100%;">
                                                '.$opsiRec.'
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="iddinas">Nama Dinas</label>
                                            <select name="iddinas" id="iddinas" class="form-control select2" data-placeholder="Pilih Nama Dinas" style="width: 100%;" disabled>
                                                '.$opsidin.'
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nomor">Nomor:</label>
                                            <input type="text" id="nomor" class="form-control" name="nomor" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="jenis">Jenis:</label>
                                            <input type="text" id="jenis" class="form-control" name="isdispenda" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tanggal">Tanggal:</label>
                                            <input type="date" id="tanggal" class="form-control" name="tanggal" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="bayar">Bayar:</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tmpbayar" id="flexRadioDefault1" disabled>
                                            <label class="form-check-label" for="flexRadioDefault1">
                                                BPPRD
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tmpbayar" id="flexRadioDefault2" disabled>
                                            <label class="form-check-label" for="flexRadioDefault2">
                                                Bank
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="isnonkas" name="isnonkas" disabled>
                                            <label class="form-check-label" for="isnonkas">Non-kas</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="keterangan">Keterangan:</label>
                                        <input type="text" id="keterangan" class="form-control" name="keterangan" disabled>
                                    </div>

                                    <div class="col-md-12 mt-2 text-right">
                                        <button type="button" id="editButton" class="btn btn-primary">Edit</button>
                                        <button type="submit" id="submitButton" class="btn btn-success" class="disabled-button" disabled>Submit</button>
                                    </div>
                                </div>
                  
                            </form>
                        </div>
                    </div>
                </div>

                

                  <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                      <div class="card-body">                   
                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="data" role="tabpanel" aria-labelledby="home-tab">
                                <div id="table-buttons">
                                    <button type="button" class="btn btn-sm btn-success fa fa-plus add-data" id="add-data" data-toggle="modal" data-target="#addModal" style="display: none;"> Tambah</button>
                                    <button type="button" class="btn btn-sm btn-danger fa fa-times delete-all-data" id="hapus_data" style="display: none;" > Hapus</button>
                                    <button type="button" class="btn btn-sm btn-dark fa fa-binoculars search-data" id="cari_data" data-toggle="modal" data-target="#searchModal" style="display: none;"> Cari</button>
                                </div>
                       
                              <table class="table table-bordered table-stripped display" style="width:100% !important;" id="pendapatan">
                              

                                <thead>                                 
                                  <tr>
                                      <th>No. Urut</th>
                                      <th>No. Bukti</th>
                                      <th>Wajib Pajak</th>
                                      <th>Rekening</th>
                                      <th>UPTD</th>
                                      <th>Tgl</th>
                                      <th>Bln</th>
                                      <th>Thn</th>
                                      <th>Jumlah</th>
                                      <th>Denda (%)</th>
                                      <th>Denda (Rp.)</th>
                                      <th>Total (Rp.)</th>
                                      <th>Keterangan</th>
                                      <th>Kode Formulir/sptpd</th>
                                      <th>Kode Bayar</th>
                                      <th>Tanggal Input</th>
                                      <th>No Pelaporan</th>
                                      <th>Aksi</th>
                                  </tr>
                                </thead>
                                <tbody>   
                                      '.$datatables.'                        
                                </tbody>
                              </table>
                          
                          </div>
                          <div class="tab-pane fade" id="insert" role="tabpanel" aria-labelledby="profile-tab">
                           
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
		</section>
      </div>
      
      <!-- The Modal -->
      <div class="modal" id="addDataModal">
        <div class="modal-dialog">
          <div class="modal-content">
          
            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Tambah Record</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
              <form id="forminput" method="post" enctype="multipart/form-data" action="'.site_url('transaksi/PendDaerah/add_record_data').'">
                     <div class="row">
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="iddinas">Nama Dinas</label>
                                    <select name="iddinas" id="iddinas" class="form-control select2" data-placeholder="Pilih Nama Dinas" style="width: 100%;" required>
                                        '.$opsidin.'
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                     <label for="nomor">Nomor:</label>
                                     <input type="text" id="nomor" class="form-control" name="nomor" required>
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="form-group">
                                     <label for="tanggal">Tanggal:</label>
                                     <input type="date" id="tanggal" class="form-control" name="tanggal" required>
                                  </div>
                                </div>
                              <div class="col-md-6">
                                    <label for="bayar">Bayar:</label>
                                    <div class="form-check">               
                                        <input class="form-check-input" type="radio" name="tmpbayar" id="flexRadioDefault1" value="B" required>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            BPPRD
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tmpbayar" id="flexRadioDefault2" value="D" >
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            Bank
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="isnonkas" name="isnonkas" value="1">
                                        <label class="form-check-label" for="isnonkas">Non-kas</label>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="keterangan">Keterangan:</label>
                                    <input type="text" id="keterangan" class="form-control" name="keterangan">
                                </div>  
                            </div>
                          
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">Submit</button>
                          </div>
                          </form>
                  </div>
            
            <!-- Modal Footer -->
         
          
          </div>
        </div>
      </div>
     <div class="modal" id="addModal">
         <div class="modal-dialog modal-lg">
          <div class="modal-content">
          
            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Tambah Record</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
              <form id="formadd" method="post" enctype="multipart/form-data" action="'.site_url('transaksi/PendDaerah/add_data').'">
                     <div class="row">
                                 <input type="hidden" class="form-control" id="idstsmaster" name="idstsmaster">
                                  <input type="hidden" class="form-control" id="nourut" name="nourut">
                            
                            
                                <div class="col-md-6">
                                                  <div class="form-group">
                                                      <label for="nobukti">Nobukti</label>
                                                      <input type="text" class="form-control" id="nobukti" name="nobukti" >
                                                  </div>
                                </div>
                             
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <label for="idwp">Wajib Pajak</label>
                                     <select id="idwp" name="idwp" class="form-control select2" data-placeholder="Pilih WP" style="width: 100%;" required>
                                      '.$opsiwp.'
                                    </select>
                                  </div>
                                </div>
                               <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="idrekening">Rekening</label>
                                     <select id="kdrekening" name="kdrekening" class="form-control" data-placeholder="Pilih Rekening" style="width: 100%;" >
                                      '.$opsiapbd.'
                                    </select>
                                  </div>
                                </div>
                               <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="iddinas">UPTD</label>
                                    <select name="iduptd" id="iduptd" class="form-control" data-placeholder="Pilih UPTD" style="width: 100%;">
                                        '.$opsiUptd.'
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                 <label for="tanggal">Tanggal:</label>
                                    <select class="form-control" id="tglpajak" name="tglpajak" style="width: 100%;" required>
                                        <option value="" disabled selected>Pilih Tanggal</option>
                                          '.$select_options.'
                                    </select>
                                </div>
                                <div class="col-md-4">
                                 <label for="bulan">Bulan:</label>
                                    <select class="form-control" id="blnpajak" name="blnpajak" style="width: 100%;" required>
                                        <option value="" disabled selected>Pilih Bulan</option>
                                        <option value="01">Januari</option>
                                        <option value="02">Februari</option>
                                        <option value="03">Maret</option>
                                        <option value="04">April</option>
                                        <option value="05">Mei</option>
                                        <option value="06">Juni</option>
                                        <option value="07">Juli</option>
                                        <option value="08">Agustus</option>
                                        <option value="09">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                      <label for="tahun">Tahun:</label>
                                      <input type="number" class="form-control" id="thnpajak" name="thnpajak" min="1900" max="9999" value="2024" required>
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                     <label for="jumlah">Jumlah(Rp).</label>
                                       <input type="number" class="form-control jumlah" id="jumlah" name="jumlah" >
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label for="prs_denda">Denda(%).</label>
                                       <input type="number" class="form-control prs_denda" id="prs_denda" name="prs_denda" >
                                 </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label for="nil_denda">Denda(Rp.).</label>
                                      <input type="number" class="form-control nil_denda" id="nil_denda" name="nil_denda">
                                  </div>
                              </div>
                             
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="total">Total(Rp.).</label>
                                      <input type="number" class="form-control total" id="total" name="total" disabled>
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                      <input type="text" class="form-control" id="keterangan" name="keterangan">
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="formulir">Kode Formulir</label>
                                      <input type="text" class="form-control" id="formulir" name="formulir">
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="kodebayar">Kode Bayar</label>
                                      <input type="text" class="form-control" id="kodebayar" name="kodebayar">
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="tgl_input">Tanggal Input</label>
                                      <input type="date" class="form-control" id="tgl_input" name="tgl_input">
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="nopelaporan">Nomor Pelaporan</label>
                                      <input type="text" class="form-control" id="nopelaporan" name="nopelaporan">
                                  </div>
                              </div>


                            </div>
                         
                            
                              <script>
                                  document.getElementById("tahun").value = new Date().getFullYear();
                              </script>
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">Submit</button>
                          </div>
                      </form>
                  </div>
            
         
          
          </div>
        </div>
      </div>
   
    <!-- Modal for Editing -->
    <div class="modal" id="editModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                      <form id="editTableForm" method="post" enctype="multipart/form-data" action="'.site_url('transaksi/PendDaerah/update_data').'">
                        <input type="hidden" name="idstsmaster" id="id="idstsmaster">
                        <input type="hidden" name="nourut" id="id="nourut">
                        <div class="row">
                                <div class="col-md-6">
                                                  <div class="form-group">
                                                      <label for="nobukti">Nobukti</label>
                                                      <input type="text" class="form-control" id="nobukti" name="nobukti" >
                                                  </div>
                                </div>
                             
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <label for="idwp">Wajib Pajak</label>
                                     <select id="idwp2" name="idwp" class="form-control select2" data-placeholder="Pilih WP" style="width: 100%;" >
                                      '.$opsiwp.'
                                    </select>
                                  </div>
                                </div>
                               <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="idrekening">Rekening</label>
                                     <select id="kdrekening" name="idrapbd" class="form-control" data-placeholder="Pilih Rekening" style="width: 100%;" >
                                      '.$opsiapbd.'
                                    </select>
                                  </div>
                                </div>
                               <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="iddinas">UPTD</label>
                                    <select name="iduptd" id="iduptd" class="form-control" data-placeholder="Pilih UPTD" style="width: 100%;">
                                        '.$opsiUptd.'
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                 <label for="tanggal">Tanggal:</label>
                                    <select name="tglpajak" id="tglpajak" class="form-control" style="width: 100%;">
                                      <option value="" disabled selected>Pilih Tanggal</option>
                                      '.$select_options.'
                                    </select>
                                   
                                </div>
                                <div class="col-md-4">
                                 <label for="bulan">Bulan:</label>
                                    <select class="form-control select2" id="blnpajak" name="blnpajak" style="width: 100%;" >
                                        <option value="" disabled selected>Pilih Bulan</option>
                                        <option value="01">Januari</option>
                                        <option value="02">Februari</option>
                                        <option value="03">Maret</option>
                                        <option value="04">April</option>
                                        <option value="05">Mei</option>
                                        <option value="06">Juni</option>
                                        <option value="07">Juli</option>
                                        <option value="08">Agustus</option>
                                        <option value="09">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                      <label for="tahun">Tahun:</label>
                                      <input type="number" class="form-control" id="thnpajak" name="thnpajak" min="1900" max="9999" value="2024" >
                                  </div>
                              </div>
                               <div class="col-md-6">
                                  <div class="form-group">
                                     <label for="jumlah">Jumlah(Rp).</label>
                                       <input type="number" class="form-control jumlah" id="jumlah" name="jumlah">
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label for="prs_denda">Denda(%).</label>
                                       <input type="number" class="form-control prs_denda" id="prs_denda" name="prs_denda">
                                 </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label for="nil_denda">Denda(Rp.).</label>
                                      <input type="number" class="form-control nil_denda" id="nil_denda" name="nil_denda">
                                  </div>
                              </div>
                             
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="total">Total(Rp.).</label>
                                      <input type="number" class="form-control total" id="total" name="total" disabled>
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                      <input type="text" class="form-control" id="keterangan" name="keterangan">
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="formulir">Kode Formulir</label>
                                      <input type="text" class="form-control" id="formulir" name="formulir">
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="kodebayar">Kode Bayar</label>
                                      <input type="text" class="form-control" id="kodebayar" name="kodebayar">
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="tgl_input">Tanggal Input</label>
                                      <input type="date" class="form-control" id="tgl_input" name="tgl_input">
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="nopelaporan">Nomor Pelaporan</label>
                                      <input type="text" class="form-control" id="nopelaporan" name="nopelaporan">
                                  </div>
                              </div>


                            </div>
                         
                            
                              <script>
                                  document.getElementById("tahun").value = new Date().getFullYear();
                              </script>
                                         
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="submitDataButton">Save changes</button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>


      <div class="modal" id="searchModal">
        <div class="modal-dialog">
          <div class="modal-content">
          
            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Cari SPTPD</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                   <form id="getApiForm" >
                     <div class="row">
                                 <input type="hidden" class="form-control" id="idstsmaster" name="idstsmaster">
                                  <input type="hidden" class="form-control" id="nourut" name="nourut">
                                  <input type="hidden" class="form-control" id="nopelaporan" name="nopelaporan">
                              <div class="col-md-12">
                                  <div class="form-group">
                                    <label for="nosptpd">No. SPTPD/No Formulir</label>
                                     <input type="text" id="nosptpd" class="form-control" name="nosptpd"  placeholder="Ketik No SPTPD / No Formulir lalu tekan Enter..." required>                       
                                  </div>
                                </div>
                            
                              <div class="col-md-12">
                                  <div class="form-group">
                                    <label for="NoSSPD">No SSPD</label>
                                     <input type="text" id="NoSSPD" class="form-control" name="nobukti" readonly>                       
                                  </div>
                                </div>

                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="TGLKirim">Tgl Bayar</label>
                                     <input type="text" id="TGLKirim" class="form-control" name="tgl_input" readonly>                       
                                  </div>
                                </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="statusbayar">Status Bayar</label>
                                     <input type="text" id="statusbayar" class="form-control" name="statusbayar" readonly>                       
                                  </div>
                                </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="jumlahbayar">Jumlah Bayar</label>
                                     <input type="text" id="jumlahbayar" class="form-control" name="jumlah" readonly>                       
                                  </div>
                                </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="denda">Denda</label>
                                     <input type="text" id="denda" class="form-control" name="nil_denda" readonly>                       
                                  </div>
                                </div>
                              <div class="col-md-12">
                                  <div class="form-group">
                                    <label for="totalbayar">Total Bayar</label>
                                     <input type="text" id="totalbayar" class="form-control" name="total" readonly>                       
                                  </div>
                                </div>
                            
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="masapajak">Masa Pajak</label>
                                     <input type="text" id="masapajak" class="form-control" name="blnpajak" readonly>                       
                                  </div>
                                </div>
                            
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="tahunpajak">Tahun Pajak</label>
                                     <input type="text" id="tahunpajak" class="form-control" name="thnpajak" readonly>                       
                                  </div>
                                </div>
                              <div class="col-md-12">
                                  <div class="form-group">
                                    <label for="namaop">Nama WP</label>
                                     <input type="text" id="namaop" class="form-control" name="idwp" readonly>                       
                                  </div>
                                </div>
                              <div class="col-md-12">
                                  <div class="form-group">
                                    <label for="jenisop">Jenis Pajak</label>
                                     <input type="text" id="jenisop" class="form-control" name="jenisop" readonly>                       
                                  </div>
                                </div>
                              <div class="col-md-12">
                                  <div class="form-group">
                                    <label for="alamatop">Alamat</label>
                                     <input type="text" id="alamatop" class="form-control" name="alamatop" readonly>                       
                                  </div>
                                </div>
                              <div class="col-md-12">
                                  <div class="form-group">
                                    <label for="npwpd">NPWPD</label>
                                     <input type="text" id="npwpd" class="form-control" name="npwpd" readonly>                       
                                  </div>
                                </div>
                     
                                    
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                              <button type="button" class="btn btn-success" id="fetchDataButton">Ok</button>
                          </div>
                          </form>
                  </div>
                  
            
            <!-- Modal Footer -->
         
          
          </div>
        </div>
      </div>
      '.implode('',$modalEdit).implode('',$modalDelete);
echo preg_replace('/\r|\n|\t/', '', implode('', $topbar) . implode('', $theme['main']) . implode('', $footer));
?>
