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
            position: absolute;
            left: 20px; 
            top: 10px; 
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
            border: 2px solid black;
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
            padding: 2px;
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
<img src="<?= base_url('/assets/img/logo.png') ?>" alt="Logo">
    <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
    <h3>BADAN PENDAPATAN DAERAH</h3>
    <h3>BUKU BESAR PEMBANTU PENERIMAAN</h3>
    <?php if (!empty($idrekheader)) : ?>
        <h3>JENIS PAJAK <?= $idrekheader['nmrekening'] ?></h3>
    <?php endif; ?>
    <?php if (!empty($iduptd)) : ?>
        <h3>UPTD <?= $iduptd['nama'] ?></h3>
    <?php endif; ?>
   
</div>
<div class="sub-header">
    <h3>APBD&nbsp;&nbsp;   : Rp. <?= number_format($total_apbd, 2) ?></h3>
    <h3>APBD-P&nbsp;&nbsp;: Rp. <?= number_format($total_apbdp, 2) ?></h3>
    <h3>BULAN&nbsp;&nbsp;  : <?= $format_bulan; ?> <?= $format_tahun ?>-<?= $format_bulan_akhir; ?> <?= $format_tahun ?></h3>
</div>
    <table border="1">
        <thead>
        <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2">Tanggal</th>
            <th rowspan="2">Nama Wajib Pajak</th>
            <th rowspan="2">Masa Pajak</th>
            <th rowspan="2">Jumlah (Rp)</th>
            <th colspan="2">Saldo</th>
            <th rowspan="2">Keterangan</th>
        </tr>
        <tr>
            <th>(Rp)</th>
            <th>(%)</th>
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
        </tr>
        </thead>
        <tbody>
        <?php
        function bulanKeIndonesia($bulan) {
            $bulanIndo = [
                '01' => 'JAN',
                '02' => 'FEB',
                '03' => 'MAR',
                '04' => 'APR',
                '05' => 'MEI',
                '06' => 'JUN',
                '07' => 'JUL',
                '08' => 'AGU',
                '09' => 'SEP',
                '10' => 'OKT',
                '11' => 'NOV',
                '12' => 'DES'
            ];
            return isset($bulanIndo[$bulan]) ? $bulanIndo[$bulan] : $bulan;
        }

        function bulanKeIndonesia2($bulan) {
            $bulanIndo = [
                '01' => 'Januari',
                '02' => 'Februari',
                '03' => 'Maret',
                '04' => 'April',
                '05' => 'Mei',
                '06' => 'Juni',
                '07' => 'Juli',
                '08' => 'Agustus',
                '09' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember'
            ];
            return isset($bulanIndo[$bulan]) ? $bulanIndo[$bulan] : $bulan;
        }

        if (!empty($tablenya)): ?>
            <?php
            $no = 1;
            $total_hari_ini = 0;
            $data_by_nrekening = [];
            $saldo_awal_data = [];
        
            foreach ($tablenya as $row) {
                $tahun = substr($row['tanggal'], 0, 4);
                $bulan = date('m', strtotime($row['tanggal']));
                $data_by_nrekening[$row['nmrekening']][$tahun][$bulan][] = $row;
                if ($row['issaldoawal'] == 1) {
                    $saldo_awal_data[$row['nmrekening']][$tahun][$bulan] = $row['jumlah'];
                }
            }
        
            ksort($data_by_nrekening);
        
            foreach ($data_by_nrekening as $nmrekening => $years):
                foreach ($years as $tahun => $months):
                    $saldo_awal_data_bulan = $saldo_awal_data[$nmrekening][$tahun] ?? [];
                    $saldo_kumulatif = 0;
                    $total_hari_ini = 0;
        
                    foreach ($months as $bulan => $rows):
                        $saldo_awal = isset($saldo_awal_data_bulan[$bulan]) ? $saldo_awal_data_bulan[$bulan] : 0;
                        $saldo_kumulatif += $saldo_awal;
        
                        $bulan_saat_ini = bulanKeIndonesia2($bulan);
                        $tanggal = date('d', strtotime($rows[0]['tanggal']));
    
                        $saldo_awal_displayed = false;
        
                        foreach ($rows as $row):
                            if ($row['issaldoawal'] == 1):
                                if (!$saldo_awal_displayed):
                                    $saldo_awal_displayed = true;
                                    ?>
                                    <tr>
                                        <td style="text-align: center;"></td>
                                        <td style="text-align: right;"><?= htmlspecialchars($bulan_saat_ini) ?></td>
                                        <td style="text-align: left;">Saldo Awal</td>
                                        <td style="text-align: center;"></td>
                                        <td style="text-align: right;"></td>
                                        <td style="text-align: right;"><?= number_format($saldo_kumulatif, 2) ?></td>
                                        <td style="text-align: right;"><?= number_format(($total_apbd != 0) ? ($saldo_kumulatif / $total_apbd) * 100 : 0, 2) ?>%</td>
                                        <td colspan="2"></td>
                                    </tr>
                                <?php
                                endif;
                                continue;
                            endif;
        
                            $saldo_kumulatif += $row['jumlah'];
                            $total_hari_ini += $row['jumlah'];
                            $persentase = ($total_apbd != 0) ? ($saldo_kumulatif / $total_apbd) * 100 : 0;
        
                            $bulan_pajak = substr($row['masapajak'], 0, 2);
                            $tahun_pajak = substr($row['masapajak'], 2);
                            $masapajak_format = bulanKeIndonesia($bulan_pajak) . ' ' . $tahun_pajak;
                            ?>
                            <tr>
                                <td style="text-align: center;"><?= $no++ ?></td>
                                <td style="text-align: right;"><?= htmlspecialchars(date('d', strtotime($row['tanggal']))) ?></td>
                                <td style="text-align: left;"><?= htmlspecialchars($row['nmwp']) ?></td>
                                <td style="text-align: center;"><?= $masapajak_format ?></td>
                                <td style="text-align: right;"><?= number_format($row['jumlah'], 2) ?></td>
                                <td style="text-align: right;"><?= number_format($saldo_kumulatif, 2) ?></td>
                                <td style="text-align: right;"><?= number_format($persentase, 2) ?>%</td>
                                <td colspan="2" style="text-align: left;"><?= htmlspecialchars($row['keterangan']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <tr style="font-weight: bold;">
                <td></td>
                <td></td>
                <td style="text-align: right;">Jumlah</td>
                <td></td>
                <td><?= number_format($total_hari_ini, 2) ?></td>
                <td><?= number_format($saldo_kumulatif, 2) ?></td>
                <td style="text-align: right;"><?= number_format(($total_apbd != 0) ? ($saldo_kumulatif / $total_apbd) * 100 : 0, 2) ?>%</td>
                <td></td>
            </tr>
        <?php else: ?>
            <tr>
                <td colspan="9" style="text-align: center;">Tidak Ada Data</td>
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
