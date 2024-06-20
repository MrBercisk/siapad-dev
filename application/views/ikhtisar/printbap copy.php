<?php 
$theme['main'][]  = '';
$theme['main'][]  = implode($sidebar);
foreach($tablenya as $row) {
  $nmrek1 = htmlspecialchars($row['nmrek1']);
  $upt = htmlspecialchars($row['upt']);
  $masapajak = htmlspecialchars($row['masapajak']);
  $nomor = htmlspecialchars($row['nomor']);
  $jumlah = number_format($row['jumlah'], 2);
  $persendenda = number_format($row['persendenda'], 2);
  $nilaidenda = number_format($row['nilaidenda'], 2);
  $total = number_format($row['total'], 2);
  $keterangan = htmlspecialchars($row['keterangan']);

  
setlocale(LC_TIME, 'id_ID.utf8');

$tanggal_saat_ini = strftime('%d %B %Y'); 
$tanggal_sebelumnya = strftime('%d %B %Y', strtotime('-1 day')); // Contoh: 6 Juni 2024

$theme['table'][] = '<tr>
                <td> '.$nmrek1.' </td>
                <td> '.$upt.' </td>
                <td> '.$masapajak.' </td>
                <td> '.$nomor.' </td>
                <td> '.$jumlah.' </td>
                <td> '.$persendenda.' </td>
                <td> '.$nilaidenda.' </td>
                <td> '.$total.' </td>
                <td> '.$keterangan.' </td>
  </tr>';

$datatables = '<script type="text/javascript">
					$(document).ready(function() {
						'.$jstable.'	
					});
				 </script>
<table class="table table-striped table-responsive" style="width:100% !important;" id="ftf">
 <thead>                                 
     <tr>
            <th>JENIS PENERIMAAN</th>
            <th>UPT</th>
            <th>MASA PAJAK</th>
            <th>NOMOR</th>
            <th>POKOK PAJAK</th>
            <th>DENDA %</th>
            <th>DENDA JUMLAH</th>
            <th>JUMLAH YG DIBAYAR</th>
            <th>KETERANGAN</th>
     </tr>
   </thead>
   <tbody>
   		'.implode($theme['table']).'
   </tbody>
   <tfoot>
        <tr>
            <td colspan="7">Penerimaan Hari Ini</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="7">Penerimaan Hari Lalu</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="7">Penerimaan s/d Hari Ini</td>
            <td colspan="2"></td>
        </tr>
   </tfoot>
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
            <th>JENIS PENERIMAAN</th>
            <th>UPT</th>
            <th>MASA PAJAK</th>
            <th>NOMOR</th>
            <th>POKOK PAJAK</th>
            <th>DENDA %</th>
            <th>DENDA JUMLAH</th>
            <th>JUMLAH YG DIBAYAR</th>
            <th>KETERANGAN</th>
     </tr>
   </thead>
   <tbody>
   		'.implode($theme['table']).'
   </tbody>
   <tfoot>
        <tr>
            <td colspan="7">Penerimaan Hari Ini</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="7">Penerimaan Hari Lalu</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="7">Penerimaan s/d Hari Ini</td>
            <td colspan="2"></td>
        </tr>
   </tfoot>
</table>';

$theme['main'][] = 
    '<div id="page-title" class="page-title" data-title="'.$title.'"></div>
    <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Cetak Pendapatan BAPENDA</h1>
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
