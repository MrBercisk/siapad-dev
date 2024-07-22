<?php defined('BASEPATH') or exit('No direct script access allowed');
$theme['alert'][] = '';
$theme['main'][]  = '';
if ($this->session->flashdata('message')) :
  $theme['alert'][] = '<div class="alert alert-success">' .
    $this->session->flashdata('message') . '
					</div>';
endif;
$theme['main'][] = implode($sidebar);
$escaped_link = 'transaksi/FormSptpd/getDinas';
// $cetak = 'transaksi/FormSptpd/Cetak';

$datatables = '<script type="text/javascript">
$(document).ready(function(){
	$("#filters").click(function(){
   var $icon = $(this).find("i");
    if ($icon.hasClass("fa-search")) {
        $icon.removeClass("fa-search").addClass("spinner-border");
        $(this).prop("disabled", true);
    }
    var nomor = $("#nomor").val();
    var rekening = $("#rekening").val();
    var npwpd = $("#npwpd").val();
    var nop = $("#nop").val();
    var wajibpajak = $("#wajibpajak").val();
    var tahun = $("#tahun").val();
        cariData();
  });
    var table = $("#ftf").DataTable({
        
        "processing": false,
        "serverSide": true,
        "searching": false, 
        "paging": true,   

        "ajax": {
            url: "' . site_url($escaped_link) . '",
            type: "POST",
            data: function(d) {
               d.nomor = $("#nomor").val();
               d.rekening = $("#rekening").val();
               d.npwpd = $("#npwpd").val();
               d.nop = $("#nop").val();
               d.wajibpajak = $("#wajibpajak").val();
               d.tahun = $("#tahun").val();
            },
             "error": function(xhr, error, code) {
                var $icon = $("#filters").find("i");
                if ($icon.hasClass("spinner-border")) {
                    $icon.removeClass("spinner-border").addClass("fas fa-search");
                }
                $("#filters").prop("disabled", false);
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

               $("#filters").prop("disabled", false);
                var $icon = $("#filters").find("i");
                if ($icon.hasClass("spinner-border")) {
                    $icon.removeClass("spinner-border").addClass("fas fa-search");
               }
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

' . $jsedit . $jsdelete . $datepick . '  
});
</script>
<table class="table table-striped table-responsive" style="width:100% !important;" id="ftf">
   <thead>                                 
     <tr>
         <th>NO</th>
         <th>Nomor</th>
         <th>Tanggal</th>
         <th>Tgl. Terbit</th>
         <th>Wajib Pajak</th>
         <th>NPWPD</th>
         <th>Rekening</th>
         <th>Bulan</th>
         <th>Tahun</th>
         <th>Pokok</th>
         <th>Denda</th>
         <th>Jumlah</th>
         <th>Keterangan</th>
		 <th></th>
     </tr>
   </thead>
   <tbody>                                 
   </tbody>
</table>';
$select2 = '


';
$theme['main'][] =
  '

  <div id="page-title" class="page-title" data-title="' . $title . '"></div>
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
                          <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#insert" role="tab" aria-controls="profile" aria-selected="false">Insert Data</a>
                          </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="data" role="tabpanel" aria-labelledby="home-tab">
                          <div class="text-right mb-3">
                          <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                           <i class="fas fa-filter"></i> Filters
                          </a>
                           <div class="collapse mb-3" id="collapseExample">
                          ' . $filters . '
                          </div>
                          </div>
                          
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
      </div>
      
      
      ' . implode('', $modalEdit) . implode('', $modalDelete);
echo preg_replace('/\r|\n|\t/', '', implode('', $topbar) . implode('', $theme['main']) . implode('', $footer));
