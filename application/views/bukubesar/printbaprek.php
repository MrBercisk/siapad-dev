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
            text-align: center;
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
        .sub-header h3 {
            font-weight: 400;
            font-size: 12px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 2px solid black;
            font-size: 8px;
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
            font-size: 12px;
        }
        .signature {
            font-size: 12px;
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
    </style>
</head>
<body>

<div class="header">
    <h3>PEMERINTAH KOTA BANDAR LAMPUNG</h3>
    <h3>BADAN PENDAPATAN DAERAH</h3>
    <h3>REKAPITULASI REALISASI ANGGARAN PENDAPATAN BAPENDA</h3>
    <?php if (!empty($kdrekening)) : ?>
        <h3><u>REKENING : <?= htmlspecialchars($kdrekening['nmrekening']) ?></u></h3>
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
            <th>NO</th>
            <th>Nama Wajib Pajak</th>
            <th>APBD</th>
            <th>APBDP</th>
            <th>UPTD</th>
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
            <th>Jumlah (Rp)</th>
            <th>Jumlah (%)</th>
        </tr>
        <tr>
            <td style="text-align: center;">1</td>
            <td style="text-align: center;">2</td>
            <td style="text-align: center;">3</td>
            <td style="text-align: center;">4</td>
            <td style="text-align: center;">5</td>
            <td style="text-align: center;">6</td>
            <td style="text-align: center;">7</td>
            <td style="text-align: center;">8</td>
            <td style="text-align: center;">9</td>
            <td style="text-align: center;">10</td>
            <td style="text-align: center;">11</td>
            <td style="text-align: center;">12</td>
            <td style="text-align: center;">13</td>
            <td style="text-align: center;">14</td>
            <td style="text-align: center;">15</td>
            <td style="text-align: center;">16</td>
            <td style="text-align: center;">17</td>
            <td style="text-align: center;">18</td>
            <td style="text-align: center;">19</td>
        </tr>
    </thead>
    <tbody>
        <?php

        if (!empty($tablenya)):
            $no = 1;
            $judulrek = '';
            $row_number = 1; 

            $total_apbd = 0;
            $total_apbdp = 0;
            $total_januari = 0;
            $total_februari = 0;
            $total_maret = 0;
            $total_april = 0;
            $total_mei = 0;
            $total_juni = 0;
            $total_juli = 0;
            $total_agustus = 0;
            $total_september = 0;
            $total_oktober = 0;
            $total_november = 0;
            $total_desember = 0;

            foreach ($tablenya as $row):
                if ($judulrek != $row['nmrekening']):
                    if ($judulrek !== ''):
                        ?>
                        <tr>
                            <td></td>
                            <td style="text-align: center;"><b>JUMLAH</b></td>
                            <td style="text-align: right;"><b><?= number_format($total_apbd, 2) ?></b></td>
                            <td style="text-align: right;"><b><?= number_format($total_apbdp, 2) ?></b></td>
                            <td></td>
                            <td style="text-align: right;"><b><?= number_format($total_januari, 2) ?></b></td>
                            <td style="text-align: right;"><b><?= number_format($total_februari, 2) ?></b></td>
                            <td style="text-align: right;"><b><?= number_format($total_maret, 2) ?></b></td>
                            <td style="text-align: right;"><b><?= number_format($total_april, 2) ?></b></td>
                            <td style="text-align: right;"><b><?= number_format($total_mei, 2) ?></b></td>
                            <td style="text-align: right;"><b><?= number_format($total_juni, 2) ?></b></td>
                            <td style="text-align: right;"><b><?= number_format($total_juli, 2) ?></b></td>
                            <td style="text-align: right;"><b><?= number_format($total_agustus, 2) ?></b></td>
                            <td style="text-align: right;"><b><?= number_format($total_september, 2) ?></b></td>
                            <td style="text-align: right;"><b><?= number_format($total_oktober, 2) ?></b></td>
                            <td style="text-align: right;"><b><?= number_format($total_november, 2) ?></b></td>
                            <td style="text-align: right;"><b><?= number_format($total_desember, 2) ?></b></td>
                            <td style="text-align: right;"><b><?= number_format($total_januari + $total_februari + $total_maret +
                                    $total_april + $total_mei + $total_juni +
                                    $total_juli + $total_agustus + $total_september +
                                    $total_oktober + $total_november + $total_desember, 2) ?></b></td>
                              <td style="text-align: right;"><b><?= number_format(
                                $total_apbd > 0 ? ($total_januari + $total_februari + $total_maret +
                                $total_april + $total_mei + $total_juni +
                                $total_juli + $total_agustus + $total_september +
                                $total_oktober + $total_november + $total_desember) / $total_apbd * 100 : 0, 2
                            ) ?></b></td>
                        </tr>
                        <?php
                    endif;
                    ?>
                    <tr>
                        <td></td>
                        <td style="text-align: left; font-size:10px;"><b><?= htmlspecialchars($row['nmrekening']) ?></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php

                    $row_number = 1; 
                    $judulrek = $row['nmrekening'];

                    $total_apbd = 0;
                    $total_apbdp = 0;
                    $total_januari = 0;
                    $total_februari = 0;
                    $total_maret = 0;
                    $total_april = 0;
                    $total_mei = 0;
                    $total_juni = 0;
                    $total_juli = 0;
                    $total_agustus = 0;
                    $total_september = 0;
                    $total_oktober = 0;
                    $total_november = 0;
                    $total_desember = 0;
                endif;

                $total_apbd += $row['apbd'];
                $total_apbdp += $row['apbdp'];
                $total_januari += $row['januari'];
                $total_februari += $row['februari'];
                $total_maret += $row['maret'];
                $total_april += $row['april'];
                $total_mei += $row['mei'];
                $total_juni += $row['juni'];
                $total_juli += $row['juli'];
                $total_agustus += $row['agustus'];
                $total_september += $row['september'];
                $total_oktober += $row['oktober'];
                $total_november += $row['november'];
                $total_desember += $row['desember'];
                ?>
                <tr>
                    <td><?= $row_number++ ?></td>
                    <td style="text-align: left;"><?= htmlspecialchars($row['nmwp']) ?></td>
                    <td style="text-align: right;"><?= number_format($row['apbd'], 2) ?></td>
                    <td style="text-align: right;"><?= number_format($row['apbdp'], 2) ?></td>
                    <td style="text-align: right;"><?= htmlspecialchars($row['nmuptd']) ?></td>
                    <td style="text-align: right;"><?= number_format($row['januari'], 2) ?></td>
                    <td style="text-align: right;"><?= number_format($row['februari'], 2) ?></td>
                    <td style="text-align: right;"><?= number_format($row['maret'], 2) ?></td>
                    <td style="text-align: right;"><?= number_format($row['april'], 2) ?></td>
                    <td style="text-align: right;"><?= number_format($row['mei'], 2) ?></td>
                    <td style="text-align: right;"><?= number_format($row['juni'], 2) ?></td>
                    <td style="text-align: right;"><?= number_format($row['juli'], 2) ?></td>
                    <td style="text-align: right;"><?= number_format($row['agustus'], 2) ?></td>
                    <td style="text-align: right;"><?= number_format($row['september'], 2) ?></td>
                    <td style="text-align: right;"><?= number_format($row['oktober'], 2) ?></td>
                    <td style="text-align: right;"><?= number_format($row['november'], 2) ?></td>
                    <td style="text-align: right;"><?= number_format($row['desember'], 2) ?></td>
                    <td style="text-align: right;"><?= number_format(
                        $row['januaripokok'] + $row['februaripokok'] +
                        $row['maretpokok'] + $row['aprilpokok'] + $row['meipokok'] + $row['junipokok'] +
                        $row['julipokok'] + $row['agustuspokok'] + $row['septemberpokok'] +
                        $row['oktoberpokok'] + $row['novemberpokok'] + $row['desemberpokok'], 2
                    ) ?></td>
                      <td style="text-align: right;"><b><?= number_format(
                                $row['apbd'] > 0 ? (
                                    ($row['januaripokok'] +  $row['februaripokok']+  $row['maretpokok'] +
                                    $row['aprilpokok'] + $row['meipokok'] +  $row['junipokok'] +
                                    $row['julipokok'] + $row['agustuspokok'] + $row['septemberpokok'] +
                                    $row['oktoberpokok'] + $row['novemberpokok'] + $row['desemberpokok']) / $row['apbd'] * 100
                                ) : 0, 2
                            ) ?></b></td>
                </tr>
                <?php
            endforeach;
            
            ?>
            <tr>
                <td></td>
                <td style="text-align: center;"><b>JUMLAH</b></td>
                <td style="text-align: right;"><b><?= number_format($total_apbd, 2) ?></b></td>
                <td style="text-align: right;"><b><?= number_format($total_apbdp, 2) ?></b></td>
                <td></td>
                <td style="text-align: right;"><b><?= number_format($total_januari, 2) ?></b></td>
                <td style="text-align: right;"><b><?= number_format($total_februari, 2) ?></b></td>
                <td style="text-align: right;"><b><?= number_format($total_maret, 2) ?></b></td>
                <td style="text-align: right;"><b><?= number_format($total_april, 2) ?></b></td>
                <td style="text-align: right;"><b><?= number_format($total_mei, 2) ?></b></td>
                <td style="text-align: right;"><b><?= number_format($total_juni, 2) ?></b></td>
                <td style="text-align: right;"><b><?= number_format($total_juli, 2) ?></b></td>
                <td style="text-align: right;"><b><?= number_format($total_agustus, 2) ?></b></td>
                <td style="text-align: right;"><b><?= number_format($total_september, 2) ?></b></td>
                <td style="text-align: right;"><b><?= number_format($total_oktober, 2) ?></b></td>
                <td style="text-align: right;"><b><?= number_format($total_november, 2) ?></b></td>
                <td style="text-align: right;"><b><?= number_format($total_desember, 2) ?></b></td>
                <td style="text-align: right;"><b><?= number_format($total_januari + $total_februari + $total_maret +
                        $total_april + $total_mei + $total_juni +
                        $total_juli + $total_agustus + $total_september +
                        $total_oktober + $total_november + $total_desember, 2) ?></b></td>
                <td style="text-align: right;"><b><?= number_format(
                    $total_apbd > 0 ? ($total_januari + $total_februari + $total_maret +
                    $total_april + $total_mei + $total_juni +
                    $total_juli + $total_agustus + $total_september +
                    $total_oktober + $total_november + $total_desember) / $total_apbd * 100 : 0, 2
                ) ?></b></td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center;"><b>TOTAL</b></td>    
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
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
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
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
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center;">TOTAL</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
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
