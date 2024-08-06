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
    <h3>DAFTAR NAMA WAJIB PAJAK HOTEL, RESTORAN DAN HIBURAN</h3>
    <h3>YANG BELUM MEMBAYAR PAJAK S.D. BULAN <?= $format_bulan;?> <?= $format_tahun ?></h3>
</div>

    <table border="1">
        <thead>
        <tr>
            <th>NO</th>
            <th>Jenis Pajak</th>
            <th>Nama Wajib Pajak</th>
            <th>Masa Pajak</th>
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
    function romawi($angka) {
        $angka = intval($angka);
        $hasil = '';

        $romawi = array(
            1000 => 'M',
            900 => 'CM', 
            500 => 'D', 
            400 => 'CD',
            100 => 'C', 
            90 => 'XC', 
            50 => 'L', 
            40 => 'XL',
            10 => 'X', 
            9 => 'IX', 
            5 => 'V', 
            4 => 'IV',
            1 => 'I'
        );

        foreach ($romawi as $nilai => $huruf) {
            $jumlah = intval($angka / $nilai);
            $angka %= $nilai;
            $hasil .= str_repeat($huruf, $jumlah);
        }

        return $hasil;
    }
    if (!empty($tablenya)):
        $no = 1;
        $count = 0;
        $noromawi = 1;
        
        foreach ($tablenya as $row):  
        
            $pageBreakClass = ($count % 3 === 0 && $count > 0) ? 'page-break' : 'page-break-none';
            $count++;
        ?>
                
        <tr class="<?= $pageBreakClass ?>">
            <td style="text-align: center;"><?= $no++ ?></td>
            <td style="text-align: left; padding:5px;" ><b><?= htmlspecialchars($row['nmrek']) ?></b></td>
            <td style="text-align: left; padding:5px;" ><b><?= htmlspecialchars($row['nmwp']) ?></b></td>
            <td style="text-align: left; padding:5px;" ><b><?= htmlspecialchars($row['tunggakan']) ?></b></td>

      
        </tr>
        <?php endforeach; ?>
       
    <?php else: ?>
        <tr>
            <td colspan="4" style="text-align: center;">Tidak Ada Data</td>
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
