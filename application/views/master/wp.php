<?php
$theme['alert'][] = '';
$theme['main'][]  = '';
if ($this->session->flashdata('message')) :
  $theme['alert'][] = '<div class="alert alert-success">' .
    $this->session->flashdata('message') . '
					</div>';
endif;
$theme['main'][]  = implode($sidebar);
$datatables     = '<script type="text/javascript">
						$(document).ready(function() {
							' . $jstable . $jsedit . $jsdelete . $jslurah . '	
						});
					 </script>
<table class="table table-striped" style="width:100% !important;" id="ftf">
   <thead>                                 
     <tr>
         <th>NO</th>
         <th>NPWPD</th>
         <th>NOP</th>
         <th>REKENING</th>
         <th>NAMA</th>
         <th>ALAMAT</th>
         <th>KECAMATAN</th>
         <th>KELURAHAN</th>
         <th></th>
     </tr>
   </thead>
   <tbody>                                 
   </tbody>
</table>
';
$theme['main'][] =

  '<div id="page-title" class="page-title" data-title="' . $title . '"></div>
    <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Wajib Pajak</h1>
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
                      <div class="card-body">
					  ' . implode('', $theme['alert']) . '
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#data" role="tab" aria-controls="home" aria-selected="true">Data</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#insert" role="tab" aria-controls="profile" aria-selected="false">Insert Data</a>
                          </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="data" role="tabpanel" aria-labelledby="home-tab">
                          ' . $datatables . '
                          </div>
                          <div class="tab-pane fade" id="insert" role="tabpanel" aria-labelledby="profile-tab">
						  ' . $forminsert
  . '
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
