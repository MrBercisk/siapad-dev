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
        .sub-header h3{
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
    <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
    <h3>DINAS PENDAPATAN DAERAH</h3>
    <h3>DAFTAR SKPD PAJAK REKLAME</h3>
    <h3>MASA PAJAK <?= $format_bulan?> <?= $format_tahun ?></h3>
</div>
    <table border="1" cellpading="4">
    <thead>
                    <tr style="background-color:#f5f5f5;">
                        <th rowspan="2" width="5%">NO</th>
                        <th colspan="2" width="25%">SURAT KETETAPAN</th>
                        <th rowspan="2" width="20%">NAMA</th>
                        <th rowspan="2" width="25%">ALAMAT</th>
                        <th rowspan="2" width="10%">JUMLAH</th>
                        <th rowspan="2" width="7.5%">BUNGA</th>
                        <th rowspan="2" width="7.5%">TOTAL</th>
                    </tr>
                    <tr style="background-color:#f5f5f5;">
                        <th width="12.5%">TANGGAL</th>
                        <th width="12.5%">NOMOR</th>
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
        $total_hari_ini = 0;
        $total_bunga = 0;
        $total_seluruh = 0;

        foreach ($tablenya as $row):  
            $bulan_saat_ini_en = date('F', strtotime($row->tglskp)); 
            $bulan_saat_ini = $bulan_indonesia[$bulan_saat_ini_en]; 
            $tanggal = date('d', strtotime($row->tglskp));
            $tahun = date('Y', strtotime($row->tglskp));

            if ($bulan_saat_ini == $format_bulan && $tahun == $format_tahun) {
                $total_hari_ini += $row->jumlah;
                $total_bunga += $row->bunga;
                $total_seluruh += $row->total;
    ?>
                
        <tr>
            <td style="text-align: center;"><?= $no++ ?></td>
            <td style="text-align: left;"><?= htmlspecialchars($row->tglskp) ?></td>
            <td style="text-align: left;"><?= htmlspecialchars($row->noskpd) ?></td>
            <td style="text-align: left;"><?= htmlspecialchars($row->wajibpajak) ?></td>
            <td style="text-align: left;"><?= htmlspecialchars($row->alamat) ?></td>
            <td style="text-align: right;"><?= number_format($row->jumlah, 2) ?></td>
            <td style="text-align: right;"><?= number_format($row->bunga, 2) ?></td>
            <td style="text-align: right;"><?= number_format($row->total, 2) ?></td>
        </tr>
    <?php 
            }
        endforeach; 
    ?>
        
        <tr style="font-weight: bold;">
            <td colspan="5" style="text-align: left;">JUMLAH</td>
            <td style="text-align: right;"><?= number_format($total_hari_ini, 2) ?></td>
            <td style="text-align: right;"><?= number_format($total_bunga, 2) ?></td>
            <td style="text-align: right;"><?= number_format($total_seluruh, 2) ?></td>
        </tr>
    <?php else: ?>
        <tr>
            <td colspan="8" style="text-align: center;">Tidak Ada Data</td>
        </tr>
    <?php endif; ?>
</tbody>
    </table>

<div class="footer-section">
    <div class="tgl_cetak">
        <p>Bandar Lampung, <?= $tgl_cetak_format ?></p>
    </div>
</div>
<?php if (!empty($tanda_tangan_1)) : ?>
    <div class="signature">
        <p>Mengetahui,</p>
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
