<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
    <style>
      
      body {
            font-family: Arial, sans-serif;
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
        .header h2, .header h3, .header h4 {
            margin: 0;
            margin-bottom: 5px;
            font-weight: bold;
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
        td{
            text-align: right;
        }
        th {
            padding: 5px;
            text-align: center;
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
            margin-bottom: 110px;
            margin-right: 70px;
            position: relative;
            float: right;
            clear: both;
        }
        .signature {
            font-weight: bold;
            text-align: center;
            margin-top: 60px;
            margin-right: 30px;
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
<?php
    $total_hari_ini = 0;
    if (!empty($tablenya)):
        foreach($tablenya as $tbl) {
            $total_hari_ini += $tbl['jumlahdibayar'];
          
        }
    endif;
    $total_sampai_hari_ini = $saldo + $total_hari_ini;
    ?>
<div class="header">
    <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
    <h3>BADAN PENDAPATAN DAERAH</h3>
    <h4>BUKU PENERIMAAN KAS</h4>
    <h4>PER TANGGAL <?= $format_tanggal ?></h4>
</div>
<table class="table-container">
    <thead>
        <tr>
                <th rowspan="2">NO. BUKTI (STS/NOTA DEBIT/KREDIT)</th>
                <th rowspan="2">KODE REKENING</th>
                <th rowspan="2">URAIAN</th>
                <th rowspan="2">DINAS</th>
                <th colspan="2">JUMLAH</th>
                <th rowspan="2">KETERANGAN</th>
        </tr>  
        <tr>
                <th>PER KODE REKENING</th>
                <th>PER SETORAN</th>
        </tr>
        <tr>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7</th>
        </tr>
    </thead>
    <tbody>
    <?php

            if (!empty($tablenya)):
                $groupedData = [];

                // Mengelompokkan data berdasarkan singkatdinas
                foreach($tablenya as $tbl) {
                    $singkatdinas = $tbl['singkatdinas'];
                    if (!isset($groupedData[$singkatdinas])) {
                        $groupedData[$singkatdinas] = [];
                    }
                    $groupedData[$singkatdinas][] = $tbl;
                }

                // Menampilkan data yang telah dikelompokkan
                foreach($groupedData as $singkatdinas => $rows):
                    $jumlahTotal = 0;
                    $pokokTotal = 0;
                    foreach($rows as $tbl):
                        $jumlahTotal += $tbl['jumlahdibayar']; 
                        $pokokTotal += $tbl['pokokpajak']
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($tbl['nomor'])?></td>
                            <td><?= htmlspecialchars($tbl['koderekening'])?></td>
                            <td style="text-align: left;"><?= htmlspecialchars($tbl['namarekening'])?></td>
                            <td style="text-align: left;"><?= htmlspecialchars($tbl['singkatdinas'])?></td>
                            <td><?= number_format($tbl['jumlahdibayar'], 2) ?></td>
                            <td><?= number_format($tbl['pokokpajak'], 2) ?></td>
                            <td><?= htmlspecialchars($tbl['keterangan'])?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" style="text-align: right;"><b>JUMLAH</b></td>
                        <td style="text-align: right;"><b><?= number_format($jumlahTotal, 2) ?></b></td>
                        <td style="text-align: right;"><b><?= number_format($pokokTotal, 2) ?></b></td>
                        <td><?= htmlspecialchars($tbl['keterangan'])?></td>
                    </tr>
                <?php endforeach;
            endif; 
            ?>

                        <tr>
                            <td colspan="4" style="text-align: center;"><b>Jumlah Per <?= $format_tanggal; ?></b> </td>
                            <td style="text-align: right;"><b> <?= number_format($total_hari_ini, 2) ?></b></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: center;"><b>Jumlah s.d.  <?= $tanggal_sebelumnya; ?></b></td>
                            <td style="text-align: right;"><b><?= number_format($saldo, 2) ?><</b></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: center;"><b>Jumlah s.d. <?= $format_tanggal; ?></b> </td>
                            <td style="text-align: right;"><b><?= number_format($total_sampai_hari_ini, 2) ?> </b></td>
                            <td></td>
                            <td></td>
                        </tr>
            </tbody>
        </table>
        <h4>Keterangan</h4>
        <table cellpadding="4">
            <?php
            $total_pendapatan_penerimaan = $total_sampai_hari_ini + $pembiayaan;
            ?>
            <tr>
                <td style="width:20%;">Penerimaan Kasda</td>
                <td style="width:40%;">Rp. <?= number_format($total_sampai_hari_ini, 2) ?> </td>
            </tr>
          <!--   <tr>
                <td style="width:20%;">BLUD</td>
                <td style="width:40%;">Rp. <?= number_format($blud, 2) ?> </td>
            </tr>
            <tr>
                <td style="width:20%;">Hibah Dana BOS</td>
                <td style="width:40%;">Rp. <?= number_format($bos, 2) ?> </td>
            </tr>
            <tr>
                <td style="width:20%;">Penghapusan Hutang PDAM Way Rilau</td>
                <td style="width:40%;">Rp. <?= number_format($rilau, 2) ?> </td>
            </tr> -->
            <tr>
                <td style="width:20%;">PEMBIAYAAN</td>
                <td style="width:40%;">Rp. <?= number_format($pembiayaan, 2) ?></td>
            </tr>
            <tr>
                <td style="width:30%;"><b>Jumlah Pendapatan + Pembiayaan</b></td>
                <td style="width:30%;">Rp. <b><?= number_format($total_pendapatan_penerimaan, 2) ?></b></td>
            </tr>
        </table>
        <br><br>
<div class="tgl_cetak">
        <p>Bandar Lampung, <?= $tgl_cetak_format; ?></p>
    </div>

<?php if (!empty($tanda_tangan)) : ?>
    <div class="signature">
        <p class="jabatan1"><?= $tanda_tangan['jabatan1'] ?></p>
        <p><?= $tanda_tangan['jabatan2'] ?>,</p>
        <p class="name"><?= $tanda_tangan['nama'] ?></p>
        <p>NIP. <?= $tanda_tangan['nip'] ?></p>
    </div>
<?php endif; ?>
 
</body>
</html>
