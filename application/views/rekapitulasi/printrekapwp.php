<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 100px;
        }
        .header h2, .header h3, .header h4 {
            margin: 0;
            margin-bottom: 5px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            table-layout: fixed;
        }
        table, th, td {
            border: 1px solid black;
        }
        th {
            padding: 5px;
            text-align: center;
            font-size: 10px;
        }
        td {
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
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
            margin-bottom: 110px;
            margin-right: 70px;
            position: relative;
            float: right;
            clear: both;
        }
        .signature {
            font-weight: bold;
            text-align: center;
            margin-top: 60px;
            margin-right: 30px;
            position: relative;
            float: right;
            clear: both;
        }
        .signature .jabatan1 {
            margin-top: 30px;
        }
        .signature .name {
            text-decoration: underline;
            font-weight: bold;
            margin-top: 70px;
        }
    </style>
</head>
<body>
<?php
setlocale(LC_ALL, 'id-ID', 'id_ID');
$tanggal_saat_ini = strftime('%d %B %Y');
$tanggal_sebelumnya = strftime('%d %B %Y', strtotime('-1 day'));
?>
<div class="header">
    <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
    <h3>BADAN PENDAPATAN DAERAH</h3>
    <h3>REKAPITULASI REALISASI ANGGARAN PENDAPATAN DAERAH</h3>
    <?php if (!empty($kdrekening)) : ?>
        <h3><?= htmlspecialchars($kdrekening['nmrekening']) ?></h3>
    <?php endif; ?>   
    <h3>TAHUN ANGGARAN : <?= $format_tahun; ?></h3>
</div>
<table>
    <thead>
        <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2">NAMA WAJIB PAJAK</th>
            <th colspan="13">SSPD/STS(Rp)</th>
        </tr>
        <tr>
            <th>JANUARI</th>
            <th>FEBRUARI</th>
            <th>MARET</th>
            <th>APRIL</th>
            <th>MEI</th>
            <th>JUNI</th>
            <th>JULI</th>
            <th>AGUSTUS</th>
            <th>SEPTEMBER</th>
            <th>OKTOBER</th>
            <th>NOVEMBER</th>
            <th>DESEMBER</th>
            <th>JUMLAH</th>
        </tr>
        <tr>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7</th>
            <th>8</th>
            <th>9</th>
            <th>10</th>
            <th>11</th>
            <th>12</th>
            <th>13</th>
            <th>14</th>
            <th>15</th>
          </tr>
    </thead>
    <tbody>
    <?php
usort($tablenya, function($a, $b) {
    return strcmp($a['nmwp'], $b['nmwp']);
});

function formatTanggal($tanggal) {
    $parts = explode('-', $tanggal);

    if (count($parts) == 3) {
        return "{$parts[2]}-{$parts[1]}-{$parts[0]}";
    }
    return $tanggal; 
}
if (!empty($tablenya)): ?>
    <?php
    $no = 1;
    $total_jan = 0;
    $total_feb = 0;
    $total_mar = 0;
    $total_apr = 0;
    $total_mei = 0;
    $total_jun = 0;
    $total_jul = 0;
    $total_aug = 0;
    $total_sep = 0;
    $total_okt = 0;
    $total_nov = 0;
    $total_des = 0;
    $total_seluruh = 0;
    ?>

    <?php foreach ($tablenya as $row): ?>
        <?php
        $row_total = $row['januari'] + $row['februari'] + $row['maret'] + $row['april'] +
                     $row['mei'] + $row['juni'] + $row['juli'] + $row['agustus'] +
                     $row['september'] + $row['oktober'] + $row['november'] + $row['desember'];
                     
        $total_jan += $row['januari'];
        $total_feb += $row['februari'];
        $total_mar += $row['maret'];
        $total_apr += $row['april'];
        $total_mei += $row['mei'];
        $total_jun += $row['juni'];
        $total_jul += $row['juli'];
        $total_aug += $row['agustus'];
        $total_sep += $row['september'];
        $total_okt += $row['oktober'];
        $total_nov += $row['november'];
        $total_des += $row['desember'];
        $total_seluruh += $row_total;
        ?>
        <tr>
            <td style="text-align: center;"><?= $no++ ?></td>
            <td style="text-align: left; padding:5px"><?= htmlspecialchars($row['nmwp']) ?></td>
            <td style="text-align: right;"><?= number_format($row['januari'], 2) ?></td>
            <td style="text-align: right;"><?= number_format($row['februari'], 2) ?></td>
            <td style="text-align: right;"><?= number_format($row['maret'], 2) ?></td>
            <td style="text-align: right;"><?= number_format($row['april'], 2) ?></td>
            <td style="text-align: right;"><?= number_format($row['mei'], 2) ?></td>
            <td style="text-align: right;"><?= number_format($row['juni'], 2) ?></td>
            <td style="text-align: right;"><?= number_format($row['juli'], 2) ?></td>
            <td style="text-align: right;"><?= number_format($row['agustus'], 2) ?></td>
            <td style="text-align: right;"><?= number_format($row['september'], 2) ?></td>
            <td style="text-align: right;"><?= number_format($row['oktober'], 2) ?></td>
            <td style="text-align: right;"><?= number_format($row['november'], 2) ?></td>
            <td style="text-align: right;"><?= number_format($row['desember'], 2) ?></td>
            <td style="text-align: right;"><?= number_format($row_total, 2) ?></td>
        </tr>
    <?php endforeach; ?>
   
    <tr>
            <td ></td>
            <td style="text-align: center; font-weight:bold;">JUMLAH</td>
                                    
            <td style="text-align: right; font-weight:bold;" ><?= number_format($total_jan,2) ?></td>
            <td style="text-align: right; font-weight:bold;" ><?= number_format($total_feb,2) ?></td>
            <td style="text-align: right; font-weight:bold;" ><?= number_format($total_mar,2) ?></td>
            <td style="text-align: right; font-weight:bold;" ><?= number_format($total_apr,2) ?></td>
            <td style="text-align: right; font-weight:bold;" ><?= number_format($total_mei,2) ?></td>
            <td style="text-align: right; font-weight:bold;" ><?= number_format($total_jun,2) ?></td>
            <td style="text-align: right; font-weight:bold;" ><?= number_format($total_jul,2) ?></td>
            <td style="text-align: right; font-weight:bold;" ><?= number_format($total_aug,2) ?></td>
            <td style="text-align: right; font-weight:bold;" ><?= number_format($total_sep,2) ?></td>
            <td style="text-align: right; font-weight:bold;" ><?= number_format($total_okt,2) ?></td>
            <td style="text-align: right; font-weight:bold;" ><?= number_format($total_nov,2) ?></td>
            <td style="text-align: right; font-weight:bold;" ><?= number_format($total_des,2) ?></td>
            <td style="text-align: right; font-weight:bold;" ><?= number_format($total_seluruh,2) ?></td>
        </tr>
   
<?php else: ?>
    <tr>
        <td></td>
        <td colspan="6" style="text-align: center;">Tidak Ada Data</td>
    </tr>
<?php endif; ?>


    </tbody>
</table>

<?php if(!empty($tglcetak)): ?>
    <div class="tgl_cetak">
        <p>Bandar Lampung, <?= strftime('%d %B %Y') ?></p>
    </div>
<?php endif; ?>

<?php if (!empty($tanda_tangan)) : ?>
    <div class="signature">
        <p class="jabatan1"><?= $tanda_tangan['jabatan1'] ?></p>
        <p><?= $tanda_tangan['jabatan2'] ?>,</p>
        <p class="name"><?= $tanda_tangan['nama'] ?></p>
        <p>NIP. <?= $tanda_tangan['nip'] ?></p>
    </div>
<?php endif; ?>

</body>
</html>