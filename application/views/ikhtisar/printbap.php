<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ikhtisar Pendapatan Pajak Harian</title>
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
        th {
            padding: 5px;
            text-align: center;
        }
        td{
            text-align: right;
        }
        .tgl_cetak p{
            font-size: 12px;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 10px;
            margin-right: 50px;
            position: relative;
            float: right;
            clear: both;
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
<?php
$total_hari_ini = 0;
$total_sampai_hari_ini = 0;
$total_hari_sebelumnya = 0;

if (!empty($tablenya)):
    foreach($tablenya as $tbl) {
        $total_hari_ini += $tbl['jumlahdibayar'];
        $total_sampai_hari_ini += $tbl['jumlahdibayar']; 
    }
endif;

$total_sampai_hari_ini = $saldo + $total_hari_ini;
?>
<div class="header">
    <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
    <h3>BADAN PENDAPATAN DAERAH</h3>
    <h4>IKHTISAR PENDAPATAN PAJAK HARIAN</h4>
    <h4>TANGGAL <?= htmlspecialchars($format_tanggal) ?></h4>
</div>
<table class="table-container">
    <thead>
    <tr>
            <th rowspan="3" class="center nowrap">NO</th>
            <th rowspan="3" class="center nowrap">JENIS PENERIMAAN</th>
            <th rowspan="3" class="center nowrap">UPT</th>
            <th rowspan="3" class="center nowrap">MASA PAJAK</th>
            <th colspan="4" class="center nowrap">SSPD / STS</th> 
            <th rowspan="3" class="center nowrap">JUMLAH YG DIBAYAR</th>
            <th rowspan="3" class="center nowrap">KETERANGAN</th>
        </tr>
        <tr>
            <th class="center nowrap" rowspan="2">NOMOR</th>
            <th class="center nowrap" rowspan="2">POKOK PAJAK</th>
            <th class="center nowrap" colspan="2">DENDA</th>
        </tr>
        <tr>
            <th class="center nowrap">%</th>
            <th class="center nowrap">JUMLAH</th>
        </tr>
        <tr>
            <th></th>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7</th>
            <th>8</th>
            <th>9</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $groupedData = [];
    foreach($tablenya as $tbl) {
        $idrekening = $tbl['idrekening'];
        if (!isset($groupedData[$idrekening])) {
            $groupedData[$idrekening] = [
                'namarekening' => $tbl['namarekening'],
                'wajibpajak' => []
            ];
        }
        $groupedData[$idrekening]['wajibpajak'][] = $tbl;
    }

    $no = 1;
    foreach($groupedData as $idrekening => $group):
    ?>
        <tr>
            <td style="text-align: center;"><?= $this->MPbapenda->roman($no++) ?></td>
            <td colspan="3" style="text-align: left; font-weight: bold;"><?= htmlspecialchars($group['namarekening']) ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?php foreach($group['wajibpajak'] as $wp): ?>
        <tr>
            <td></td>
            <td style="text-align: left;"><?= htmlspecialchars($wp['namawp']) ?></td>
            <td style="text-align: center;"><?= htmlspecialchars($wp['singkatanupt']) ?></td>
            <td style="text-align: center;"><?= htmlspecialchars($wp['blnpajak'] . ' - ' . $wp['thnpajak']) ?></td>
            <td style="text-align: center;"><?= htmlspecialchars($wp['nomor']) ?></td>
            <td><?= number_format($wp['pokokpajak'], 2) ?></td>
            <td><?= number_format($wp['persendenda'], 2) ?>%</td>
            <td><?= number_format($wp['jumlahdenda'], 2) ?></td>
            <td><?= number_format($wp['jumlahdibayar'], 2) ?></td>
            <td><?= htmlspecialchars($wp['keterangan']) ?></td>
        </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
    <tr>
        <td></td>
        <td style="text-align: left;"><b>Penerimaan Hari ini  :</b></td>
        <td colspan="4"></td>
        <td colspan="3"><b>Rp. <?= number_format($total_hari_ini, 2) ?></b></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align: left;"><b>Penerimaan Hari lalu :</b></td>
        <td colspan="4"></td>
        <td colspan="3"><b>Rp. <?= number_format($saldo, 2) ?></b></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align: left;" ><b>Penerimaan s/d Hari ini :</b></td>
        <td colspan="4"></td>
        <td colspan="3"><b>Rp. <?= number_format($total_sampai_hari_ini, 2) ?></b></td>
        <td></td>
    </tr>
    </tbody>
</table>
<div class="tgl_cetak">
    <p>Bandar Lampung, <?= $tgl_cetak_format ?></p>
</div>
<?php if (!empty($tanda_tangan)) : ?>
    <div class="signature">
        <p><?= htmlspecialchars($tanda_tangan['jabatan1']) ?>,</p>
        <p><?= htmlspecialchars($tanda_tangan['jabatan2']) ?>,</p>
        <p class="name"><?= htmlspecialchars($tanda_tangan['nama']) ?></p>
        <p>NIP. <?= htmlspecialchars($tanda_tangan['nip']) ?></p>
    </div>
<?php endif; ?>
</body>
</html>
