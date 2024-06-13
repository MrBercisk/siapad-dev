<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
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
        .table-container {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .table-container, .table-container th, .table-container td {
            border: 1px solid black;
        }
        .table-container th, .table-container td {
            padding: 8px;
            text-align: center;
        }
        .table-container th {
            background-color: #f2f2f2;
        }
        .signature {
            font-weight: bold;
            text-align: center;
            margin-top: 60px;
            position: relative;
            float: right;
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
<div class="header">
    <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
    <h3>BADAN PENDAPATAN DAERAH</h3>
    <h4>BUKU PENERIMAAN KAS</h4>
    <h4>PER TANGGAL <?= strftime('%d %B %Y') ?></h4>
</div>
<table class="table-container">
    <thead>
        <tr>
            <th>NO BUKTI</th>
            <th>KODE REKENING</th>
            <th>URAIAN</th>
            <th>DINAS</th>
            <th>JUMLAH</th>
            <th>KETERANGAN</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $tanggal_saat_ini = strftime('%d %B %Y'); 
        $tanggal_sebelumnya = strftime('%d %B %Y', strtotime('-1 day'));
        if (!empty($tablenya)):
            foreach($tablenya as $tbl): ?>
                <tr>
                    <td><?= htmlspecialchars($tbl['nomor'])?></td>
                    <td><?= htmlspecialchars($tbl['kdrekening'])?></td>
                    <td><?= htmlspecialchars($tbl['uraian'])?></td>
                    <td><?= htmlspecialchars($tbl['nmdinas'])?></td>
                    <td><?= number_format($tbl['jumlah'], 2)?></td>
                    <td><?= htmlspecialchars($tbl['keterangan'])?></td>

                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
<?php if (!empty($tanda_tangan)) : ?>
    <div class="signature">
        <p><?= $tanda_tangan['jabatan1'] ?>,</p>
        <p><?= $tanda_tangan['jabatan2'] ?>,</p>
        <p class="name"><?= $tanda_tangan['nama'] ?></p>
        <p>NIP. <?= $tanda_tangan['nip'] ?></p>
    </div>
<?php endif; ?>
    </tbody>
</table>
</body>
</html>
