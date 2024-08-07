<?php defined('BASEPATH') or exit('No direct script access allowed');
$theme['alert'][] = '';
$theme['main'][]  = '';
if ($this->session->flashdata('message')) :
  $theme['alert'][] = '<div class="alert alert-success">' .
    $this->session->flashdata('message') . '
					</div>';
endif;
$theme['main'][] = implode($sidebar);
$escaped_link = 'transaksi/SyncSptpd/getApisptpd';
$simpan = 'transaksi/SyncSptpd/aksi';

$datatables = '<script type="text/javascript">
$(document).ready(function(){
  $("#select-all").click(function() {
    var isChecked = $(this).is(":checked");
    $("#ftf tbody input[type=\'checkbox\']").prop("checked", isChecked);
    if (isChecked) {
      $("#ftf tbody tr").addClass("selected");
    } else {
      $("#ftf tbody tr").removeClass("selected");
    }
  });

  $("#ftf tbody").on("click", "input[type=\'checkbox\']", function() {
    var $row = $(this).closest("tr");
    if ($(this).is(":checked")) {
      $row.addClass("selected");
    } else {
      $row.removeClass("selected");
    }
  });

  
  $("#simpan").click(function() {
    var selectedData = [];
    var AKSI = $("#simpan").val();
    $("#ftf tbody input[type=\'checkbox\']:checked").each(function() {
      var row = $(this).closest("tr");
      var rowData = {
        tgl_input:$("#tanggals").val(),
        nomor: row.find("td:eq(2)").text(),
        tanggal: row.find("td:eq(3)").text(),
        wajibPajak: row.find("td:eq(4)").text(),
        npwpd: row.find("td:eq(5)").text(),
        nop: row.find("td:eq(6)").text(),
        namausaha: row.find("td:eq(7)").text(),
        rekening: row.find("td:eq(8)").text(),
        bulan: row.find("td:eq(9)").text(),
        tahun: row.find("td:eq(10)").text(),
        pokok: row.find("td:eq(11)").text(),
        denda: row.find("td:eq(12)").text(),
        noPelaporan: row.find("td:eq(13)").text(),
        jumlah: row.find("td:eq(14)").text(),
        keterangan: row.find("td:eq(15)").text(),
        jenispajak: row.find("td:eq(16)").text()
      };

      selectedData.push(rowData);
    }); 
      if (selectedData.length === 0) {
        Swal.fire({
              icon: "error",
              title: "Gagal menyimpan data",
              text: "Silahkan Pilih Data yang akan di save !",
              showConfirmButton: true
            });
        return; 
      }
        var $icon = $(this).find("i");
        var $button = $(this);
      if ($icon.hasClass("fa-print")) {
          $icon.removeClass("fa-print").addClass("spinner-border");
          $(this).prop("disabled", true);
      }
    $.ajax({
      url: "' . site_url($simpan) . '", 
      type: "POST",
      data: { data: selectedData,AKSI: AKSI },
      success: function(response) {
        if (response.success) {
           Swal.fire({
            icon: "success",
            title: "Data berhasil disimpan",
            showConfirmButton: true
          });
          var $icon = $button.find("i"); 
                if ($icon.hasClass("spinner-border")) {
                    $icon.removeClass("spinner-border").addClass("fas fa-print");
                    $button.prop("disabled", false);
                };
        } else {
              
           Swal.fire({
            icon: "error",
            title: "Gagal menyimpan data",
            text: response.message,
            showConfirmButton: true
          });
             var $icon = $button.find("i"); 
                if ($icon.hasClass("spinner-border")) {
                    $icon.removeClass("spinner-border").addClass("fas fa-print");
                    $button.prop("disabled", false);
                }
      
        };
      },
      error: function(xhr, status, error) { 
        alert("Terjadi kesalahan: " + error);
         var $icon = $(this).find("i");
        if ($icon.hasClass("spinner-border")) {
            $icon.removeClass("spinner-border").addClass("fas fa-print");
            $(this).prop("disabled", true);
        }
      }
    });
  });

	$("#filters").click(function(){
   var $icon = $(this).find("i");
    if ($icon.hasClass("fa-search")) {
        $icon.removeClass("fa-search").addClass("spinner-border");
        $(this).prop("disabled", true);
    }
    var tanggals = $("#tanggals").val();
   
        cariData();
  });
    var table = $("#ftf").DataTable({
        
        "processing": false,
        "serverSide": true,
        "searching": false, 
        "ordering": false,
        "paging": true,   

        "ajax": {
            url: "' . site_url($escaped_link) . '",
            type: "POST",
            data: function(d) {
               d.tanggals = $("#tanggals").val();
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
                "targets": 1,
                "orderable": false,
                "width": "1%"
            },
            {
                "targets": 0,
                "width": "10%"
            }
        ],
        "drawCallback": function(settings) {
            var api = this.api();
            var start = api.page.info().start;
            api.column(1, { page: "current" }).nodes().each(function(cell, i) {
                cell.innerHTML = start + i + 1;
            });

               $("#filters").prop("disabled", false);
                var $icon = $("#filters").find("i");
                if ($icon.hasClass("spinner-border")) {
                    $icon.removeClass("spinner-border").addClass("fas fa-search");
               }
        }
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
     <th><input type="checkbox" id="select-all"></th>
         <th>NO</th>
         <th>Nomor</th>
         <th>Tanggal</th>
         <th>Wajib Pajak</th>
         <th>NPWPD</th>
         <th>NOP</th>
         <th>Nama Usaha</th>
         <th>Rekening</th>
         <th>Bulan</th>
         <th>Tahun</th>
         <th>Pokok</th>
         <th>Denda</th>
         <th>No. Pelaporan</th>
         <th>Jumlah</th>
         <th>Keterangan</th>
         <th>Jenis Pajak</th>
		 
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
                       
                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="data" role="tabpanel" aria-labelledby="home-tab">
                          <div class="text-left mb-3">
                           <div class="mb-3">
                          ' . $filters . '
                          </div>
                          </div>
                          
                          ' . $datatables . '
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
