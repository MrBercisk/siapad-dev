<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ikhtisar Pendapatan Pajak Harian</title>
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
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .tgl_cetak p{
            text-align: center;
            margin-top: 50px;
            margin-bottom: 110px;
            margin-right: 20px;
            position: relative;
            float: right;
            clear: both;
        }
        .signature {
            font-weight: bold;
            text-align: center;
            margin-top: 60px;
            margin-right: 20px;
            position: relative;
            float: right;
            clear: both;
        }
        .signature .jabatan1{
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
 $tanggal_sebelumnya = date('Y-m-d', strtotime('-1 day', strtotime($format_tanggal)));
 $tanggal_sebelumnya_display = strftime('%d %B %Y', strtotime($tanggal_sebelumnya));
 $tgl_cetak_format = strftime('%d %B %Y', strtotime($tgl_cetak));
?>
<div class="header">
    <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
    <h3>BADAN PENDAPATAN DAERAH</h3>
    <h4>IKHTISAR PENDAPATAN PAJAK HARIAN</h4>
    <h4>TANGGAL <?= $format_tanggal ?></h4>
</div>
<table class="table-container">
    <thead>
        <tr>
            <th>NO</th>
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
        <tr>
            <th></th>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7</th>
            <th>8</th>
            <th>9</th>
          </tr>
    </thead>
    <tbody>
    <?php
        if (!empty($tablenya)):
            $no = 1;
            foreach($tablenya as $tbl): ?>
                
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($tbl['nmrek1'])?></td>
                    <td><?= htmlspecialchars($tbl['upt'])?></td>
                    <td><?= htmlspecialchars($tbl['nomor'])?></td>
                    <td><?= htmlspecialchars($tbl['masapajak'])?></td>
                    <td><?= number_format($tbl['jumlah'], 2)?></td>
                    <td><?= htmlspecialchars($tbl['persendenda'])?></td>
                    <td><?= number_format($tbl['nilaidenda'], 2)?></td>
                    <td><?= number_format($tbl['total'], 2)?></td>
                    <td><?= htmlspecialchars($tbl['keterangan'])?></td>
                </tr>
                <?php if (!empty($tbl['nmrek2'])): ?>
                <tr class="nmrek2-row">
                    <td colspan="9"><?= htmlspecialchars($tbl['nmrek2'])?></td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
<div class="tgl_cetak">
        <p>Bandar Lampung, <?= $tgl_cetak_format; ?></p>
    </div>
<?php if (!empty($tanda_tangan)) : ?>
    <div class="signature">
        <p><?= $tanda_tangan['jabatan1'] ?>,</p>
        <p><?= $tanda_tangan['jabatan2'] ?>,</p>
        <p class="name"><?= $tanda_tangan['nama'] ?></p>
        <p>NIP. <?= $tanda_tangan['nip'] ?></p>
    </div>
<?php endif; ?>
</body>
</html>
