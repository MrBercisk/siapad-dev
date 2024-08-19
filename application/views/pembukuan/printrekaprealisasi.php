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
    <h3>REKAPITULASI REALISASI PENERIMAAN PAJAK DAERAH BERDASARKAN MASA PAJAK</h3>
    <h3>PADA UNIT PELAKSANA TEKNIS DAERAH (UPTD)</h3>
    <h3>TAHUN ANGGARAN <?= $format_tahun; ?></h3>
   
</div>

    <table border="1">
        <thead>
        <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2">WILAYAH/JENIS</th>
            <th colspan="13">REALISASI PER MASA PAJAK (Rp)</th>
        </tr>
        <tr>
            <th>Januari</th>
            <th>Februari</th>
            <th>Maret</th>
            <th>April</th>
            <th>Mei</th>
            <th>Juni</th>
            <th>Juli</th>
            <th>Agustus</th>
            <th>September</th>
            <th>Oktober</th>
            <th>November</th>
            <th>Desember</th>
            <th>Jumlah</th>
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
            <th>15 = 3 SD 14</th>
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
                                   
                        
                        }
  
                    }
                   
             
                ?>
                    <tr>
                        <td style="text-align: center; font-weight: bold;"><?= htmlspecialchars($no++) ?></td>
                        <td style="text-align: left; font-weight: bold;"><?= htmlspecialchars($nmuptd) ?></td>
                     
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
                        
                        ?>
                     
                        <tr>
                            <td style="text-align: center;"></td>
                            <td style="text-align: left;">
                                <?= !empty($row['nmrekening']) ? htmlspecialchars($row['nmrekening']) : '' ?>
                            </td>   
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
                  
           
                endforeach; 
                ?>
            
                <tr>
                    <td></td>
                    <td style="text-align: center; font-weight: bold;">JUMLAH PENDAPATAN</td>
                    
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
