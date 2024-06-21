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
<div class="header">
    <h2>LAPORAN HARIAN REALISASI PAJAK BAPENDA</h2>
    <h3>BEA PEROLEHAN HAK ATAS TANAH DAN BANGUNAN (BPHTB)</h3>
    <h3>TANGGAL <?= $format_tanggal ?></h3>
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
        <tr>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                    <th>6</th>
                </tr>
    </thead>
    <tbody>
        <?php
        
        if (!empty($tablenya)):
            foreach($tablenya as $tbl): ?>
                
                <tr>
                    <td></td>
                    <td style="text-align: center;"><?= htmlspecialchars($tbl['nosspd']) ?></td>
                    <td style="text-align: center;"><?= htmlspecialchars($tbl['formulir']) ?></td>
                    <td style="text-align: center;"><?= htmlspecialchars($tbl['namawp']) ?></td>
                    <td style="text-align: center;"><?= htmlspecialchars($tbl['nmuptd']) ?></td>
                    <td><?= number_format($tbl['jumlsspd'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                    <td></td>
                    <td colspan="4">Jumlah Per <?= $format_tanggal; ?> </td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4">Jumlah s.d.  </td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4">Jumlah s.d. <?= $format_tanggal; ?> </td>
                    <td></td>
                </tr>
        <?php endif; ?>
    </tbody>
</table>
<div class="tgl_cetak">
    <p>Bandar Lampung, <?= $tgl_cetak_format ?></p>
</div>
<?php if (!empty($tanda_tangan)) : ?>
    <div class="signature">
        <p><?= htmlspecialchars($tanda_tangan['jabatan1']) ?></p>
        <p><?= htmlspecialchars($tanda_tangan['jabatan2']) ?>,</p>
        <p class="name"><?= htmlspecialchars($tanda_tangan['nama']) ?></p>
        <p>NIP. <?= htmlspecialchars($tanda_tangan['nip']) ?></p>
    </div>
<?php endif; ?>
</body>
</html>
