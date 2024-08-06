<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
        }
        .header {
            text-align: left;
            margin-bottom: 20px;
            font-size: 12px;
        }
        .header img {
            max-width: 100px;
        }
        .header h2, .header h3, .header h4 {
            margin: 0;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .sub-header {
            padding-left: 40px; 
        }
        .sub-header h3 {
            font-size: 12px;
            font-weight: bold;
            margin: 0;
            margin-bottom: 5px;
            padding-left: 40px; 
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: auto;
        }
        table, th, td {
            border: 2px solid black;
            font-size: 9px;
        }
        th {
            padding: 5px;
            text-align: center;
            page-break-inside: avoid;
            font-weight: bold;
        }
        td {
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
            font-size: 12px;
        }
        .signature {
            font-weight: bold;
            text-align: center;
            margin-top: 60px;
            margin-right: 20px;
            position: relative;
            float: right;
            clear: both;
            font-size: 12px;
        }
        .signature .jabatan1 {
            margin-top: 30px;
        }
        .signature .name {
            text-decoration: underline;
            font-weight: bold;
            margin-top: 70px;
        }
        .page-break {
            page-break-before: always;
        }
        .page-break-none {
            page-break-before: avoid;
        }
    </style>
</head>
<body>

<div class="header">
    <h3>Lampiran : Keputusan Kepala Badan Pengelola Pajak dan Retribusi Daerah Kota Bandar</h3>
</div>
<div class="sub-header">
    <h3>Nomor&nbsp; &nbsp;&nbsp;: <?= htmlspecialchars($nomor) ?></h3>
    <h3>Tanggal&nbsp;&nbsp;: <?= htmlspecialchars($tgl_cetak_format) ?></h3>
    <h3>Perihal&nbsp;&nbsp; &nbsp;: Piutang Pajak Hotel, Restoran, Hiburan, Reklame, Parkir, Air Tanah, dan Minerba</h3>
    <?php if (!empty($iduptd)) : ?>
        <h3>UPTD&nbsp;&nbsp; &nbsp; &nbsp;: <?= htmlspecialchars($iduptd['nama']) ?></h3>
    <?php endif; ?>   
</div>

<table border="1">
    <thead>
    <tr>
        <th rowspan="2">NO</th>
        <th rowspan="2">Uraian</th>
        <th rowspan="2">UPTD</th>
        <th rowspan="2">No SPTPD/SKPD</th>
        <th rowspan="2">Tgl SPTPD/SKPD</th>
        <th rowspan="2">Masa Pajak</th>
        <th rowspan="2">Tgl Jth Tempo</th>
        <th rowspan="2">Pokok Pajak/Bulan</th>
        <th colspan="2">STPD</th>
        <th rowspan="2">Jumlah Tunggakan</th>
        <th rowspan="2">Keterangan</th>
    </tr>
    <tr>
        <th>Persen(%)</th>
        <th>Denda</th>
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
    </tr>
    </thead>
    <tbody>
    <?php
 
        function romawi($angka) {
            $angka = intval($angka);
            $hasil = '';

            $romawi = array(
                1000 => 'M',
                900 => 'CM', 
                500 => 'D', 
                400 => 'CD',
                100 => 'C', 
                90 => 'XC', 
                50 => 'L', 
                40 => 'XL',
                10 => 'X', 
                9 => 'IX', 
                5 => 'V', 
                4 => 'IV',
                1 => 'I'
            );

            foreach ($romawi as $nilai => $huruf) {
                $jumlah = intval($angka / $nilai);
                $angka %= $nilai;
                $hasil .= str_repeat($huruf, $jumlah);
            }

            return $hasil;
        }

        if (!empty($tablenya)): ?>
            <?php
            $no = 1;
            $nmreknya = '';
            $noromawi = 1;
            $total_hari_ini = 0;
            $total_bunga = 0;
            $total_seluruh = 0;
        
            $total_hari_ini_pajak = 0;
            $total_bunga_pajak = 0;
            $total_seluruh_pajak = 0;
        
            $sub_total_hari_ini = 0;
            $sub_total_bunga = 0;
            $sub_total_seluruh = 0;

            
            ?>
        
        <?php
        function formatTanggal($tanggal) {
            $parts = explode('-', $tanggal);

            if (count($parts) == 3) {
               
                return "{$parts[2]}/{$parts[1]}/{$parts[0]}";
            }
            return $tanggal; 
        }
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
        ?>

        <?php foreach ($tablenya as $row):
     
          
          $prshitung = 0;
          $prsdenda = 0;
          
          $tglakhirs = strtotime($row['tglakhirs']);
          $tglakhir = strtotime($row['tglakhir']);
          $tgldenda = strtotime($row['tgldenda']);
          $tgljthtmp = strtotime($row['tgljthtmp']);
          
          if ($row['kdrek'] == '4.1.1.04') {
            if ($tgldenda > $tglakhir) {
                $prshitung = 0;
            } else if ($tglakhir == $tgldenda) {
                $selisihbulan = ((date('Y', $tglakhir) - date('Y', $tgldenda)) * 12) + (date('m', $tglakhir) - date('m', $tgldenda));
                $prshitung = max(($selisihbulan * 2 / 100) + 0.06, 0); 
            } else {
                if ($tglakhir > $tglakhirs) {
                    $selisihhari = ceil(($tglakhirs - $tgldenda) / 86400);
                    $prshitung = max(($selisihhari / 30 * 2 / 100), 0); 
                } else {
                    $selisihhari = ceil(($tglakhir - $tgldenda) / 86400);
                    $prshitung = max(($selisihhari / 30 * 2 / 100), 0); 
                }
            }
        } else {
            if ($tgldenda > $tglakhir) {
                $prshitung = 0;
            } else {
                $selisihbulan = ((date('Y', $tglakhirs) - date('Y', $tgldenda)) * 12) + (date('m', $tglakhirs) - date('m', $tgldenda));
                $prshitung = max(($selisihbulan * 2 / 100), 0); 
            }
        }
          if ($row['pokok'] == 0 || $prshitung < 0) {
              $prsdenda = 0;
          } elseif ($prshitung >= 0.48) {
              $prsdenda = 0.48;
          } else {
              $prsdenda = $prshitung;
          }
          $jmldenda = ceil($row['pokok'] * $prsdenda);
          $total_hari_ini += $row['pokok'];
          $total_bunga +=  $jmldenda;
          $total_seluruh += $row['jumlah'];
          
      /*     $jmldenda = ceil($row['pokok'] * $prsdenda);
          
          $sub_total_hari_ini += $row['pokok'];
          $sub_total_bunga += $jmldenda;
          $total_per_pajak = $row['pokok'] + $jmldenda;
          $sub_total_seluruh += $row['jumlah'];
           */

            if ($row['nmrek'] !== $nmreknya):
                if ($nmreknya !== ''):
                    ?>
                    <tr>
                        <td colspan="7" style="background-color:lightgray; text-align:right; font-weight: bold;">TOTAL <?= htmlspecialchars($nmreknya) ?></td>
                        <td style="background-color:lightgray; text-align: right; font-weight: bold;"><?= number_format($sub_total_hari_ini) ?></td>
                        <td style="background-color:lightgray; text-align: right; font-weight: bold;"></td>
                        <td style="background-color:lightgray; text-align: right; font-weight: bold;"><?= number_format($sub_total_bunga) ?></td>
                        <td style="background-color:lightgray; text-align: right; font-weight: bold;"><?= number_format($sub_total_seluruh) ?></td>
                        <td style="background-color:lightgray;"></td>
                    </tr>
                    <?php
                    $sub_total_hari_ini = 0;
                    $sub_total_bunga = 0;
                    $sub_total_seluruh = 0;
                endif;
                ?>
                <tr>
                    <td style="text-align: center; background-color:lightgray;"><?= romawi($noromawi++) ?></td>
                    <td style="background-color:lightgray;"><b><?= htmlspecialchars($row['nmrek']) ?></b></td>
                    <td style="background-color:lightgray;"></td>
                    <td style="background-color:lightgray;"></td>
                    <td style="background-color:lightgray;"></td>
                    <td style="background-color:lightgray;"></td>
                    <td style="background-color:lightgray;"></td>
                    <td style="background-color:lightgray;"></td>
                    <td style="background-color:lightgray;"></td>
                    <td style="background-color:lightgray;"></td>
                    <td style="background-color:lightgray;"></td>
                </tr>
                <?php
                $nmreknya = $row['nmrek'];
                $no = 1;
            endif;

                
          $jmldenda = ceil($row['pokok'] * $prsdenda);
          
          $sub_total_hari_ini += $row['pokok'];
          $sub_total_bunga += $jmldenda;
          $total_per_pajak = $row['pokok'] + $jmldenda;
          $sub_total_seluruh += $total_per_pajak;
            ?>
            <tr>
                <td style="text-align: center;"><?= $no++ ?></td>
                <td style="text-align: left;"><?= htmlspecialchars($row['nmwp']) ?></td>
                <td style="text-align: center;"><?= htmlspecialchars($row['uptd']) ?></td>
                <td style="text-align: center;"><?= htmlspecialchars($row['nomor']) ?></td>
                <td style="text-align: center;"><?= formatTanggal(htmlspecialchars($row['tanggal'])) ?></td>
                <td style="text-align: center;"><?= bulanKeIndonesia(htmlspecialchars($row['bulan'])) ?>-<?= htmlspecialchars($row['tahun']) ?></td>
                <td style="text-align: center;"><?= formatTanggal(htmlspecialchars($row['tgljthtmp'])) ?></td>
                <td style="text-align: right;"><?= number_format($row['pokok']) ?></td>
                <td style="text-align: right;"><?= number_format($prsdenda * 100) . '%' ?></td>
                <td style="text-align: right;"><?= number_format($jmldenda) ?></td>
                <td style="text-align: right;"><?= number_format($total_per_pajak) ?></td>
                <td style="text-align: left;"><?= htmlspecialchars($row['keterangan']) ?></td>
            </tr>
        <?php endforeach; ?>

        
            <?php if ($nmreknya !== ''): 
                ?>
            <tr>
                <td colspan="7" style="background-color:lightgray; text-align:right; font-weight: bold;">TOTAL <?= htmlspecialchars($nmreknya) ?></td>
                <td style="background-color:lightgray; text-align: right; font-weight: bold;"><?= number_format($sub_total_hari_ini) ?></td>
                <td style="background-color:lightgray; text-align: right; font-weight: bold;"></td>
                <td style="background-color:lightgray; text-align: right; font-weight: bold;"><?= number_format($sub_total_bunga) ?></td>
                <td style="background-color:lightgray; text-align: right; font-weight: bold;"><?= number_format($sub_total_seluruh) ?></td>
                <td style="background-color:lightgray;"></td>
            </tr>
            <?php 
        
        endif; ?>
        
            <tr>
                <td style="background-color:lightgray;"></td>
                <td style="text-align: center; font-weight: bold; background-color: lightgray;">JUMLAH TUNGGAKAN PAJAK</td>
                <td style="background-color:lightgray;"></td>
                <td style="background-color:lightgray;"></td>
                <td style="background-color:lightgray;"></td>
                <td style="background-color:lightgray;"></td>
                <td style="background-color:lightgray;"></td>
                <td style="background-color:lightgray; text-align: right; font-weight: bold;"><?= number_format($total_hari_ini, 2) ?></td>
                <td style="background-color:lightgray; text-align: right; font-weight: bold;"></td>
                <td style="background-color:lightgray; text-align: right; font-weight: bold;"><?= number_format($total_bunga, 2) ?></td>
                <td style="background-color:lightgray; text-align: right; font-weight: bold;"><?= number_format($total_seluruh, 2) ?></td>
                <td style="background-color:lightgray;"></td>
            </tr>
        
        <?php else: ?>
            <tr>
                <td colspan="11">Data tidak tersedia.</td>
            </tr>
        <?php endif; ?>
        
    </tbody>
</table>

<div class="footer-section">
    <div class="tgl_cetak">
        <p>Bandar Lampung, <?= htmlspecialchars($tgl_cetak_format) ?></p>
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
