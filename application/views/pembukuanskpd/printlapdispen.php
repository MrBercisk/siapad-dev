<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
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
        .sub-header h3 {
            font-weight: 400;
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
        td {
            text-align: left;
            padding: 8px;
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
        .signature {
            font-weight: bold;
            text-align: center;
            margin-top: 60px;
            margin-right: 20px;
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
        .signature2 {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin-top: 60px;
            margin-right: 30px;
            position: relative;
            float: right;
            clear: both;
        }
        .signature2 .jabatan1 {
            margin-top: 10px;
        }
        .signature2 .name {
            text-decoration: underline;
            font-weight: bold;
            margin-top: 70px;
        }
    </style>
</head>
<body>

<div class="header">
    <h3>PEMERINTAH KOTA BANDAR LAMPUNG</h3>
    <h3>BADAN PENGELOLA PAJAK DAN RETRIBUSI DAERAH</h3>
    <h3>DISPENSASI PEMBAYARAN PAJAK REKLAME</h3>
    <h3>TAHUN <?= htmlspecialchars($format_tahun) ?></h3>
</div>

<table border="1">
    <thead>
    <tr>
        <th>NO</th>
        <th>No.SKPD</th>
        <th>Tgl.SKPD</th>
        <th>Uraian</th>
        <th>Masa Pajak</th>
        <th>Jumlah</th>
        <th>Keterangan</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (is_array($tablenya) && !empty($tablenya)) {
        $no = 1;
        $total_hari_ini = 0;

        foreach ($tablenya as $row) {
                $total_hari_ini += $row['jumlah'];
                ?>
                <tr>
                    <td style="text-align: center;"><?= htmlspecialchars($no++) ?></td>
                    <td style="text-align: left;"><?= htmlspecialchars($row['noskpd']) ?></td>
                    <td style="text-align: left;"><?= htmlspecialchars($row['tglskpd']) ?></td>
                    <td style="text-align: left;"><?= htmlspecialchars($row['uraian']) ?></td>
                    <td style="text-align: center; padding-"><?= htmlspecialchars($row['masapajak']) ?></td>
                    <td style="text-align: right;"><?= number_format($row['jumlah'], 2) ?></td>
                    <td style="text-align: left;"><?= htmlspecialchars($row['keterangan']) ?></td>
                </tr>
                <?php
            
        }
        ?>
        <tr style="font-weight: bold;">
            <td colspan="5" style="text-align: left;">TOTAL</td>
            <td style="text-align: right;"><?= number_format($total_hari_ini, 2) ?></td>
            <td></td>
        </tr>
    <?php
    } else {
    ?>
        <tr>
            <td colspan="7" style="text-align: center;">Tidak Ada Data</td>
        </tr>
    <?php
    }
    ?>
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
