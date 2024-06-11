<?php 
$theme['main'][]  = '';
$theme['main'][]  = implode($sidebar);
foreach($tablenya as $row) {
  $nosspd = htmlspecialchars($row['nosspd']);
  $skpd = htmlspecialchars($row['skpd']);
  $nmwp = htmlspecialchars($row['nmwp']);
  $nmuptd = htmlspecialchars($row['nmuptd']);
  $total = number_format($row['total'], 2);
  $saldoawal = number_format($row['saldoawal'], 2);
  $totalsaldo = number_format($row['total'] + $row['saldoawal'], 2);

  
setlocale(LC_TIME, 'id_ID.utf8');

$tanggal_saat_ini = strftime('%d %B %Y'); 
$tanggal_sebelumnya = strftime('%d %B %Y', strtotime('-1 day')); // Contoh: 6 Juni 2024

$theme['table'][] = '<tr>
      <td>'.$nosspd.'</td>
      <td colspan="3">
          Jumlah Per '.$tanggal_saat_ini.'<br>
          Jumlah s.d '.$tanggal_sebelumnya.'<br>
          Jumlah s.d '.$tanggal_saat_ini.'
      </td>
      <td>
          '.$total.'<br>
          '.$saldoawal.'<br>
          '.$totalsaldo.'
      </td>
  </tr>';

$datatables = '<script type="text/javascript">
					$(document).ready(function() {
						'.$jstable.'	
					});
				 </script>
<table class="table table-striped table-responsive" style="width:100% !important;" id="ftf">
   <thead>                                 
     <tr>
         <th rowspan="2">NO. SSPD</th>
         <th rowspan="2">NOMOR FORMULIR</th>
         <th rowspan="2" colspan="2">NAMA WAJIB PAJAK & LOKASI OBJEK PAJAK</th>
         <th rowspan="2">JUMLAH SSPD</th>
     </tr>
   </thead>
   <tbody>
   		'.implode($theme['table']).'
   </tbody>
</table>';

}

$datatables 	  = '<script type="text/javascript">
						$(document).ready(function() {
							'.$jstable.'	
						});
					 </script>
<table class="table table-striped table-responsive" style="width:100% !important;" id="ftf">
   <thead>                                 
     <tr>
         <th rowspan="2">NO. SSPD</th>
         <th rowspan="2">NOMOR FORMULIR</th>
         <th rowspan="4">NAMA WAJIB PAJAK</th>
         <th rowspan="2">LOKASI OBJEK PAJAK</th>
         <th rowspan="2">jUMLAH SSPD</th>
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
            <h1>Cetak BPHTB</h1>
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
