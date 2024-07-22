<?php defined('BASEPATH') or exit('No direct script access allowed');
$theme['alert'][] = '';
$theme['main'][]  = '';
if ($this->session->flashdata('message')) :
  $theme['alert'][] = '<div class="alert alert-success">' .
    $this->session->flashdata('message') . '
					</div>';
endif;
// var_dump(site_url($escaped_link));
// die;
$theme['main'][] = implode($sidebar);
$datatables = '<script type="text/javascript">
$(document).ready(function(){

	' . $datepicker . '  
        $("#nobaris").change(function() {    
            if ($(this).is(":checked")) {
                $("#unaudited").prop("checked", false).prop("disabled",true);
                $("#audited").prop("checked", false).prop("disabled",true);
                $("#apbdp").prop("checked", false).prop("disabled",true);
            }else{
              $("#unaudited").prop("disabled",false);
              $("#audited").prop("disabled",false);
              $("#apbdp").prop("disabled",false);
            }
        });  
        $("#unaudited").change(function() {
            if ($(this).is(":checked")) {
                $("#audited").prop("checked", false);
            }
        });
         $("#audited").change(function() {
            if ($(this).is(":checked")) {
                $("#unaudited").prop("checked", false);
            }
        });
});
</script>';
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
					  	' . implode('', $theme['alert']) . $forminsert . '
                  </div>
              </div>
            </div>
		</section>
      </div>' . implode('', $modalEdit) . implode('', $modalDelete) . $datatables;
echo preg_replace('/\r|\n|\t/', '', implode('', $topbar) . implode('', $theme['main']) . implode('', $footer));
