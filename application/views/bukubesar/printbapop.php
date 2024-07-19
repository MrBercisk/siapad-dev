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
            text-align: center;
            margin-top: 50px;
            margin-bottom: 50px;
            margin-right: 70px;
            position: relative;
            float: right;
            clear: both;
        } .signature {
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
    <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
    <h3>BADAN PENGELOLA PAJAK DAN RETRIBUSI DAERAH</h3>
    <h3>BUKU BESAR PEMBANTU PENERIMAAN</h3>
    <h3>REALISASI ANGGARAN PENDAPATAN BAPENDA PER WAJIB PAJAK</h3>
</div>
<div class="sub-header">
    <?php if (!empty($kdrekening)) : ?>
        <h3>Kode Rekening&nbsp;&nbsp;  : <?= $kdrekening['kdrekening'] ?></h3>
        <h3>Nama Rekening&nbsp;&nbsp;  : <?= $kdrekening['nmrekening'] ?></h3>
    <?php endif; ?>
    <h3>APBD&nbsp;&nbsp;   : Rp. <?= number_format($total_apbd, 2) ?></h3>
    <h3>APBD-P&nbsp;&nbsp;: Rp. <?= number_format($total_apbdp, 2) ?></h3>
    <h3>TAHUN&nbsp;&nbsp;  : <?= $format_tahun ?></h3>
</div>

    <table border="1">
        <thead>
        <tr>
            <th>NO</th>
            <th>Tanggal</th>
            <th>Uraian / No. Bukti</th>
            <th>Ref.</th>
            <th>Jumlah (Rp)</th>
            <th>Jumlah Sampai Dengan Hari Ini (Rp)</th>
        </tr>
        <tr>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
        </tr>
        <tr>
            <th></th>
            <th><?= $format_tahun ?> </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
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
        $saldo_awal = 0;
        $saldo_kumulatif = 0;
        $total_hari_ini = 0;
        $bulan_sebelumnya = null;
        $data_pertama_bulan = array(); 

        foreach ($tablenya as $row):  
            $bulan_saat_ini_en = date('F', strtotime($row['tanggal'])); 
            $bulan_saat_ini = $bulan_indonesia[$bulan_saat_ini_en]; 
            $tanggal = date('d', strtotime($row['tanggal']));

            $saldo_awal = $row['total'];
            $saldo_kumulatif += $row['total'];
            $total_hari_ini += $row['total'];

            if (!isset($data_pertama_bulan[$bulan_saat_ini])) {

                ?>
                <tr>
                    <td></td>
                    <td colspan="5" style="text-align: left; font-weight: bold;"><?= $bulan_saat_ini; ?></td>
                </tr>
                <?php 
                $data_pertama_bulan[$bulan_saat_ini] = true;
            }
        ?>
                
        <tr>
            <td style="text-align: center;"><?= $no++ ?></td>
            <td style="text-align: right;"><?= htmlspecialchars($tanggal) ?></td>
            <td style="text-align: left;"><?= htmlspecialchars($row['nama_wp']) ?></td>
            <td></td>
            <td style="text-align: right;"><?= number_format($row['total'], 2) ?></td>
            <td style="text-align: right;"><?= number_format($saldo_kumulatif, 2) ?></td>
        </tr>
        <?php endforeach; ?>
        
        <tr style="font-weight: bold;">
            <td></td>
            <td></td>
            <td style="text-align: right;">JUMLAH</td>
            <td></td>
            <td style="text-align: right;"><?= number_format($total_hari_ini, 2) ?></td>
            <td style="text-align: right;"><?= number_format($saldo_kumulatif, 2) ?></td>
        </tr>
    <?php else: ?>
        <tr>
            <td colspan="6" style="text-align: center;">Tidak Ada Data</td>
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
</div>
</body>
</html>
