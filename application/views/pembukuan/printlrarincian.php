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
            font-size: 9px;
        }
        th {
            padding: 5px;
            text-align: center;
        }
        td{
            padding: 2px;
            text-align: left;
        }
     
        tbody td {
            text-align: right;
            padding: 2px;
            font-size: 8px;
        }
        tbody td:first-child,
        tbody td:nth-child(2) {
            text-align: left;
            text-wrap: nowrap;
            font-size: 7px;
        }
        .tgl_cetak p {
            font-size: 10px;
            text-align: center;
            margin-top: 50px;
            margin-bottom: 50px;
            margin-right: 70px;
            position: relative;
            float: right;
            clear: both;
        } .signature {
            font-size: 10px;
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
    <h3>BADAN PENGELOLA PAJAK DAN RETRIBUSI DAERAH</h3>
    <h3>REKAPITULASI REALISASI PENERIMAAN PAJAK DAERAH</h3>
    <h3>PADA UNIT PELAKSANA TEKNIS DAERAH (UPTD)</h3>
    <h3>TAHUN ANGGARAN <?= $format_tahun; ?></h3>
   
</div>

    <table border="1">
        <thead>
        <?php if($apbdp_checkbox): ?>
        <tr>
            <th rowspan="3">NO</th>
            <th rowspan="3">WILAYAH/JENIS</th>
            <th rowspan="3">APBD (Rp)</th>
            <th rowspan="3">APBDP (Rp)</th>
            <th colspan="14">Realisasi Per Bulan</th>
            <th colspan="2" rowspan="2">Sisa Lebih Kurang Anggaran</th>
        </tr>
        <tr>
            <th rowspan="2">Januari</th>
            <th rowspan="2">Februari</th>
            <th rowspan="2">Maret</th>
            <th rowspan="2">April</th>
            <th rowspan="2">Mei</th>
            <th rowspan="2">Juni</th>
            <th rowspan="2">Juli</th>
            <th rowspan="2">Agustus</th>
            <th rowspan="2">September</th>
            <th rowspan="2">Oktober</th>
            <th rowspan="2">November</th>
            <th rowspan="2">Desember</th>
            <th colspan="2">Jumlah</th>
        </tr>
        <tr>
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
            <th>9</th>
            <th>10</th>
            <th>11</th>
            <th>12</th>
            <th>13</th>
            <th>14</th>
            <th>15</th>
            <th>16</th>
            <th>17 = 5 SD 16</th>
            <th>18</th>
            <th>19 = 17-4</th>
            <th>20</th>
        </tr>
        <?php else: ?>
            <tr>
            <th rowspan="3">NO</th>
            <th rowspan="3">WILAYAH/JENIS</th>
            <th rowspan="3">APBD (Rp)</th>
            <th colspan="14">Realisasi Per Bulan</th>
            <th colspan="2" rowspan="2">Sisa Lebih Kurang Anggaran</th>
        </tr>
        <tr>
            <th rowspan="2">Januari</th>
            <th rowspan="2">Februari</th>
            <th rowspan="2">Maret</th>
            <th rowspan="2">April</th>
            <th rowspan="2">Mei</th>
            <th rowspan="2">Juni</th>
            <th rowspan="2">Juli</th>
            <th rowspan="2">Agustus</th>
            <th rowspan="2">September</th>
            <th rowspan="2">Oktober</th>
            <th rowspan="2">November</th>
            <th rowspan="2">Desember</th>
            <th colspan="2">Jumlah</th>
        </tr>
        <tr>
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
            <th>9</th>
            <th>10</th>
            <th>11</th>
            <th>12</th>
            <th>13</th>
            <th>14</th>
            <th>15</th>
            <th>16 = 4 SD 15</th>
            <th>17</th>
            <th>18 = 16-3</th>
            <th>19</th>
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
                $total_apbd_keseluruhan = 0;
                $total_apbdp_keseluruhan = 0;
                $total_jan_keseluruhan = 0;
                $total_feb_keseluruhan = 0;
                $total_mar_keseluruhan = 0;
                $total_apr_keseluruhan = 0;
                $total_mei_keseluruhan = 0;
                $total_jun_keseluruhan = 0;
                $total_jul_keseluruhan = 0;
                $total_agu_keseluruhan = 0;
                $total_sep_keseluruhan = 0;
                $total_okt_keseluruhan = 0;
                $total_nov_keseluruhan = 0;
                $total_des_keseluruhan = 0;

                $total_bulan_keseluruhan = 0;
            
                foreach ($tablenya as $row) {
                    $kelompokin[$row['nmuptd']][] = $row;
                
                }
            
                $no = 1;
            
                foreach ($kelompokin as $nmuptd => $rows):
                    $total_apbd_uptd = 0;
                    $total_apbdp_uptd = 0;
                    $total_jan_uptd = 0;
                    $total_feb_uptd = 0;
                    $total_mar_uptd = 0;
                    $total_apr_uptd = 0;
                    $total_mei_uptd = 0;
                    $total_jun_uptd = 0;
                    $total_jul_uptd = 0;
                    $total_agu_uptd = 0;
                    $total_sep_uptd = 0;
                    $total_okt_uptd = 0;
                    $total_nov_uptd = 0;
                    $total_des_uptd = 0;

                    $sisa = 0;
                   
                
                    foreach ($rows as $row) {
                        if (!empty($row['nmrekening'])) {
                            $total_apbd_uptd += $row['apbd'];
                            $total_apbdp_uptd += $row['apbdp'];
                            $total_jan_uptd += $row['jan_jml'];
                            $total_feb_uptd += $row['feb_jml'];
                            $total_mar_uptd += $row['mar_jml'];
                            $total_apr_uptd += $row['apr_jml'];
                            $total_mei_uptd += $row['mei_jml'];
                            $total_jun_uptd += $row['jun_jml'];
                            $total_jul_uptd += $row['jul_jml'];
                            $total_agu_uptd += $row['agu_jml'];
                            $total_sep_uptd += $row['sep_jml'];
                            $total_okt_uptd += $row['okt_jml'];
                            $total_nov_uptd += $row['nov_jml'];
                            $total_des_uptd += $row['des_jml'];

                            $total_bulan_uptd = $total_jan_uptd + $total_feb_uptd +
                            $total_mar_uptd + $total_apr_uptd + $total_mei_uptd +
                            $total_jun_uptd + $total_jul_uptd + $total_agu_uptd + $total_sep_uptd +
                            $total_okt_uptd + $total_nov_uptd + $total_des_uptd ;
                                   
                        
    
                            if ($apbdp_checkbox) {
                                $sisa = $total_bulan_uptd - $total_apbdp_uptd;
                                $persen_sisa = $total_apbdp_uptd > 0 ? ($sisa / $total_apbdp_uptd) * 100 : 0;
                                $persen = $total_apbdp_uptd > 0 ? ($total_bulan_uptd / $total_apbdp_uptd) * 100 : 0;
                            } else {
                                $sisa = $total_bulan_uptd - $total_apbd_uptd;
                                $persen_sisa = $total_apbd_uptd > 0 ? ($sisa / $total_apbd_uptd) * 100 : 0;
                                $persen = $total_apbd_uptd > 0 ? ($total_bulan_uptd / $total_apbd_uptd) * 100 : 0;
                            }
                        }
  
                    }
                   
             
                ?>
                    <tr>
                        <td style="text-align: center; font-weight: bold;" ><?= htmlspecialchars($no++) ?></td>
                        <td style="text-align: left; font-weight: bold;"><?= htmlspecialchars($nmuptd) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($total_apbd_uptd, 2) ?></td>
                        <?php if ($apbdp_checkbox): ?>
                        <td><b><?= number_format($total_apbdp_uptd, 2) ?></b></td>
                        <?php endif; ?>
                     
                        <td style="text-align: right; font-weight: bold;"><?= number_format($total_jan_uptd, 2) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($total_feb_uptd, 2) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($total_mar_uptd, 2) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($total_apr_uptd, 2) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($total_mei_uptd, 2) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($total_jun_uptd, 2) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($total_jul_uptd, 2) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($total_agu_uptd, 2) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($total_sep_uptd, 2) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($total_okt_uptd, 2) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($total_nov_uptd, 2) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($total_des_uptd, 2) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($total_bulan_uptd, 2) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($persen, 2) ?>%</td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($sisa, 2) ?></td>
                        <td style="text-align: right; font-weight: bold;"><?= number_format($persen_sisa, 2) ?>%</td>
                    </tr>
                    <?php foreach ($rows as $row): 
                     
                         $total_bulan = (
                            ($row['jan_jml'] ?? 0) +
                            ($row['feb_jml'] ?? 0) +
                            ($row['mar_jml'] ?? 0) +
                            ($row['apr_jml'] ?? 0) +
                            ($row['mei_jml'] ?? 0) +
                            ($row['jun_jml'] ?? 0) +
                            ($row['jul_jml'] ?? 0) +
                            ($row['agu_jml'] ?? 0) +
                            ($row['sep_jml'] ?? 0) +
                            ($row['okt_jml'] ?? 0) +
                            ($row['nov_jml'] ?? 0) +
                            ($row['des_jml'] ?? 0)
                        );
                                
                            if ($apbdp_checkbox) {
                                $sisa = $total_bulan - $row['apbdp'];
                                $persen_sisa = $row['apbdp'] > 0 ? ($sisa / $row['apbdp']) * 100 : 0;
                                $persen = $row['apbdp'] > 0 ? ($total_bulan / $row['apbdp']) * 100 : 0;
                            } else {
                                $sisa = $total_bulan - $row['apbd'];
                                $persen_sisa = $row['apbd'] > 0 ? ($sisa / $row['apbd']) * 100 : 0;
                                $persen = $row['apbd'] > 0 ? ($total_bulan / $row['apbd']) * 100 : 0;
                            }
                          
                        ?>
                     
                        <tr>
                            <td style="text-align: center;"></td>
                            <td style="text-align: left;">
                                <?= !empty($row['nmrekening']) ? htmlspecialchars($row['nmrekening']) : '' ?>
                            </td>
                            <td style="text-align: right;"><?= number_format($row['apbd'], 2) ?></td>
                            <?php if ($apbdp_checkbox): ?>
                            <td><?= number_format($row['apbdp'], 2) ?></td>
                            <?php endif; ?>
                            <td style="text-align: right;"><?= !empty($row['jan_jml']) ? number_format($row['jan_jml'], 2) : '' ?></td>
                            <td style="text-align: right;"><?= !empty($row['feb_jml']) ? number_format($row['feb_jml'], 2) : '' ?></td>
                            <td style="text-align: right;"><?= !empty($row['mar_jml']) ? number_format($row['mar_jml'], 2) : '' ?></td>
                            <td style="text-align: right;"><?= !empty($row['apr_jml']) ? number_format($row['apr_jml'], 2) : '' ?></td>
                            <td style="text-align: right;"><?= !empty($row['mei_jml']) ? number_format($row['mei_jml'], 2) : '' ?></td>
                            <td style="text-align: right;"><?= !empty($row['jun_jml']) ? number_format($row['jun_jml'], 2) : '' ?></td>
                            <td style="text-align: right;"><?= !empty($row['jul_jml']) ? number_format($row['jul_jml'], 2) : '' ?></td>
                            <td style="text-align: right;"><?= !empty($row['agu_jml']) ? number_format($row['agu_jml'], 2) : '' ?></td>
                            <td style="text-align: right;"><?= !empty($row['sep_jml']) ? number_format($row['sep_jml'], 2) : '' ?></td>
                            <td style="text-align: right;"><?= !empty($row['okt_jml']) ? number_format($row['okt_jml'], 2) : '' ?></td>
                            <td style="text-align: right;"><?= !empty($row['nov_jml']) ? number_format($row['nov_jml'], 2) : '' ?></td>
                            <td style="text-align: right;"><?= !empty($row['des_jml']) ? number_format($row['des_jml'], 2) : '' ?></td>
                            <td style="text-align: right;"><?= number_format($total_bulan, 2) ?></td>
                            <td style="text-align: right;"><?= number_format($persen, 2) ?></td>
                            <td style="text-align: right;"><?= number_format($sisa, 2) ?></td>
                            <td style="text-align: right;"><?= number_format($persen_sisa, 2) ?></td>
                        </tr>
                    <?php  endforeach;
                      $total_apbd_keseluruhan += $total_apbd_uptd;
                      $total_apbdp_keseluruhan += $total_apbdp_uptd;
                      $total_jan_keseluruhan += $total_jan_uptd;
                      $total_feb_keseluruhan += $total_feb_uptd;
                      $total_mar_keseluruhan += $total_mar_uptd;
                      $total_apr_keseluruhan += $total_apr_uptd;
                      $total_mei_keseluruhan += $total_mei_uptd;
                      $total_jun_keseluruhan += $total_jun_uptd;
                      $total_jul_keseluruhan += $total_jul_uptd;
                      $total_agu_keseluruhan += $total_agu_uptd;
                      $total_sep_keseluruhan += $total_sep_uptd;
                      $total_okt_keseluruhan += $total_okt_uptd;
                      $total_nov_keseluruhan += $total_nov_uptd;
                      $total_des_keseluruhan += $total_des_uptd;

                      $total_bulan_keseluruhan = $total_jan_keseluruhan + $total_feb_keseluruhan +
                      $total_mar_keseluruhan + $total_apr_keseluruhan + $total_mei_keseluruhan +
                      $total_jun_keseluruhan + $total_jul_keseluruhan + $total_agu_keseluruhan +
                      $total_sep_keseluruhan + $total_okt_keseluruhan +  $total_nov_keseluruhan + $total_des_keseluruhan;


                      if ($apbdp_checkbox) {
                        $sisa_keseluruhan = $total_bulan_keseluruhan - $total_apbdp_keseluruhan;
                        $persen_sisa_keseluruhan = $total_apbdp_keseluruhan > 0 ? ($sisa_keseluruhan / $total_apbdp_keseluruhan) * 100 : 0;
                        $persen_keseluruhan = $total_apbdp_keseluruhan > 0 ? ($total_bulan_keseluruhan / $total_apbdp_keseluruhan) * 100 : 0;
                        } else {
                            $sisa_keseluruhan = $total_bulan_keseluruhan - $total_apbd_keseluruhan;
                            $persen_sisa_keseluruhan = $total_apbd_keseluruhan > 0 ? ($sisa_keseluruhan / $total_apbd_keseluruhan) * 100 : 0;
                            $persen_keseluruhan = $total_apbd_keseluruhan > 0 ? ($total_bulan_keseluruhan / $total_apbd_keseluruhan) * 100 : 0;
                        }
                  
           
                endforeach; 
                ?>
            
                <tr>
                    <td></td>
                    <td style="text-align: center; font-weight: bold;">JUMLAH PENDAPATAN</td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($total_apbd_keseluruhan, 2) ?></td>
                    <?php if ($apbdp_checkbox): ?>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($total_apbdp_keseluruhan, 2) ?></td>
                    <?php endif; ?>
                    
                    <td style="text-align: right; font-weight: bold;"><?= number_format($total_jan_keseluruhan, 2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($total_feb_keseluruhan, 2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($total_mar_keseluruhan, 2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($total_apr_keseluruhan, 2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($total_mei_keseluruhan, 2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($total_jun_keseluruhan, 2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($total_jul_keseluruhan, 2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($total_agu_keseluruhan, 2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($total_sep_keseluruhan, 2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($total_okt_keseluruhan, 2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($total_nov_keseluruhan, 2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($total_des_keseluruhan, 2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($total_bulan_keseluruhan, 2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($persen_keseluruhan, 2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($sisa_keseluruhan, 2) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($persen_sisa_keseluruhan, 2) ?></td>
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
