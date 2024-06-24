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
            margin-bottom: 30px;
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
    <h3>IKHTISAR PENDAPATAN RINCIAN OBJEK PENDAPATAN</h3>
    <?php if (!empty($rekening)) : ?>
        <h3><?= $rekening['nmrekening'] ?></h3>
    <?php endif; ?>
    <h3>BULAN <?= $format_bulan; ?>-<?= $tahun ?></h3>
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
            foreach($tablenya as $tbl): ?>       
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td><?= htmlspecialchars($tbl['tanggal']) ?></td>
                    <td><?= htmlspecialchars($tbl['namawp']) ?></td>
                    <td><?= htmlspecialchars($tbl['singkatanupt']) ?></td>
                    <td><?= htmlspecialchars($tbl['nmuptd']) ?></td>
                    <td style="text-align: right;"><?= number_format($tbl['jumlsspd'], 2) ?></td>
                </tr>
            <?php endforeach; ?>        
        <?php endif; ?>
    </tbody>
</table>

<?php if(!empty($tgl_cetak)): ?>
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