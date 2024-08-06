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
            margin-bottom: 40px;
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
            padding-left: 8px;
            padding-right: 8px;
        }
        tbody td {
            text-align: right;
        }
        tbody td:first-child,
        tbody td:nth-child(2) {
            text-align: left;
        }
        .tgl_cetak p {
            font-size: 12px;
            text-align: center;
            margin-top: 50px;
            margin-bottom: 50px;
            margin-right: 50px;
            position: relative;
            float: right;
            clear: both;
        }
        .footer-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
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
<div class="header">
    <h3>PEMERINTAH KOTA BANDAR LAMPUNG</h3>
    <h2><b>BADAN PENDAPATAN DAERAH</b></h2>
    <h3>REKONSILIASI SSPD/STS DAN SKPD/SKPN</h3>
    <?php if (!empty($kdrekening)) : ?>
        <h3><?= $kdrekening['nmrekening'] ?></h3>
    <?php endif; ?> 
    <h3>BULAN :<?= htmlspecialchars($format_bulan) ?> <?= htmlspecialchars($format_tahun) ?></h3>
</div>

<table border="1">
    <thead>
    <tr>
        <th rowspan="2">NO</th>
        <th rowspan="2">Tanggal Transaksi</th>
        <th rowspan="2">No.SKPD/NO.SKPD</th>
        <th rowspan="2">Tanggal SKPD/SKPDN</th>
        <th rowspan="2">UPTD</th>
        <th rowspan="2">Text</th>
        <th rowspan="2">Masa Pajak</th>
        <th colspan="1">Bidang Pembukuan & Pelaporan</th>
        <th colspan="1">Bidang Pendaftaran & Penetapan</th>
        <th rowspan="2">Selisih</th>
        <th rowspan="2">Keterangan</th>
    </tr>
    <tr>
        <th>SSPD/STS(Rp)</th>
        <th>SKPD/SKPD(Rp)</th>
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
        <th>10=8-9</th>
        <th>11</th>
    </tr>
    </thead>
    <tbody>
    <?php
    function formatTanggal($tanggal) {
        $parts = explode('-', $tanggal);

        if (count($parts) == 3) {
           
            return "{$parts[2]}-{$parts[1]}-{$parts[0]}";
        }
        return $tanggal; 
    }
    if (is_array($tablenya) && !empty($tablenya)) {
        $no = 1;
        $total_jmlsts = 0;
        $total_jmlskpd = 0;
        $total_selisih = 0;

        foreach ($tablenya as $row) {
            $total_jmlsts += $row['jmlsts'];
            $total_jmlskpd += $row['jmlskpd'];
            $total_selisih += $row['selisih'];
            ?>
            <tr>
                <td style="text-align: center;"><?= htmlspecialchars($no++) ?></td>
                <td style="text-align: center;"><?= formatTanggal(htmlspecialchars($row['tanggal'])) ?></td>
                <td style="text-align: left;"><?= htmlspecialchars($row['noskp']) ?></td>
                <td style="text-align: center;"><?= formatTanggal(htmlspecialchars($row['tglskp'])) ?></td>
                <td style="text-align: center;"><?= htmlspecialchars($row['uptd']) ?></td>
                <td style="text-align: left;"><?= htmlspecialchars($row['teks']) ?></td>
                <td style="text-align: center;"><?= htmlspecialchars($row['masapajak']) ?></td>
                <td style="text-align: right;"><?= number_format($row['jmlsts'], 2) ?></td>
                <td style="text-align: right;"><?= number_format($row['jmlskpd'], 2) ?></td>
                <td style="text-align: right;"><?= number_format($row['selisih'], 2) ?></td>
                <td style="text-align: left;"><?= htmlspecialchars($row['keterangan']) ?></td>
            </tr>
            <?php
        }
        ?>
    
        <tr style="font-weight: bold;">
            <!-- <td colspan="7" rowspan="2" style="text-align: center;"><b>JUMLAH</b></td> -->
            <td colspan="7" style="text-align: center;"><b>JUMLAH</b></td>
            <td style="text-align: right;">Rp. <?= number_format($total_jmlsts, 2) ?></td>
            <td style="text-align: right;">Rp. <?= number_format($total_jmlskpd, 2) ?></td>
            <td style="text-align: right;"><?= number_format($total_selisih, 2) ?></td>
            <td></td>
        </tr>
     <!--    <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr> -->
    <?php
    } else {
    ?>
        <tr>
            <td colspan="11" style="text-align: center;">Tidak Ada Data</td>
        </tr>
    <?php
    }
    ?>
    </tbody>
</table>

<div class="footer-section">
    <div class="tgl_cetak">
        <p>Bandar Lampung, <?= $tgl_cetak_format ?></p>
    </div>
</div>
<?php if (!empty($tanda_tangan_1)) : ?>
    <div class="signature">
        <p><?= htmlspecialchars($tanda_tangan_1['jabatan1']) ?></p>
        <p><?= htmlspecialchars($tanda_tangan_1['jabatan2']) ?>,</p>
        <p class="name"><?= htmlspecialchars($tanda_tangan_1['nama']) ?></p>
        <p>NIP. <?= htmlspecialchars($tanda_tangan_1['nip']) ?></p>
    </div>
<?php endif; ?>
<?php if (!empty($tanda_tangan_2)) : ?>
    <div class="signature2">
        <p><?= htmlspecialchars($tanda_tangan_2['jabatan1']) ?></p>
        <p><?= htmlspecialchars($tanda_tangan_2['jabatan2']) ?>,</p>
        <p class="name"><?= htmlspecialchars($tanda_tangan_2['nama']) ?></p>
        <p>NIP. <?= htmlspecialchars($tanda_tangan_2['nip']) ?></p>
    </div>
<?php endif; ?>
</body>
</html>
