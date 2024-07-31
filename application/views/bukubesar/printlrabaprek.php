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
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: auto;
        }
        table, th, td {
            border: 2px solid black;
            font-size: 8px;
        }
        th {
            padding: 5px;
            text-align: center;
            page-break-inside: avoid;
        }
        td {
            text-align: left;
        }
        tbody td {
            text-align: right;
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
    <h3>BUKU BESAR PEMBANTU PENDAPATAN DAERAH</h3>
    <h3>REALISASI ANGGARAN PAJAK BAPENDA KOTA BANDAR LAMPUNG PER WAJIB PAJAK</h3>
    <?php if (!empty($kdrekening)) : ?>
        <h3>REKENING : <?= $kdrekening['nmrekening'] ?></h3>
    <?php endif; ?>   
    <h3>TAHUN ANGGARAN : <?= $format_tahun ?></h3>
</div>


    <table border="1">
        <thead>
        <tr>
            <th>NO</th>
            <th>Nama Wajib Pajak</th>
            <th>APBD</th>
            <th>APBDP</th>
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
            <th>Jumlah</th>
            <th>Ket</th>
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
    $total_apbd = 0;
    $total_apbdp = 0;
    $total_jan_jumlah = 0;
    $total_jan_pokok = 0;
    $total_jan_bunga = 0;

    $total_feb_jumlah = 0;
    $total_feb_pokok = 0;
    $total_feb_bunga = 0;

    $total_mar_jumlah = 0;
    $total_mar_pokok = 0;
    $total_mar_bunga = 0;

    $total_apr_jumlah = 0;
    $total_apr_pokok = 0;
    $total_apr_bunga = 0;

    $total_mei_jumlah = 0;
    $total_mei_pokok = 0;
    $total_mei_bunga = 0;

    $total_jun_jumlah = 0;
    $total_jun_pokok = 0;
    $total_jun_bunga = 0;

    $total_jul_jumlah = 0;
    $total_jul_pokok = 0;
    $total_jul_bunga = 0;

    $total_aug_jumlah = 0;
    $total_aug_pokok = 0;
    $total_aug_bunga = 0;

    $total_sep_jumlah = 0;
    $total_sep_pokok = 0;
    $total_sep_bunga = 0;

    $total_okt_jumlah = 0;
    $total_okt_pokok = 0;
    $total_okt_bunga = 0;

    $total_nov_jumlah = 0;
    $total_nov_pokok = 0;
    $total_nov_bunga = 0;

    $total_des_jumlah = 0;
    $total_des_pokok = 0;
    $total_des_bunga = 0;

    $total_totjumlah = 0;
    $total_seluruhpokok = 0;
    $total_seluruhbunga = 0;
    if (!empty($tablenya)):
        $no = 1;
        $count = 0;
        
        foreach ($tablenya as $row):  
            $total_apbd += $row['apbd'];
            $total_apbdp += $row['apbdp'];
            $total_jan_jumlah += $row['jan_jumlah'];
            $total_jan_pokok += $row['jan_pokok'];
            $total_jan_bunga += $row['jan_bunga'];

            $total_feb_jumlah += $row['feb_jumlah'];
            $total_feb_pokok += $row['feb_pokok'];
            $total_feb_bunga += $row['feb_bunga'];

            $total_mar_jumlah += $row['mar_jumlah'];
            $total_mar_pokok += $row['mar_pokok'];
            $total_mar_bunga += $row['mar_bunga'];

            $total_apr_jumlah += $row['apr_jumlah'];
            $total_apr_pokok += $row['apr_pokok'];
            $total_apr_bunga += $row['apr_bunga'];

            $total_mei_jumlah += $row['mei_jumlah'];
            $total_mei_pokok += $row['mei_pokok'];
            $total_mei_bunga += $row['mei_bunga'];

            $total_jun_jumlah += $row['jun_jumlah'];
            $total_jun_pokok += $row['jun_pokok'];
            $total_jun_bunga += $row['jun_bunga'];

            $total_jul_jumlah += $row['jul_jumlah'];
            $total_jul_pokok += $row['jul_pokok'];
            $total_jul_bunga += $row['jul_bunga'];

            $total_aug_jumlah += $row['aug_jumlah'];
            $total_aug_pokok += $row['aug_pokok'];
            $total_aug_bunga += $row['aug_bunga'];

            $total_sep_jumlah += $row['sep_jumlah'];
            $total_sep_pokok += $row['sep_pokok'];
            $total_sep_bunga += $row['sep_bunga'];

            $total_okt_jumlah += $row['okt_jumlah'];
            $total_okt_pokok += $row['okt_pokok'];
            $total_okt_bunga += $row['okt_bunga'];

            $total_nov_jumlah += $row['nov_jumlah'];
            $total_nov_pokok += $row['nov_pokok'];
            $total_nov_bunga += $row['nov_bunga'];

            $total_des_jumlah += $row['des_jumlah'];
            $total_des_pokok += $row['des_pokok'];
            $total_des_bunga += $row['des_bunga'];

            $total_totjumlah += $row['totjumlah'];

            $total_seluruhpokok = $total_jan_pokok + $total_feb_pokok + $total_mar_pokok + $total_apr_pokok +
            $total_mei_pokok + $total_jun_pokok + $total_jul_pokok + $total_aug_pokok + $total_sep_pokok + $total_okt_pokok + $total_nov_pokok + $total_des_pokok;
            
            $total_seluruhbunga= $total_jan_bunga + $total_feb_bunga + $total_mar_bunga + $total_apr_bunga +
            $total_mei_bunga + $total_jun_bunga + $total_jul_bunga + $total_aug_bunga + $total_sep_bunga + $total_okt_bunga + $total_nov_bunga + $total_des_bunga;

            $pageBreakClass = ($count % 3 === 0 && $count > 0) ? 'page-break' : 'page-break-none';
            $count++;
        ?>
                
        <tr class="<?= $pageBreakClass ?>">
            <td rowspan="6" style="text-align: center;"><?= $no++ ?></td>
            <td style="text-align: left; padding:5px;"><b><?= htmlspecialchars($row['nmwp']) ?></b><br><br>
            Alamat&nbsp; &nbsp;: <?= htmlspecialchars($row['alamat']) ?> <br>
            NPWPD&nbsp;: <?= htmlspecialchars($row['npwpd']) ?>
            </td>
            <td></td>
            <td ></td>
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
            <td rowspan="6"></td>
        </tr>
    
        <tr>
            <td style="text-align: left;">Nomor SSPD</td>
            <td></td>
            <td></td>
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
            <td></td>
            <td></td>
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
            <td></td>
            <td></td>
            <td style="text-align: center;"><?= number_format($row['jan_pokok'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['feb_pokok'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['mar_pokok'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['apr_pokok'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['mei_pokok'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['jun_pokok'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['jul_pokok'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['aug_pokok'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['sep_pokok'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['okt_pokok'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['nov_pokok'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['des_pokok'],2) ?> </td>
            <td style="text-align: center;"><?= number_format($row['totpokok'],2) ?> </td>
        </tr>
        <tr>
            <td style="text-align: left;">Bunga</td>
            <td></td>
            <td></td>
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
            <td style="text-align: center;"><?= number_format($row['totbunga'],2) ?> </td>
        </tr>
        <tr>
            <td style="text-align: left;">Jumlah yang dibayar(Rp.)</td>
            <td style="text-align: center;"><?= number_format($row['apbd'], 2) ?></td>
            <td style="text-align: center;"><?= number_format($row['apbdp'], 2) ?></td>
            <td style="text-align: center;"><?= number_format($row['jan_jumlah'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['feb_jumlah'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['mar_jumlah'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['apr_jumlah'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['mei_jumlah'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['jun_jumlah'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['jul_jumlah'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['aug_jumlah'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['sep_jumlah'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['okt_jumlah'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['nov_jumlah'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['des_jumlah'],2) ?></td>  
            <td style="text-align: center;"><?= number_format($row['totjumlah'],2) ?></td>  
        </tr>
        <?php endforeach; ?>
        <tr>
            <td rowspan="6" style="text-align: center;"></td>
            <td style="text-align: left; padding:5px;"><b>Jumlah Pokok Pajak(Rp.)</b></td>
            <td></td>
            <td></td>
            <td style="text-align: center;"><?= number_format($total_jan_pokok ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_feb_pokok ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_mar_pokok ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_apr_pokok ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_mei_pokok ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_jun_pokok ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_jul_pokok ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_aug_pokok ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_sep_pokok ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_okt_pokok ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_nov_pokok ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_des_pokok ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_seluruhpokok ,2) ?></td>  
            <td rowspan="6"></td>
        </tr>
        <tr>

            <td style="text-align: left; padding:5px;"><b>Jumlah Bunga(Rp.)</b></td>
            <td></td>
            <td></td>
            <td style="text-align: center;"><?= number_format($total_jan_bunga ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_feb_bunga ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_mar_bunga ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_apr_bunga ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_mei_bunga ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_jun_bunga ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_jul_bunga ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_aug_bunga ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_sep_bunga ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_okt_bunga ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_nov_bunga ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_des_bunga ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_seluruhbunga ,2) ?></td>  
        </tr>
        <tr>
  
            <td style="text-align: left; padding:5px;"><b>Jumlah yang dibayar(Rp.)</b></td>
            <td style="text-align: center;"><?= number_format($total_apbd ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_apbdp ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_jan_jumlah ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_feb_jumlah ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_mar_jumlah ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_apr_jumlah ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_mei_jumlah ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_jun_jumlah ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_jul_jumlah ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_aug_jumlah ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_sep_jumlah ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_okt_jumlah ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_nov_jumlah ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_des_jumlah ,2) ?></td>  
            <td style="text-align: center;"><?= number_format($total_totjumlah ,2) ?></td>  
        
        </tr>
    <?php else: ?>
        <tr>
            <td colspan="6" style="text-align: center;">Tidak Ada Data</td>
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
