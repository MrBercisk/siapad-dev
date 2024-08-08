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
    <h3>DAFTAR PENDAPATAN DITERIMA DIMUKA</h3> 
    <h3>TAHUN ANGGARAN : <?= $format_tahun; ?></h3>
</div>
<table>
    <thead>
        <tr>
            <th rowspan="2">NO URUT</th>
            <th rowspan="2">TANGGAL TRANSAKSI</th>
            <th rowspan="2">NAMA OBJEK PAJAK</th>
            <th rowspan="2">UPTD</th>
            <th rowspan="2">MASA PAJAK</th>
            <th rowspan="2">NO SPTPD</th>
            <th rowspan="2">POKOK</th>
            <th rowspan="2">DENDA</th>
            <th rowspan="2">JUMLAH</th>
            <th colspan="5">PERHITUNGAN MASA PAJAK</th>
            <th rowspan="2">PENDAPATAN DITERIMA DIMUKA</th>
        </tr>
        <tr>
            <th>MASA PAJAK</th>
            <th>Tgl Awal</th>
            <th>Tgl Akhir</th>
            <th>Jumlah Hari</th>
            <th></th>
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
    $total_jum = 0;
    $total_denda = 0;
    $total_seluruh = 0;
    $total_seluruh_piutang = 0;
 
    ?>

    <?php foreach ($tablenya as $row): ?>
        <?php
          $total_jum += $row['jumlah'];
          $total_denda += $row['denda'];
          $total_seluruh += $row['total'];
          $total_seluruh_piutang += $row['totdimuka'];
        ?>
        <tr>
            <td style="text-align: center;"><?= $no++ ?></td>
            <td style="text-align: left; padding:5px"><?= htmlspecialchars($row['tglbayar']) ?></td>
            <td style="text-align: left; padding:5px"><?= htmlspecialchars($row['nmwp']) ?></td>
            <td style="text-align: left; padding:5px"><?= htmlspecialchars($row['uptd']) ?></td>
            <td style="text-align: left; padding:5px"><?= htmlspecialchars($row['masapajak']) ?></td>
            <td style="text-align: left; padding:5px"><?= htmlspecialchars($row['nosspd']) ?></td>
            <td style="text-align: right;" ><?= number_format($row['jumlah'],2) ?></td>
            <td style="text-align: right;" ><?= number_format($row['denda'],2)  ?></td>
            <td style="text-align: right;" ><?= number_format($row['total'],2)  ?></td>
            <td style="text-align: left; padding:5px"><?= htmlspecialchars($row['masapajak']) ?> s.d <?= htmlspecialchars($row['jthtempo']) ?></td>
            <td style="text-align: left; padding:5px"><?= htmlspecialchars($row['masapajak']) ?></td>
            <td style="text-align: left; padding:5px"><?= htmlspecialchars($row['jthtempo']) ?></td>
            <td style="text-align: left; padding:5px"></td>
            <td style="text-align: left; padding:5px"></td>
            <td style="text-align: left; padding:5px"><?= number_format($row['totdimuka'],2) ?></td>
         
        </tr>
    <?php endforeach; ?>
   
    <tr>
            <td></td>
            <td colspan="5" style="text-align: center; font-weight:bold;">JUMLAH</td>
            <td style="text-align: center; font-weight:bold;"><?= number_format($total_jum,2) ?></td>
            <td style="text-align: center; font-weight:bold;"><?= number_format($total_denda,2) ?></td>
            <td style="text-align: center; font-weight:bold;"><?= number_format($total_seluruh,2) ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: center; font-weight:bold;"><?= number_format($total_seluruh_piutang,2) ?></td>                    
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