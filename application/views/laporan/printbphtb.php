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
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        th {
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
    <h2>LAPORAN HARIAN REALISASI PAJAK BAPENDA</h2>
    <h3>BEA PEROLEHAN HAK ATAS TANAH DAN BANGUNAN (BPHTB)</h3>
    <h3>TANGGAL <?= strftime('%d %B %Y') ?></h3>
</div>
<table>
    <thead>
        <tr>
            <th>NO. URUT</th>
            <th>NO. SSPD</th>
            <th>NOMOR FORMULIR</th>
            <th>NAMA WAJIB PAJAK</th>
            <th>LOKASI OBJEK PAJAK</th>
            <th>JUMLAH SSPD (Rp)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $tanggal_saat_ini = strftime('%d %B %Y'); 
        $tanggal_sebelumnya = strftime('%d %B %Y', strtotime('-1 day'));
        if (!empty($tablenya)):
            foreach($tablenya as $tbl): ?>
                <tr>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                </tr>
                <tr>
                    <td colspan="5">Jumlah Per <?= $tanggal_saat_ini ?> </td>
                    <td><?= number_format($tbl['total'], 2); ?></td>
                </tr>
                <tr>
                    <td colspan="5">Jumlah s.d. <?= $tanggal_sebelumnya ?> </td>
                    <td><?= number_format($tbl['total'] + $tbl['saldoawal'], 2);?></td>
                </tr>
                <tr>
                    <td colspan="5">Jumlah s.d. <?= $tanggal_saat_ini ?> </td>
                    <td><?= number_format($tbl['saldoawal'], 2, ',', '.') ?></td>
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
</body>
</html>
