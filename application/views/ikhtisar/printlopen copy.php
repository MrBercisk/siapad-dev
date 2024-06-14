<?php 
$theme['main'][]  = '';
$theme['main'][]  = implode($sidebar);
foreach($tablenya as $row){
                $kdrekening = htmlspecialchars($row['kdrekening']);
                $nmdinas = htmlspecialchars($row['nmdinas']);
                $apbd = number_format($row['apbd'], 2);
                $apbdp = number_format($row['apbdp'], 2);
                $totlalu = number_format($row['totlalu'], 2);
                $totini = number_format($row['totini'], 2);
                $tot = number_format($row['totlalu'] + $row['totini'], 2);
                $persen = ($row['apbd'] > 0) ? number_format((($row['totlalu'] + $row['totini']) / $row['apbd']) * 100, 2) : '0.00';
                $sisa = number_format($row['apbd'] - ($row['totlalu'] + $row['totini']), 2);
                $persen_sisa = ($row['apbd'] > 0) ? number_format( ($row['apbd'] - ($row['totlalu'] + $row['totini']))/ $row['apbd'] * 100, 2) : '0.00';
           
$theme['table'][] = '<tr>
                <td> '.$kdrekening.' </td>
                <td> '.$nmdinas.' </td>
                <td> '.$apbd.' </td>
                <td> '.$apbdp.' </td>
                <td> '.$totlalu.' </td>
                <td> '.$totini.' </td>
                <td> '.$tot.' </td>
                <td> '.$persen.' </td>
                <td> '.$sisa.' </td>
                <td> '.$persen_sisa.' </td>
            </tr>';
};
$datatables 	  = '<script type="text/javascript">
						$(document).ready(function() {
							'.$jstable.'	
						});
					 </script>
<table class="table table-striped table-responsive" style="width:100% !important;" id="ftf">
   <thead>                                 
     <tr>
         <th rowspan="2">KODE REKENING</th>
         <th rowspan="2">URAIAN AKUN</th>
         <th rowspan="2">APBD</th>
         <th rowspan="2">APBDP</th>
         <th colspan="4">REALISASI PENDAPATAN</th>
         <th colspan="2">SISA LEBIH / KURANG</th>
     </tr>
	 <tr>
	 	 <th>S.D HARI LALU</th>
		 <th>HARI INI</th>
		 <th>S.D HARI INI</th>
		 <th>%</th>
		 <th>JUMLAH</th>
		 <th>%</th>
	 </tr>
   </thead>
   <tbody>
   		'.implode($theme['table']).'
   </tbody>
</table>';

$theme['main'][] = 
    '<div id="page-title" class="page-title" data-title="'.$title.'"></div>
    <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Wajib Pajak</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="'.base_url().'"><i class="bx bxs-home"></i>Home</a></div>
              <div class="breadcrumb-item"><a href="#">'.$title.'</a></div>
            </div>
          </div>
            <div class="container-fluid">
              <div class="section-body">
                <div class="row">
                  <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                      <div class="card-body">
                        '.$datatables.'
                      </div>
                    </div>
                  </div>
              </div>
            </div>
		</section>
      </div>';
echo preg_replace('/\r|\n|\t/', '', implode($topbar) . implode($theme['main']) . implode($footer));
?>
