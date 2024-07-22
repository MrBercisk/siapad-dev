<?php defined('BASEPATH') or exit('No direct script access allowed');
$theme['alert'][] = '';
$theme['main'][]  = '';
if ($this->session->flashdata('message')) :
  $theme['alert'][] = '<div class="alert alert-success">' .
    $this->session->flashdata('message') . '
					</div>';
endif;
$escaped_link = 'transaksi/Apbd/getDinas';
$theme['main'][] = implode($sidebar);
$datatables = '<script type="text/javascript">
$(document).ready(function(){  
 var table = null;

            Swal.fire({
                title: \'Informasi\',
                text: \'Klik Cari Untuk Melakukan Pencarian\',
                icon: \'info\',
                confirmButtonText: \'OK\'
            });
'. $jsedit  . $jsdelete . '  
});
</script>

';
$theme['main'][] =
  '<div id="page-title" class="page-title" data-title="' . $title . '"></div>
    <div class="main-content">
        <section class="section m-0">
          <div class="section-header">
            <h1>' . $title . '</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="' . base_url() . '"><i class="bx bxs-home"></i>Home</a></div>
              <div class="breadcrumb-item"><a href="#">' . $title . '</a></div>
            </div>
          </div>
          
            <div class="container-fluid">
              <div class="section-body">
                <div class="row">
                  <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                      <div class="card-body px-4 mx-0">
					  	' . implode('', $theme['alert']) . '
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#data" role="tab" aria-controls="home" aria-selected="true">Data</a>
                          </li>
                     
                        </ul>
                          ' . $formCari . '
                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="data" role="tabpanel" aria-labelledby="home-tab">
                          <table class="table table-bordered" style="width:100% !important;" id="syncTable">
                              <thead>                                 
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>NPWPD</th>
                                    <th>WAJIB PAJAK</th>
                                    <th>ALAMAT</th>
                                    <th>KELURAHAN</th>
                                    <th>KECAMATAN</th>
                                    <th>NO.SKPD</th>
                                    <th>MASA PAJAK</th>
                                    <th>TGL.JTH.TMP</th>
                                    <th>THN</th>
                                    <th>JUMLAH</th>
                                    <th>BUNGA</th>
                                    <th>TOTAL</th>
                                    <th>TGL.TERBIT</th>
                                    <th>KETERANGAN</th>
                                </tr>
                              </thead>
                              <tbody>     
                                    '.$datatables.'                         
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
            </div> 
		</section>
      </div>' . implode('', $modalEdit) . implode('', $modalDelete);
echo preg_replace('/\r|\n|\t/', '', implode('', $topbar) . implode('', $theme['main']) . implode('', $footer));
