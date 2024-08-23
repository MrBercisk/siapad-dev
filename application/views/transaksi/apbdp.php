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
$("#dinas, #tahun").change(function() {
    var tahun = $("#tahun").val();
    var dinas = $("#dinas option:selected").text();
     $("#mtahun").val(tahun);
     $("#mdinas").val(dinas);
     $("#ttambah").removeAttr("disabled");
      $("#ttambah").removeClass("btn-secodary").addClass("btn-success");
        cariData();
    });
  
    var table = $("#ftf").DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "info": false,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "' . site_url($escaped_link) . '",
            type: "POST",
            data: function(d) {
                d.dinas = $("#dinas").val();
                d.tahun = $("#tahun").val();
            }
        },
        "columnDefs": [
            {
                "targets": 0,
                "orderable": false,
                "width": "1%"
            },
            {
                "targets": -1,
                "width": "10%"
            }
        ],
        "drawCallback": function(settings) {
            var api = this.api();
            var start = api.page.info().start;
            api.column(0, { page: "current" }).nodes().each(function(cell, i) {
                cell.innerHTML = start + i + 1;
            });
        },
        "buttons": [
            "copyHtml5",
            "excelHtml5",
            "csvHtml5",
            "pdfHtml5"
        ]
    });

    function cariData() {
        table.ajax.reload();
    }

    
     $("#refres").click(function() { 
     window.location.reload();
    });
    

' . $jsedit  . $jsdelete . '  
});
</script>
<table class="table table-striped" style="width:100% !important;" id="ftf">
   <thead>                                 
     <tr>
         <th>NO</th>
         <th>NAMA DINAS</th>
         <th>KODE REKENING</th>
         <th>APBD</th>
		 <th>APBDP</th>
		 <th></th>
     </tr>
   </thead>
   <tbody>                                 
   </tbody>
</table>';
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

           <div class="section-headers">
           ' . $formCari . '
             <div class="section-header-breadcrumb mr-4">
                 <!-- <div class="breadcrumb-item mr-2"><a href="#" class="btn btn-sm btn-icon icon-left btn-success"><i class="fas fa-plus"></i> TAMBAH</a></div> -->
				         <div class="breadcrumb-items"><a href="#" id="refres" class="btn btn-sm btn-icon icon-left btn-info"><i class="fas fa-sync"></i>REFRESH</a></div>
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
                          <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#insert" role="tab" aria-controls="profile" aria-selected="false">Insert Data</a>
                          </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="data" role="tabpanel" aria-labelledby="home-tab">
                          ' . $datatables . '
                          </div>
                          <div class="tab-pane fade" id="insert" role="tabpanel" aria-labelledby="profile-tab">
						             ' . $forminsert . '
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
