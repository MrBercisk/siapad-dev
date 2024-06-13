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
            padding: 10px;
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
    <h3>LAPORAN REALISASI ANGGARAN PENDAPATAN DAERAH</h3>
    <h3>TANGGAL <?= strftime('%d %B %Y') ?></h3>
</div>
<table>
    <thead>
        <tr>
            <th rowspan="2">KODE REKENING</th>
            <th rowspan="2">URAIAN AKUN</th>
            <th rowspan="2">APBD</th>
            <?php if ($apbdp_checkbox): ?>
                <th rowspan="2">APBDP</th>
            <?php endif; ?>
            <th colspan="4">REALISASI PENDAPATAN</th>
            <th colspan="2">SISA LEBIH / KURANG</th>
        </tr>
        <tr>
            <th>S.D HARI LALU</th>
            <th>HARI INI</th>
            <th>S.D HARI INI</th>
            <th>%</th>
            <th>JUMLAH</th>
            <th>%</th>
        </tr>
        <tr>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <?php if ($apbdp_checkbox): ?>
                <th>4</th>
            <?php endif; ?>
            <th>5</th>
            <th>6</th>
            <th>7</th>
            <th>8</th>
            <th>9</th>
            <th>10</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($tablenya)):
            $row_count = 0;
            foreach($tablenya as $tbl):
                $row_count++;
        ?>
                <tr>
                    <td><?= htmlspecialchars($tbl['kdrekening']) ?></td>
                    <td>
                        <strong><?= htmlspecialchars($tbl['nmrek1']) ?></strong><br>
                    </td>
                    <td><?= number_format($tbl['apbd'], 2) ?></td>
                    <?php if ($apbdp_checkbox): ?>
                        <td><?= number_format($tbl['apbdp'], 2) ?></td>
                    <?php endif; ?>
                    <td><?= number_format($tbl['totlalu'], 2) ?></td>
                    <td><?= number_format($tbl['totini'], 2) ?></td>
                    <td><?= number_format($tbl['totlalu'] + $tbl['totini'], 2) ?></td>
                    <td><?= ($tbl['apbd'] > 0) ? number_format((($tbl['totlalu'] + $tbl['totini']) / $tbl['apbd']) * 100, 2) : '0.00' ?></td>
                    <td><?= number_format($tbl['apbd'] - ($tbl['totlalu'] + $tbl['totini']), 2) ?></td>
                    <td><?= ($tbl['apbd'] > 0) ? number_format(($tbl['apbd'] - ($tbl['totlalu'] + $tbl['totini'])) / $tbl['apbd'] * 100, 2) : '0.00' ?></td>
                </tr>
                <tr>
                    <td><?= htmlspecialchars($tbl['kdrek2']) ?></td>
                    <td><?= htmlspecialchars($tbl['nmrek2']) ?></td>
                    <td></td>
                    <?php if ($apbdp_checkbox): ?>
                        <td></td>
                    <?php endif; ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                               
                </tr>
                <tr>
                    <td><?= htmlspecialchars($tbl['kdrek3']) ?></td>
                    <td><?= htmlspecialchars($tbl['nmrek3']) ?></td>
                    <td></td>
                    <?php if ($apbdp_checkbox): ?>
                        <td></td>
                    <?php endif; ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>       
                </tr>
                <tr>
                    <td><?= htmlspecialchars($tbl['kdrek4']) ?></td>
                    <td><?= htmlspecialchars($tbl['nmrek4']) ?></td>
                    <td></td>
                    <?php if ($apbdp_checkbox): ?>
                        <td></td>
                    <?php endif; ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>       
                </tr>
                <tr>
                    <td><?= htmlspecialchars($tbl['kdrek5']) ?></td>
                    <td><?= htmlspecialchars($tbl['nmrek5']) ?></td>
                    <td></td>
                    <?php if ($apbdp_checkbox): ?>
                        <td></td>
                    <?php endif; ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>       
                </tr>
                <tr>
                    <td><?= htmlspecialchars($tbl['kdrek6']) ?></td>
                    <td><?= htmlspecialchars($tbl['nmrek6']) ?></td>
                    <td></td>
                    <?php if ($apbdp_checkbox): ?>
                        <td></td>
                    <?php endif; ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>       
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
