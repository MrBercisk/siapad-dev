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

           </style>              
            
';
        $dinasData = $this->db->get('mst_dinas')->result();
        $opsidin = '';
        foreach ($dinasData as $din) {
            $opsidin .= '<option value="'.$din->id.'">'.$din->nama.'</option>';
        }

        $recData = $this->db->get('trx_stsmaster')->result();
        $opsiRec = '';
        foreach ($recData as $record) {
            $opsiRec .= '<option value="'.$record->id.'">'.$record->nomor.'</option>';
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

                        <form id="editForm" action="'.('PendDaerah/updateData') .'" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="idrecord">Record</label>
                                    <select name="id" id="idrecord" class="form-control select2" data-placeholder="Record" style="width: 100%;">
                                        '.$opsiRec.'
                                    </select>
                                  </div>       
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="iddinas">Nama Dinas</label>
                                    <select name="iddinas" id="iddinas" class="form-control" placeholder="Pilih Nama Dinas" style="width: 100%;">
                                        '.$opsidin.'
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                     <label for="nomor">Nomor:</label>
                                     <input type="text" id="nomor" class="form-control" name="nomor" disabled>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                     <label for="jenis">Jenis:</label>
                                     <input type="text" id="jenis" class="form-control" name="isdispenda" disabled>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                     <label for="tanggal">Tanggal:</label>
                                     <input type="date" id="tanggal" class="form-control" name="tanggal" disabled>
                                  </div>
                                </div>
                                <div class="col-md-6">
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
                              </form>
                            </div>
                            <div class="row mt-3">
                              <div class="col-md-12">
                                
                              </div>
                            </div>
                        </div>
                    </div>
                     
                  </div>
                

                  <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                      <div class="card-body">                   
                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="data" role="tabpanel" aria-labelledby="home-tab">
                            <button type="button" class="btn btn-success mb-2"  id="tambah_data_button" style="display: none;" data-toggle="modal" data-target="#tambahDataModal" style="background-color: #28a745; border-color: #28a745; color:white;">
                             <i class="fas fa-plus"> Tambah</i>  
                            </button> 
                            <button type="button" class="btn btn-danger mb-2"  id="hapus_data" style="display: none;" data-toggle="modal" data-target="#tambahDataModal">
                             <i class="fas fa-times"> Hapus</i>  
                            </button>
                            <button type="button" class="btn btn-secondary mb-2"  id="cari_data" style="display: none;" data-toggle="modal" data-target="#CariModal">
                             <i class="fas fa-binoculars"> Cari</i>  
                            </button>
                       
                              <table class="display" style="width:100% !important;" id="pendapatan">
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
                                      <th></th>
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
                                    <select name="iddinas" id="iddinas" class="form-control" placeholder="Pilih Nama Dinas" style="width: 100%;" required>
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
      <!-- The Modal -->
      <div class="modal" id="tambahDataModal">
        <div class="modal-dialog">
          <div class="modal-content">
          
            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Tambah Record</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
              <form id="forminput" method="post" enctype="multipart/form-data" action="'.site_url('transaksi/PendDaerah/add_data').'">
                     <div class="row">
                     <input type="hidden" id="idrecord" name="idrecord">
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="iddinas">Nama Dinas</label>
                                    <select name="iddinas" id="iddinas" class="form-control" placeholder="Pilih Nama Dinas" style="width: 100%;" required>
                                        '.$opsidin.'
                                    </select>
                                  </div>
                                </div>
                            
                                <div class="col-md-6">
                                  <div class="form-group">
                                     <label for="nomor">Nomor:</label>
                                     <input type="text" id="nobukti" class="form-control" name="nobukti" required>
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="form-group">
                                     <label for="tanggal">Tanggal:</label>
                                     <input type="date" id="tglpajak" class="form-control" name="tglpajak" required>
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
      <!-- Modal for Editing -->
    <!-- Modal for Editing -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form fields for editing -->
                <form id="editForm">
                    <input type="text" name="idstsmaster" id="idstsmaster">
                    <input type="text" name="nourut" id="nourut">
                     <div class="row">
                          <div class="col-md-6">
                                        <div class="form-group">
                                              <div class="form-group">
                                                  <label for="nobukti">Nobukti</label>
                                                  <input type="text" class="form-control" id="nobukti" name="nobukti">
                                              </div>
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <label for="wajibpajak">Wajib Pajak</label>
                                              <input type="text" class="form-control" id="wajibpajak" name="wajibpajak">
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="namarekening">Nama Rekening</label>
                                            <input type="text" class="form-control" id="namarekening" name="namarekening">
                                        </div>
                                      </div>
                                      <div class="col-md-6">            
                                        <div class="form-group">
                                            <label for="uptd">UPTD</label>
                                            <input type="text" class="form-control" id="uptd" name="uptd">
                                        </div>
                                      </div>
                                      <div class="col-md-6">            
                                         <div class="form-group">
                                            <label for="tglpajak">Tanggal Pajak</label>
                                            <input type="date" class="form-control" id="tglpajak" name="tglpajak">
                                        </div>
                                      </div>
                                      <div class="col-md-6">            
                                         <div class="form-group">
                                            <label for="blnpajak">Bulan Pajak</label>
                                            <input type="text" class="form-control" id="blnpajak" name="blnpajak">
                                        </div>
                                      </div>
                                      <div class="col-md-6">            
                                          <div class="form-group">
                                              <label for="thnpajak">Tahun Pajak</label>
                                              <input type="text" class="form-control" id="thnpajak" name="thnpajak">
                                          </div>
                                      </div>
                                      <div class="col-md-6">            
                                                              
                                          <div class="form-group">
                                              <label for="jumlah">Jumlah</label>
                                              <input type="text" class="form-control" id="jumlah" name="jumlah">
                                          </div>
                                      </div>
                                      <div class="col-md-6">            
                                                          
                                        <div class="form-group">
                                            <label for="prs_denda">Persentase Denda</label>
                                            <input type="text" class="form-control" id="prs_denda" name="prs_denda">
                                        </div>
                                      </div>
                                      <div class="col-md-6">            
                                                          
                                         <div class="form-group">
                                              <label for="nil_denda">Nilai Denda</label>
                                              <input type="text" class="form-control" id="nil_denda" name="nil_denda">
                                          </div>
                                      </div>
                                      <div class="col-md-6">            
                                                          
                                                              
                                          <div class="form-group">
                                              <label for="total">Total</label>
                                              <input type="text" class="form-control" id="total" name="total">
                                          </div>
                                      </div>
                                      <div class="col-md-6">            
                                                          
                                                            
                                          <div class="form-group">
                                              <label for="keterangan">Keterangan</label>
                                              <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                                          </div>
                                      </div>
                                      <div class="col-md-6">            
                                                          
                                        <div class="form-group">
                                            <label for="formulir">Formulir</label>
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveChangesBtn">Save changes</button>
            </div>
        </div>
    </div>
</div>


      <div class="modal" id="CariModal">
        <div class="modal-dialog">
          <div class="modal-content">
          
            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Cari SPTPD</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
              <form id="forminput" method="post" enctype="multipart/form-data" action="'.site_url('transaksi/PendDaerah/add_data').'">
                     <div class="row">
                     <input type="hidden" id="idrecord" name="idrecord">
                              <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="iddinas">Nama Dinas</label>
                                    <select name="iddinas" id="iddinas" class="form-control" placeholder="Pilih Nama Dinas" style="width: 100%;" required>
                                        '.$opsidin.'
                                    </select>
                                  </div>
                                </div>
                            
                                <div class="col-md-6">
                                  <div class="form-group">
                                     <label for="nomor">Nomor:</label>
                                     <input type="text" id="nobukti" class="form-control" name="nobukti" required>
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="form-group">
                                     <label for="tanggal">Tanggal:</label>
                                     <input type="date" id="tglpajak" class="form-control" name="tglpajak" required>
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
      '.implode('',$modalEdit).implode('',$modalDelete);
echo preg_replace('/\r|\n|\t/', '', implode('', $topbar) . implode('', $theme['main']) . implode('', $footer));
?>
