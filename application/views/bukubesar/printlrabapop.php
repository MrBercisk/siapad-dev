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
    <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
    <h3>BADAN PENDAPATAN DAERAH</h3>
    <h3>BUKU BESAR PEMBANTU PENERIMAAN</h3>
    <h3>REALISASI ANGGARAN PENDAPATAN PAJAK BAPENDA PER WAJIB PAJAK</h3>
    <?php if (!empty($kdrekening)) : ?>
        <h3>REKENING : <?= $kdrekening['nmrekening'] ?></h3>
    <?php endif; ?>
    <?php if (!empty($idwp)) : ?>
        <h3 style="margin-top: 30px;"><u><?= $idwp['nama'] ?></u></h3>
    <?php endif; ?>
    <h3>TAHUN ANGGARAN : <?= htmlspecialchars($format_tahun) ?></h3>
</div>
<div class="sub-header">
    <h3>APBD&nbsp;&nbsp;   : Rp. <?= number_format($total_apbd, 2) ?></h3>
    <h3>APBD-P&nbsp;&nbsp;: Rp. <?= number_format($total_apbdp, 2) ?></h3>
</div>
<table border="1">
    <thead>
    <tr>
        <th rowspan="3">NO</th>
        <?php if (!empty($kdrekening)) : ?>
        <th colspan="5">REALISASI ANGGARAN PENDAPATAN <?= $kdrekening['nmrekening'] ?></th>
        <?php endif; ?>
        <th rowspan="3">Keterangan</th>
    </tr>
    <tr>
        <th rowspan="3">Tanggal</th>
        <th rowspan="3">Masa Pajak</th>
        <th rowspan="3">Jumlah(Rp)</th>
        <th rowspan="1" colspan="2">Saldo</th>
    </tr>
    <tr>
        <th>Saldo(Rp)</th>
        <th>Saldo(%)</th>
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
        $jumlah_total = 0;
        $saldo_kumulatif = 0;

        foreach ($tablenya as $row):
            $tanggal_saat_ini = date('d', strtotime($row['tanggal']));
            $bulan_saat_ini_en = date('F', strtotime($row['tanggal'])); 
            $bulan_saat_ini = $bulan_indonesia[$bulan_saat_ini_en]; 
            $jumlah_total += $row['total'];
            $saldo_kumulatif += $row['total'];
            $persentase = ($total_apbd != 0) ? ($saldo_kumulatif / $total_apbd) * 100 : 0;
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td>
                    <div class="date-flex">
                        <span class="month"><?= htmlspecialchars($bulan_saat_ini) ?></span>
                        <span class="year"><?= htmlspecialchars($tanggal_saat_ini) ?></span>
                    </div>
                </td>
                <td style="text-align: center;"><?= htmlspecialchars($row['masapajak']) ?></td>
                <td style="text-align: right;"><?= number_format($row['total'], 2) ?></td>
                <td style="text-align: right;"><?= number_format($saldo_kumulatif, 2) ?></td>
                <td style="text-align: right;"><?= number_format($persentase, 2) ?>%</td>
                <td style="text-align: right;"><?= htmlspecialchars($row['keterangan']) ?></td>
            </tr>
            <?php
        endforeach;
        
        ?>
        <tr>
            <td></td>
            <td></td>
            <td style="text-align: right;"><b>JUMLAH</b></td>
            <td style="text-align: right;"><?= number_format($jumlah_total, 2) ?></td>
            <td style="text-align: right;"><?= number_format($saldo_kumulatif, 2) ?></td>
            <td style="text-align: right;"><?= number_format($persentase, 2) ?>%</td>
            <td></td>
        </tr>
       
        <?php else: ?>
    <tr>
        <td></td>
        <td style="text-align: center;">Tidak Ada Data</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align: center;">JUMLAH</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
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
