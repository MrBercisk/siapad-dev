<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <style>
           body {
            font-family: Arial, sans-serif;
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
        }
        .header img {
            max-width: 100px;
        }
        .header h2, .header h3, .header h4 {
            margin: 0;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .sub-header h3 {
            font-weight: 400;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: auto;
        }
        table, th, td {
            border: 2px solid black;
            font-size: 9px;
        }
        th {
            padding: 5px;
            text-align: center;
            page-break-inside: avoid;
            font-weight: bold;
        }
        td {
            text-align: left;
        }
        tbody td {
            text-align: right;
            padding: 2px;
        }
        tbody td:first-child,
        tbody td:nth-child(2) {
            text-align: left;
        }
        .tgl_cetak p {
            text-align: center;
            margin-top: 50px;
            margin-bottom: 50px;
            margin-right: 70px;
            position: relative;
            float: right;
            clear: both;
            font-size: 12px;
        }
        .signature {
            font-weight: bold;
            text-align: center;
            margin-top: 60px;
            margin-right: 20px;
            position: relative;
            float: right;
            clear: both;
            font-size: 12px;
        }
        .signature .jabatan1 {
            margin-top: 30px;
        }
        .signature .name {
            text-decoration: underline;
            font-weight: bold;
            margin-top: 70px;
        }
        .page-break {
            page-break-before: always;
        }
        .page-break-none {
            page-break-before: avoid;
        }

    </style>
</head>
<body>

<div class="header">
    <h3>BUKU PEMBANTU TUNGGAKAN PIUTANG</h3>
    <h3>PAJAK HOTEL,RESTORAN DAN HIBURAN</h3>
    <?php if (!empty($kdrekening)) : ?>
        <h3>REKENING : <?= $kdrekening['nmrekening'] ?></h3>
    <?php endif; ?>   
    <h3>TAHUN <?= $format_tahun ?></h3>
</div>
<div class="sub-header">
    <?php if (!empty($kdrekening)) : ?>
        <h3><b>Rekening&nbsp;&nbsp;  : <?= $kdrekening['nmrekening'] ?></b></h3>
    <?php endif; ?>
  
</div>

    <table border="1">
        <thead>
        <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2">Nama Wajib Pajak</th>
            <th colspan="12">Masa Pajak</th>
            <th rowspan="2">Ket</th>
        </tr>
      <tr>
            <th>Januari</th>
            <th>Februari</th>
            <th>Maret</th>
            <th>April</th>
            <th>Mei</th>
            <th>Juni</th>
            <th>Juli</th>
            <th>Agustus</th>
            <th>September</th>
            <th>Oktober</th>
            <th>November</th>
            <th>Desember</th>
      </tr>
        </thead>
        <tbody>
        <tbody>
    <?php
    $bulan_indonesia = array(
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    );

    if (!empty($tablenya)):
        $no = 1;
        $count = 0;
        
        foreach ($tablenya as $row):  
        
            $pageBreakClass = ($count % 3 === 0 && $count > 0) ? 'page-break' : 'page-break-none';
            $count++;
        ?>
                
        <tr class="<?= $pageBreakClass ?>">
            <td rowspan="12" style="text-align: center;"><?= $no++ ?></td>
            <td style="text-align: left; padding:5px;" ><b><?= htmlspecialchars($row['nmwp']) ?></b><br><br>
            Alamat&nbsp; &nbsp;: <?= htmlspecialchars($row['alamat']) ?> <br>
            NPWPD&nbsp;: <?= htmlspecialchars($row['npwpd']) ?>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
      
        </tr>
    
        <tr>
            <td style="text-align: left;">- Nomor SPTPD</td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jan_nosptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['feb_nosptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['mar_nosptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['apr_nosptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['mei_nosptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jun_nosptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jul_nosptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['aug_nosptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['sep_nosptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['okt_nosptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['nov_nosptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['des_nosptpd']) ?> </td>
          
            <td></td>
        </tr>
        <tr>
            <td style="text-align: left;">Tanggal SPTPD</td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jan_tglsptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['feb_tglsptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['mar_tglsptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['apr_tglsptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['mei_tglsptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jun_tglsptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jul_tglsptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['aug_tglsptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['sep_tglsptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['okt_tglsptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['nov_tglsptpd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['des_tglsptpd']) ?> </td>
           
            <td></td>
        </tr>
        <tr>
            <td style="text-align: left;">Jumlah (Rp)</td>
            <td style="text-align: center;"><?= number_format($row['jan_jmlsptpd'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['feb_jmlsptpd'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['mar_jmlsptpd'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['apr_jmlsptpd'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['mei_jmlsptpd'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['jun_jmlsptpd'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['jul_jmlsptpd'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['aug_jmlsptpd'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['sep_jmlsptpd'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['okt_jmlsptpd'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['nov_jmlsptpd'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['des_jmlsptpd'], 2) ?> </td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align: left;">- Nomor SKPD/SKPD</td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jan_noskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['feb_noskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['mar_noskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['apr_noskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['mei_noskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jun_noskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jul_noskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['aug_noskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['sep_noskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['okt_noskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['nov_noskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['des_noskpdn']) ?> </td>
          
            <td></td>
        </tr>
        <tr>
            <td style="text-align: left;">Tanggal SKPD/SKPD</td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jan_tglskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['feb_tglskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['mar_tglskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['apr_tglskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['mei_tglskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jun_tglskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jul_tglskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['aug_tglskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['sep_tglskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['okt_tglskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['nov_tglskpdn']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['des_tglskpdn']) ?> </td>
           
            <td></td>
        </tr>
        <tr>
            <td style="text-align: left;">Jumlah (Rp)</td>
            <td style="text-align: center;"><?= number_format($row['jan_jmlskpdn'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['feb_jmlskpdn'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['mar_jmlskpdn'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['apr_jmlskpdn'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['mei_jmlskpdn'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['jun_jmlskpdn'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['jul_jmlskpdn'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['aug_jmlskpdn'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['sep_jmlskpdn'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['okt_jmlskpdn'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['nov_jmlskpdn'], 2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['des_jmlskpdn'], 2) ?> </td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align: left;">- Nomor SSPD</td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jan_nosspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['feb_nosspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['mar_nosspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['apr_nosspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['mei_nosspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jun_nosspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jul_nosspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['aug_nosspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['sep_nosspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['okt_nosspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['nov_nosspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['des_nosspd']) ?> </td>
          
            <td></td>
        </tr>
        <tr>
            <td style="text-align: left;">Tanggal SSPD</td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jan_tglsspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['feb_tglsspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['mar_tglsspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['apr_tglsspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['mei_tglsspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jun_tglsspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jul_tglsspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['aug_tglsspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['sep_tglsspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['okt_tglsspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['nov_tglsspd']) ?> </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['des_tglsspd']) ?> </td>
           
            <td></td>
        </tr>
        <tr>
            <td style="text-align: left;">Pokok Pajak</td>
            <td style="text-align: center;"><?= number_format($row['jan_jmlsspd'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['feb_jmlsspd'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['mar_jmlsspd'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['apr_jmlsspd'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['mei_jmlsspd'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['jun_jmlsspd'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['jul_jmlsspd'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['aug_jmlsspd'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['sep_jmlsspd'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['okt_jmlsspd'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['nov_jmlsspd'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['des_jmlsspd'],2) ?> </td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align: left;">Bunga</td>
            <td style="text-align: center;"><?= number_format($row['jan_bunga'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['feb_bunga'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['mar_bunga'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['apr_bunga'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['mei_bunga'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['jun_bunga'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['jul_bunga'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['aug_bunga'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['sep_bunga'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['okt_bunga'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['nov_bunga'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['des_bunga'],2) ?> </td>
            <td></td>
           
        </tr>
        <tr>
            <td style="text-align: left;">Jumlah yang dibayar(Rp.)</td>
            <td style="text-align: center;"><?= number_format($row['jan_total'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['feb_total'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['mar_total'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['apr_total'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['mei_total'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['jun_total'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['jul_total'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['aug_total'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['sep_total'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['okt_total'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['nov_total'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['des_total'],2) ?></td>  
            <td></td>
        </tr>
        <?php endforeach; ?>
       
    <?php else: ?>
        <tr>
            <td colspan="15" style="text-align: center;">Tidak Ada Data</td>
        </tr>
    <?php endif; ?>
</tbody>

    </table>

    <div class="footer-section">
    <div class="tgl_cetak">
        <p>Bandar Lampung, <?= $tgl_cetak_format ?></p>
    </div>
</div>
<?php if (!empty($tanda_tangan)) : ?>
    <div class="signature">
        <p><?= htmlspecialchars($tanda_tangan['jabatan1']) ?></p>
        <p><?= htmlspecialchars($tanda_tangan['jabatan2']) ?>,</p>
        <p class="name"><?= htmlspecialchars($tanda_tangan['nama']) ?></p>
        <p>NIP. <?= htmlspecialchars($tanda_tangan['nip']) ?></p>
    </div>
<?php endif; ?>
</div>
<script>
    function printDiv(divId) {
     var printContents = document.getElementById(divId).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
</body>
</html>
