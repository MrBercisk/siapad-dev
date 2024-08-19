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
            font-size: 12px
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
    <h3>TANGGAL <?= $tgl_awal_format; ?> s.d <?= $tgl_akhir_format; ?></h3>
   
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
            if (!empty($tablenya)) : 
                $kelompokin = [];
                $totseluruh_apbd = 0;
                $totseluruh_apbdp = 0;
                $totseluruh_jml_sd_hariini = 0;
                $totseluruh_jml_sd_harilalu = 0;
                $totseluruh_total_totlalu = 0;
                $totseluruh_tot_sd_harilalu = 0;
                $totsampaihariini = 0;
                $totseluruh_sisa = 0;
                $totseluruh_totini = 0;
                $persensampaihariini = 0;
                $persensisasampaihariini = 0;

                foreach ($tablenya as $row) {
                    $kelompokin[$row['nmuptd']][$row['nmrekening']][] = $row;
                   
                }
            
                $no = 1;
            
                foreach ($kelompokin as $nmuptd => $kelompok_nmrekening) {
                    $total_apbd_nmuptd = 0;
                    $total_apbdp_nmuptd = 0;
                    $total_jml_sd_hariini_nmuptd = 0;
                    $total_jml_sd_harilalu_nmuptd = 0;
                    $total_totlalu_nmuptd = 0;
                    $total_totini_nmuptd = 0;
                    $total_sisa_nmuptd = 0;
                    
                    foreach ($kelompok_nmrekening as $nmrekening => $rows) {
                        /* Cek total apbd baris kedua yaitu pada nmrekening nya */
                        if (isset($rows[0])) {
                            $total_apbd_nmuptd += $rows[0]['apbd']; 
                            $total_apbdp_nmuptd += $rows[0]['apbdp'];
                        }
                        
                        foreach ($rows as $row) {   
                            $total_jml_sd_hariini_nmuptd += $row['jmlini'];
                            $total_jml_sd_harilalu_nmuptd += $row['jmllalu'];
                            $total_totlalu_nmuptd += $row['totlalu'];
                            $total_totini_nmuptd += $row['totini'];
                        }
                    }
                    $totseluruh_apbd += $total_apbd_nmuptd;
                    $totseluruh_apbdp += $total_apbdp_nmuptd;
                    
                    if ($apbdp_checkbox) {
                        $sisasampaihariini = $totsampaihariini - $totseluruh_apbdp;
                        $persensampaihariini = $totseluruh_apbdp > 0 ? ($totsampaihariini / $totseluruh_apbdp) * 100 : 0;
                        $persensisasampaihariini = $totseluruh_apbdp > 0 ?  ($sisasampaihariini / $totseluruh_apbdp) * 100 : 0;
                    } else {
                        $sisasampaihariini = $totsampaihariini - $totseluruh_apbd;
                        $persensampaihariini = $totseluruh_apbd > 0 ? ($totsampaihariini / $totseluruh_apbd) * 100 : 0;
                        $persensisasampaihariini = $totseluruh_apbd > 0 ?  ($sisasampaihariini / $totseluruh_apbd) * 100 : 0;
                    }
                    echo "<tr>
                            <td style='text-align: center;'>{$no}</td>
                            <td style='font-weight: bold;'>{$nmuptd}</td>";
            
                    if ($apbdp_checkbox) {
                        echo "<td style='text-align: right; font-weight: bold;'>".number_format($total_apbd_nmuptd, 2)."</td>
                              <td style='text-align: right; font-weight: bold;'>".number_format($total_apbdp_nmuptd, 2)."</td>";
                    } else {
                        echo "<td style='text-align: right; font-weight: bold;'>".number_format($total_apbd_nmuptd, 2)."</td>";
                    }
            
                    echo "<td style='text-align: right; font-weight: bold;'>".number_format($total_jml_sd_harilalu_nmuptd)."</td>
                          <td style='text-align: right; font-weight: bold;'>".number_format($total_totlalu_nmuptd, 2)."</td>
                          <td style='text-align: right; font-weight: bold;'>".number_format($total_jml_sd_hariini_nmuptd)."</td>
                          <td style='text-align: right; font-weight: bold;'>".number_format($total_totini_nmuptd, 2)."</td>
                          <td style='text-align: right; font-weight: bold;'>".number_format($total_jml_sd_harilalu_nmuptd + $total_jml_sd_hariini_nmuptd)."</td>
                          <td style='text-align: right; font-weight: bold;'>".number_format($total_totlalu_nmuptd + $total_totini_nmuptd, 2)."</td>";
            
                    if ($apbdp_checkbox) {
                        $sisa = $total_totlalu_nmuptd + $total_totini_nmuptd - $total_apbdp_nmuptd;
                        $persen = $total_apbdp_nmuptd > 0 ? (($total_totlalu_nmuptd + $total_totini_nmuptd) / $total_apbdp_nmuptd) * 100 : 0;
                        $persen_sisa = $total_apbdp_nmuptd > 0 ? ($sisa / $total_apbdp_nmuptd) * 100 : 0;
                    } else {
                        $sisa = $total_totlalu_nmuptd + $total_totini_nmuptd - $total_apbd_nmuptd;
                        $persen = $total_apbd_nmuptd > 0 ? (($total_totlalu_nmuptd + $total_totini_nmuptd) / $total_apbd_nmuptd) * 100 : 0;
                        $persen_sisa = $total_apbd_nmuptd > 0 ? ($sisa / $total_apbd_nmuptd) * 100 : 0;
                    }
            
                    echo "<td style='text-align: right; font-weight: bold;'>".number_format($persen, 2)."</td>
                          <td style='text-align: right; font-weight: bold;'>".number_format($sisa, 2)."</td>
                          <td style='text-align: right; font-weight: bold;'>".number_format($persen_sisa, 2)."</td>
                          </tr>";
                    $no++;
            
                    foreach ($kelompok_nmrekening as $nmrekening => $rows) {
                        $total_apbd = 0;
                        $jml_sd_hariini = 0;
                        $jml_sd_harilalu = 0;
                        $total_totlalu = 0;
                        $total_totini = 0;
            
                        foreach ($rows as $row) {
                            $total_apbd += $row['apbd'];
                            $jml_sd_hariini += $row['jmlini'];
                            $jml_sd_harilalu += $row['jmllalu'];
                            $total_totlalu += $row['totlalu'];
                            $total_totini += $row['totini'];
                        }
            
                        $totseluruh_jml_sd_hariini += $jml_sd_hariini;
                        $totseluruh_jml_sd_harilalu += $jml_sd_harilalu;
                        $totseluruh_total_totlalu += $total_totlalu;
                        $totseluruh_totini += $total_totini;
                        $jumsampaihariini = $totseluruh_jml_sd_hariini + $totseluruh_jml_sd_harilalu;
                        $totsampaihariini = $totseluruh_total_totlalu + $totseluruh_totini;
            
                        $barisKedua = $rows[0];
            
                        if ($apbdp_checkbox) {
                            $sisa = $total_totlalu + $total_totini - $barisKedua['apbdp'];
                            $persen = $barisKedua['apbdp'] > 0 ? (($total_totlalu + $total_totini) / $barisKedua['apbdp']) * 100 : 0;
                            $persen_sisa = $barisKedua['apbdp'] > 0 ? ($sisa / $barisKedua['apbdp']) * 100 : 0;
                        } else {
                            $sisa = $total_totlalu + $total_totini - $barisKedua['apbd'];
                            $persen = $barisKedua['apbd'] > 0 ? (($total_totlalu + $total_totini) / $barisKedua['apbd']) * 100 : 0;
                            $persen_sisa = $barisKedua['apbd'] > 0 ? ($sisa / $barisKedua['apbd']) * 100 : 0;
                        }
            
                        $totseluruh_sisa += $sisa;
                        ?>
                        <tr>
                            <td></td>
                            <td style="text-align: left;"><?= strtoupper(htmlspecialchars($nmrekening)) ?></td>
                            <td style="text-align: right;"><?= number_format($barisKedua['apbd'], 2) ?></td>
                            <?php if ($apbdp_checkbox): ?>
                            <td><?= number_format($barisKedua['apbdp'], 2) ?></td>
                            <?php endif; ?>
                            <td style="text-align: right;"><?= number_format($jml_sd_harilalu) ?></td>
                            <td style="text-align: right;"><?= number_format($total_totlalu, 2) ?></td>
                            <td style="text-align: right;"><?= number_format($jml_sd_hariini) ?></td>
                            <td style="text-align: right;"><?= number_format($total_totini, 2) ?></td>
                            <td style="text-align: right;"><?= number_format($jml_sd_harilalu + $jml_sd_hariini) ?></td>
                            <td style="text-align: right;"><?= number_format($total_totlalu + $total_totini, 2) ?></td>
                            <td style="text-align: right;"><?= number_format($persen, 2) ?></td>
                            <td style="text-align: right;"><?= number_format($sisa, 2) ?></td>
                            <td style="text-align: right;"><?= number_format($persen_sisa, 2) ?></td>
                        </tr>
                        <?php foreach ($rows as $row): ?>
                            <?php 
                            if ($wp && !empty($row['nmwp'])) {
                                if (!empty($row['totini']) && (empty($row['totlalu']) || $row['totlalu'] == 0)): 
                                    $total_totlalu = $row['totlalu'] + $row['totini'];
                            ?>
                            <tr>
                                <td style="text-align: center;"></td>
                                <td style="text-align: left; font-weight: 500;">
                                    - <?= htmlspecialchars($row['nmwp']) ?>
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
                                <td style="text-align: right;"><?= !empty($row['totlalu']) || !empty($row['totini']) ? number_format($total_totlalu, 2) : '' ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php 
                                endif;
                            }
                            ?>
                        <?php endforeach;
                    }
                } ?>
                <tr>
                    <td></td>
                    <td style="text-align: center; font-weight: bold;">JUMLAH PENDAPATAN</td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($totseluruh_apbd, 2) ?></td>
                    <?php if ($apbdp_checkbox): ?>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($totseluruh_apbdp, 2) ?></td>
                    <?php endif; ?>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($totseluruh_jml_sd_harilalu) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($totseluruh_total_totlalu, 2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($totseluruh_jml_sd_hariini) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($totseluruh_totini, 2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($jumsampaihariini) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($totsampaihariini,2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($persensampaihariini,2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($sisasampaihariini,2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($persensisasampaihariini) ?></td>

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
