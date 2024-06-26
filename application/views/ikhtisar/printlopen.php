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
        }
        table, th, td {
            border: 1px solid black;
            font-size: 12px;
        }
        th {
            padding: 5px;
            text-align: center;
        }
        td{
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
        }
        .footer-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
         }
    
        .pembuat {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin-top: 60px;
            margin-right: 30px;
            position: relative;
            float: right;
            clear: both;
        }
        .pembuat .jabatan1 {
            margin-top: 10px;
        }
        .pembuat .name {
            text-decoration: underline;
            font-weight: bold;
            margin-top: 70px;
        }
        .signature {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin-top: 40px;
            margin-left: 30px;
            position: relative;
            float: left;
            clear: both;
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
$total_hari_ini = 0;
$total_denda = 0;

if (!empty($tablenya)):
    foreach($tablenya as $tbl) {
        $total_hari_ini += $tbl['pokok'];
        $total_denda += $tbl['denda_lalu'];
    }
endif;

$total_sampai_hari_ini = $saldo + $total_hari_ini;
?>
<div class="header">
    <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
    <h3>BADAN PENDAPATAN DAERAH</h3>
    <h3>IKHTISAR PENDAPATAN RINCIAN OBJEK PENDAPATAN</h3>
    <?php if (!empty($kdrekening)) : ?>
        <h3><?= $kdrekening['nmrekening'] ?></h3>
    <?php endif; ?>
    <h3>BULAN <?= $format_bulan; ?>-<?= $format_tahun ?></h3>
</div>
<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>TANGGAL TRANSAKSI</th>
            <th>WAJIB PAJAK</th>
            <th>UPT</th>
            <th>Tanggal Terbit SPTPD/SKP</th>
            <th>NOMOR SPTPD/SKPD</th>
            <th>MASA PAJAK</th>
            <th>NOMOR</th>
            <th>POKOK</th>
            <th>DENDA</th>
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
        </tr>
    </thead>
    <tbody>
        <?php
         if (!empty($tablenya)):   
            $no = 1;
            foreach($tablenya as $tbl): 
            $jml = number_format($tbl['pokok'] + $tbl['denda'], 2);
            ?>       
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td><?= htmlspecialchars($tbl['tanggal']) ?></td>
                    <td style="text-align: left;"><?= htmlspecialchars($tbl['nmwp']) ?></td>
                    <td><?= htmlspecialchars($tbl['uptd']) ?></td>
                    <td><?= htmlspecialchars($tbl['tgl']) ?></td>
                    <td style="text-align: right;"><?= htmlspecialchars($tbl['skpd']) ?></td>
                    <td style="text-align: center;"><?= htmlspecialchars($tbl['masapajak'] ) ?></td>
                    <td style="text-align: center;"><?= htmlspecialchars( $tbl['nomor']) ?></td>
                    <td style="text-align: right;"><?= number_format($tbl['pokok'], 2) ?></td>
                    <td style="text-align: right;"><?= number_format($tbl['denda'], 2) ?></td>
                    <td style="text-align: right;"><?= $jml ?></td>
                </tr>
            <?php endforeach; ?>        
        <?php endif; ?>
        
        <tr style=" background-color: #f2f2f2;">
            <td></td>
            <td></td>
            <td colspan="5" style="text-align: left;"><b>JUMLAH</b></td>
            <td></td>
            <td><b><?= number_format($total_hari_ini, 2) ?></b></td>
            <td><b><?= number_format($total_denda, 2) ?></b></td>
            <td><b><?= number_format($total_hari_ini, 2) ?></b></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="5" style="text-align: left;"><b>JUMLAH BULAN INI</b></td>
            <td></td>
            <td><b><?= number_format($total_hari_ini, 2) ?></b></td>
            <td><b><?= number_format($total_denda, 2) ?></b></td>
            <td><b><?= number_format($total_hari_ini, 2) ?></b></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="5" style="text-align: left;"><b>JUMLAH S.D BULAN LALU</b></td>
            <td></td>
            <td><b><?= number_format($saldo, 2) ?></b></td>
            <td><b><?= number_format($total_denda, 2) ?></b></td>
            <td><b><?= number_format($saldo, 2) ?></b></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="5" style="text-align: left;"><b>JUMLAH S.D BULAN INI</b></td>
            <td></td>
            <td><b><?= number_format($total_sampai_hari_ini, 2) ?></b></td>
            <td><b><?= number_format($total_denda, 2) ?></b></td>
            <td><b><?= number_format($total_sampai_hari_ini, 2) ?></b></td>
        </tr>
    </tbody>
</table>


<div class="footer-section">
    <div class="tgl_cetak">
        <p>Bandar Lampung, <?= $tgl_cetak_format ?></p>
    </div>
</div>

<div class="footer-section">
    <?php if (!empty($tanda_tangan)) : ?>
        <div class="signature">
            <p>Mengetahui,</p>
            <p class="jabatan1"><?= $tanda_tangan['jabatan1'] ?></p>
            <p><?= $tanda_tangan['jabatan2'] ?>,</p>
            <p class="name"><?= $tanda_tangan['nama'] ?></p>
            <p>NIP. <?= $tanda_tangan['nip'] ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($pembuat)) : ?>
        <div class="pembuat">
            <p>PEMBUAT DOKUMEN</p>
            <p class="jabatan1"><?= $pembuat['jabatan1'] ?></p>
            <p><?= $pembuat['jabatan2'] ?>,</p>
            <p class="name"><?= $pembuat['nama'] ?></p>
            <p>NIP. <?= $pembuat['nip'] ?></p>
        </div>
    <?php endif; ?>
</div>
</body>
</html>