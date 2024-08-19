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
            border: 1px solid black;
            font-size: 10px;
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
            font-size: 10px
        }
        tbody td:first-child,
        tbody td:nth-child(2) {
            text-align: left;
            text-wrap: nowrap;
        }
        .tgl_cetak p {
            text-align: center;
            margin-top: 50px;
            margin-bottom: 50px;
            margin-right: 70px;
            position: relative;
            float: right;
            clear: both;
            font-size: 10px;
        } .signature {
            font-weight: bold;
            text-align: center;
            margin-top: 60px;
            margin-right: 20px;
            position: relative;
            float: right;
            clear: both;
            font-size: 10px;
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
    <h3>LAPORAN REALISASI PENERIMAAN PAJAK HOTEL, RESTORAN, HIBURAN DAN REKLAME</h3>
    <h3>PADA UNIT PELAKSANA TEKNIS DAERAH (UPTD)</h3>
    <?php if (!empty($iduptd)) : ?>
        <h3>UPTD <?= $iduptd['nama'] ?></h3>
    <?php endif; ?>
    <h3>BULAN <?= $format_bulan; ?> <?= $format_tahun; ?></h3>
   
</div>

    <table border="1">
        <thead>
        <?php if($apbdp_checkbox): ?>
        <tr>
            <th rowspan="3">NO</th>
            <th rowspan="3">WILAYAH/JENIS/WAJIB PAJAK</th>
            <th rowspan="3">APBD (Rp)</th>
            <th rowspan="3">APBDP (Rp)</th>
            <th colspan="7">Realisasi</th>
            <th colspan="2" rowspan="2">Sisa Lebih Kurang Anggaran</th>
        </tr>
        <tr>
            <th colspan="2">s.d. BULAN LALU</th>
            <th colspan="2">BULAN INI</th>
            <th colspan="3">s.d. BULAN INI</th>
        </tr>
        <tr>
            <th>JUMLAH TRANSAKSI</th>
            <th>(Rp)</th>
            <th>JUMLAH TRANSAKSI</th>
            <th>(Rp)</th>
            <th>JUMLAH TRANSAKSI</th>
            <th>(Rp)</th>
            <th>%</th>
            <th>(Rp)</th>
            <th>%</th>
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
            <th>9 = 5+7</th>
            <th>10 = 6+8</th>
            <th>11</th>
            <th>12 = 10-4</th>
            <th>13</th>
        </tr>
        <?php else: ?>
            <tr>
            <th rowspan="3">NO</th>
            <th rowspan="3">WILAYAH/JENIS/WAJIB PAJAK</th>
            <th rowspan="3">APBD (Rp)</th>
            <th colspan="7">Realisasi</th>
            <th colspan="2" rowspan="2">Sisa Lebih Kurang Anggaran</th>
        </tr>
        <tr>
            <th colspan="2">s.d. BULAN LALU</th>
            <th colspan="2">BULAN INI</th>
            <th colspan="3">s.d. BULAN INI</th>
        </tr>
        <tr>
            <th>JUMLAH TRANSAKSI</th>
            <th>(Rp)</th>
            <th>JUMLAH TRANSAKSI</th>
            <th>(Rp)</th>
            <th>JUMLAH TRANSAKSI</th>
            <th>(Rp)</th>
            <th>%</th>
            <th>(Rp)</th>
            <th>%</th>
        </tr>
        <tr>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7</th>
            <th>8 = 4+6</th>
            <th>9 = 5+7</th>
            <th>10</th>
            <th>11 = 9-3</th>
            <th>12</th>
        </tr>
        <?php endif; ?>
      
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
           if (!empty($tablenya)) : ?>
                <?php
                $kelompokin = [];
                $totseluruh_apbd = 0;
                $totseluruh_apbdp = 0;
                $totseluruh_jml_sd_hariini = 0;
                $totseluruh_jml_sd_harilalu = 0;
                $totseluruh_tot_sd_hariini = 0;
                $totseluruh_sisa = 0;
                $totseluruh_totini = 0;
            
                foreach ($tablenya as $row) {
                    $kelompokin[$row['nmrekening']][] = $row;
                }
            
                $no = 1;
            
                foreach ($kelompokin as $nmrekening => $rows):
                    $jml_sd_hariini = 0;
                    $jml_sd_harilalu = 0;
                    $tot_sd_hariini = 0;
                    $total_totini = 0;
                    $sisa = 0;
                    $tanpawp = 0; 
            
                    foreach ($rows as $row) {
                        if (!empty($row['nmwp'])) {
                            $tanpawp++;
                        }
                        $jml_sd_hariini += $row['jmlini'];
                        $jml_sd_harilalu += $row['jmllalu'];
                        $tot_sd_hariini += $row['totlalu'];
                        $total_totini += $row['totini'];
                    }
            
                    $totseluruh_jml_sd_hariini += $jml_sd_hariini;
                    $totseluruh_jml_sd_harilalu += $jml_sd_harilalu;
                    $totseluruh_tot_sd_hariini += $tot_sd_hariini;
                    $totseluruh_totini += $total_totini;
            
                    $barisPertama = $rows[0];
            
                    if ($apbdp_checkbox) {
                        $sisa = $tot_sd_hariini + $total_totini - $barisPertama['apbdp'];
                        $persen = $barisPertama['apbdp'] > 0 ? (($tot_sd_hariini + $total_totini) / $barisPertama['apbdp']) * 100 : 0;
                        $persen_sisa = $barisPertama['apbdp'] > 0 ? ($sisa / $barisPertama['apbdp']) * 100 : 0;
                    } else {
                        $sisa = $tot_sd_hariini + $total_totini - $barisPertama['apbd'];
                        $persen = $barisPertama['apbd'] > 0 ? (($tot_sd_hariini + $total_totini) / $barisPertama['apbd']) * 100 : 0;
                        $persen_sisa = $barisPertama['apbd'] > 0 ? ($sisa / $barisPertama['apbd']) * 100 : 0;
                    }
            
                    $totseluruh_sisa += $sisa;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($no++) ?></td>
                        <td style="text-align: left; font-weight: bold;"><?= htmlspecialchars($nmrekening) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($barisPertama['apbd'], 2) ?></td>
                        <?php if ($apbdp_checkbox): ?>
                        <td><b><?= number_format($barisPertama['apbdp'], 2) ?></b></td>
                        <?php endif; ?>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($jml_sd_harilalu) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($tot_sd_hariini, 2) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($jml_sd_hariini) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($total_totini, 2) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($tanpawp) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($tot_sd_hariini + $total_totini, 2) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($persen, 2) ?>%</td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($sisa, 2) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($persen_sisa, 2) ?>%</td>
                    </tr>
                    <?php foreach ($rows as $row): ?>
                        <?php if ($wp && !empty($row['nmwp']) && !empty($row['totlalu']) && !empty($row['totini'])): 
                            $tot_sd_hariini = $row['totlalu'] + $row['totini'];
                        ?>
                        <tr>
                            <td style="text-align: center;"></td>
                            <td style="text-align: left;">
                                <?= !empty($row['nmwp']) ? htmlspecialchars($row['nmwp']) : '' ?>
                            </td>
                            <td></td>
                            <?php if ($apbdp_checkbox): ?>
                            <td></td>
                            <?php endif; ?>
                            <td style="text-align: right;"></td>
                            <td style="text-align: right;"><?= !empty($row['totlalu']) ? number_format($row['totlalu'], 2) : '' ?></td>
                            <td></td>
                            <td style="text-align: right;"><?= !empty($row['totini']) ? number_format($row['totini'], 2) : '' ?></td>
                            <td></td>
                            <td style="text-align: right;"><?= !empty($row['totlalu']) || !empty($row['totini']) ? number_format($tot_sd_hariini, 2) : '' ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach;
                    $totseluruh_apbd += $row['apbd'];
                    $totseluruh_apbdp += $row['apbdp'];
                endforeach; ?>
            
                <tr>
                    <td></td>
                    <td style="text-align: center; font-weight: bold;">TOTAL</td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($totseluruh_apbd, 2) ?></td>
                    <?php if ($apbdp_checkbox): ?>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($totseluruh_apbdp, 2) ?></td>
                    <?php endif; ?>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($totseluruh_jml_sd_harilalu) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($totseluruh_tot_sd_hariini, 2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($totseluruh_jml_sd_hariini) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($totseluruh_totini, 2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($totseluruh_jml_sd_harilalu + $totseluruh_jml_sd_hariini) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($totseluruh_tot_sd_hariini + $totseluruh_totini, 2) ?></td>
                    <td></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($totseluruh_sisa, 2) ?></td>
                    <td></td>
                </tr>
            <?php endif; ?>
            
            
                

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
