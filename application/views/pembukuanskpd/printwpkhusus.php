<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
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
        .date-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .date-flex .month {
            text-align: left;
        }
        .date-flex .year {
            text-align: right;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>DAFTAR WAJIB PAJAK DENGAN KETERANGAN KHUSUS</h2>
    <h3>TANGGAL <?= htmlspecialchars($tgl_awal_format) ?> S.D <?= htmlspecialchars($tgl_akhir_format) ?></h3>
</div>
<table border="1">
    <thead>
    <tr>
        <th rowspan="2">NO</th>
        <th rowspan="2">Nama Wp</th>
        <th rowspan="2">Alamat/UPT</th>
        <th  colspan="2">Dispensasi</th>
    </tr>

    <tr>
        <th>Tanggal Tutup</th>
        <th>Keterangan</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $bulan_indonesia = array(
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    );
    if (!empty($tablenya)):
        $no = 1;
      
        foreach ($tablenya as $row):
            $tglawal_format = !empty($row['tglawal']) ? strftime('%d %B %Y', strtotime($row['tglawal'])) : '-';
            $tglakhir_format = !empty($row['tglakhir']) ? strftime('%d %B %Y', strtotime($row['tglakhir'])) : '-';
    
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td style="text-align: left;"><?= htmlspecialchars($row['nmwp']) ?></td>
                <td style="text-align: left;"><?= htmlspecialchars($row['alamat']) ?></td>
                <td style="text-align: left;"><?= $tglawal_format; ?> s/d <?= $tglakhir_format; ?></td>
                <td style="text-align: left;"><?= htmlspecialchars($row['keterangan']) ?></td>
            </tr>
            <?php
        endforeach;      
        ?>
         <?php else: ?>
        <tr>
        <td></td>
        <td colspan="4" style="text-align: center;">Tidak Ada Data</td>
        
    </tr>
    <?php endif; ?>
    </tbody>
</table>

<div class="footer-section">
    <div class="tgl_cetak">
        <p>Bandar Lampung, <?= $tgl_cetak_format ?></p>
    </div>
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
