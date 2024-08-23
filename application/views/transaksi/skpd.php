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
						$(document).ready(function() {
         
                    '.$jstable.$jsedit.$jsdelete.'	
                  });
					 </script>
           <style>
            #skpdTable thead th,
            #skpdTable tbody td {
                padding: 10px;
                font-size: 10px;
            }
              .bg-danger {
    background-color: #4CAF50; 
}

           </style>
<table class="table table-bordered" style="width:100% !important;" id="skpdTable">
   <thead>                                 
     <tr>
         <th>NO</th>
         <th>WAJIB PAJAK</th>
         <th>NO. SKPD</th>
         <th>TGL.JTH.TMP</th>
         <th>TEKS</th>
         <th>BLN</th>
         <th>THN</th>
         <th>JUMLAH</th>
         <th>BUNGA</th>
         <th>TOTAL</th>
         <th>TGL BYR</th>
         <th>KETERANGAN</th>
         <th>AKSI</th>
        
     </tr>
   </thead>
   <tbody>                                 
   </tbody>
</table>
';

$theme['main'][] = 
    '<div id="page-title" class="page-title" data-title="SKPD Reklame"></div>
    <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>SKPD Reklame</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="'.base_url().'"><i class="bx bxs-home"></i>Home</a></div>
              <div class="breadcrumb-item"><a href="#">SKPD Reklame</a></div>
            </div>
          </div>
            <div class="container-fluid">
              <div class="section-body">
                <div class="row">
                  <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                      <div class="card-body">
					  '.implode('',$theme['alert']).'
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#data" role="tab" aria-controls="home" aria-selected="true">Data</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#insert" role="tab" aria-controls="profile" aria-selected="false">Insert Data</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="cetak-tab" data-toggle="tab" href="#cetak" role="tab" aria-controls="cetak" aria-selected="false">Cetak Data</a>
                          </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="data" role="tabpanel" aria-labelledby="home-tab">
                          
                    
                          '.$datatables.'
                          </div>
                          <div class="tab-pane fade" id="insert" role="tabpanel" aria-labelledby="profile-tab">
						              '.$forminsert.'
                          </div>
                          <div class="tab-pane fade" id="cetak" role="tabpanel" aria-labelledby="cetak-tab">
						              '.$formcetak.'
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
		</section>
      </div>'.implode('',$modalEdit).implode('',$modalDelete);
echo preg_replace('/\r|\n|\t/', '', implode('', $topbar) . implode('', $theme['main']) . implode('', $footer));
?>
