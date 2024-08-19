<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
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
            table-layout: fixed;
        }
        table, th, td {
            border: 1px solid black;
        }
        th {
            padding: 10px;
            text-align: center;
            font-size: 10px;
        }
        td {
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
        tbody td {
            text-align: right;
            padding: 5px;
        }
        tbody td:first-child,
        tbody td:nth-child(2) {
            text-align: left;
        }
        tbody td:nth-child(2) {
            overflow: hidden;
            text-wrap: nowrap;
        
   
        }
        .tgl_cetak p {
            font-size: 12px;
            text-align: center;
            margin-top: 50px;
            margin-bottom: 50px;
            margin-right: 50px;
            position: relative;
            float: right;
            clear: both;
        }
        .footer-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
         }
     
        .signature2 {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin-top: 60px;
            margin-right: 30px;
            position: relative;
            float: right;
            clear: both;
        }
        .signature2 .jabatan1 {
            margin-top: 10px;
        }
        .signature2 .name {
            text-decoration: underline;
            font-weight: bold;
            margin-top: 70px;
        }
        .signature {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin-top: 40px;
            margin-left: 30px;
            position: relative;
            float: left;
            clear: both;
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
setlocale(LC_ALL, 'id-ID', 'id_ID');
$tanggal_saat_ini = strftime('%d %B %Y');
$tanggal_sebelumnya = strftime('%d %B %Y', strtotime('-1 day'));
?>
<div class="header">

    <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
    <h3>BADAN PENDAPATAN DAERAH</h3>
    <h3>REKONSILIASI LAPORAN REALISASI ANGGARAN PENDAPATAN DAERAH</h3>
    <h3>s.d BULAN: <?= $format_bulan; ?> <?= $format_tahun;?></h3>
    <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo">
</div>
<table>
  
    <thead>
        <tr>
            <th rowspan="2">KODE REKENING</th>
            <th rowspan="2">URAIAN AKUN</th>
            <th rowspan="2">APBD</th>
            <?php if ($apbdp_checkbox): ?>
                <th rowspan="2">APBDP</th>
            <?php endif; ?>
            <th colspan="4">REKONSILIASI BAPENDA DAN BPKAD KOTA BANDAR LAMPUNG</th>
            <th rowspan="2">SKPD PENGELOLA</th>
        </tr>
        <tr>
            <th>BAPENDA</th>
            <th>BPKAD</th>
            <th>SELISIH</th>
            <th>PENJELASAN SELISIH</th>
        </tr>
        <tr>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <?php if ($apbdp_checkbox): ?>
                <th>4</th>
            <?php endif; ?>
            <th>5</th>
            <th>6</th>
            <th>7 = 5-6</th>
            <th>8</th>
            <th>9</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $totalApbd = 0;
    $totalApbdp = 0;
    $totalBap = 0;
    $totalBpkad = 0;
    $totalSelisih = 0;
    $groupedData = [];

    if (!empty($tablenya)) {
        foreach ($tablenya as $tbl) {
            $selisihnya = $tbl['dipenda'] - $tbl['bpkad'];
            if (!isset($groupedData[$tbl['nmrek2']])) {
                $groupedData[$tbl['nmrek2']] = [
                    'kdrek2' => $tbl['kdrek2'],
                    'totalApbd' => 0,
                    'totalApbdp' => 0,
                    'totalBap' => 0,
                    'totalBpkad' => 0,
                    'totalSelisih' => 0,
                    'subTotals' => []
                ];
            }
    
            if (!isset($groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']])) {
                $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']] = [
                    'kdrek3' => $tbl['kdrek3'],
                    'totalApbd' => 0,
                    'totalApbdp' => 0,
                    'totalBap' => 0,
                    'totalBpkad' => 0,
                    'totalSelisih' => 0,
                    'subTotals' => []
                ];
            }
    
            if (!isset($groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']])) {
                $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']] = [
                    'kdrek4' => $tbl['kdrek4'],
                    'nmrek4' => $tbl['nmrek4'],
                    'totalApbd' => 0,
                    'totalApbdp' => 0,
                    'totalBap' => 0,
                    'totalBpkad' => 0,
                    'totalSelisih' => 0,
                    'subSubTotals' => []
                ];
            }
            if (!isset($groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']])) {
                $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']] = [
                    'kdrek5' => $tbl['kdrek5'],
                    'nmrek5' => $tbl['nmrek5'],
                    'apbd' => 0,
                    'apbdp' => 0,
                    'dipenda' => 0,
                    'bpkad' => 0,
                    'selisih' => 0
                ];
            }

            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['apbd'] += $tbl['apbd'];
            if ($apbdp_checkbox) {
                $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['apbdp'] += $tbl['apbdp'];
            }
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['dipenda'] += $tbl['dipenda'];
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['bpkad'] += $tbl['bpkad'];
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['selisih'] += $selisihnya;
    
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['totalApbd'] += $tbl['apbd'];
            if ($apbdp_checkbox) {
                $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['totalApbdp'] += $tbl['apbdp'];
            }
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['totalBap'] += $tbl['dipenda'];
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['totalBpkad'] += $tbl['bpkad'];
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['totalSelisih'] += $selisihnya;
    
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['totalApbd'] += $tbl['apbd'];
            if ($apbdp_checkbox) {
                $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['totalApbdp'] += $tbl['apbdp'];
            }
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['totalBap'] += $tbl['dipenda'];
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['totalBpkad'] += $tbl['bpkad'];
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['totalSelisih'] += $selisihnya;
    
            $groupedData[$tbl['nmrek2']]['totalApbd'] += $tbl['apbd'];
            if ($apbdp_checkbox) {
                $groupedData[$tbl['nmrek2']]['totalApbdp'] += $tbl['apbdp'];
            }
            $groupedData[$tbl['nmrek2']]['totalBap'] += $tbl['dipenda'];
            $groupedData[$tbl['nmrek2']]['totalBpkad'] += $tbl['bpkad'];
            $groupedData[$tbl['nmrek2']]['totalSelisih'] += $selisihnya;
    
            $totalApbd += $tbl['apbd'];
            if ($apbdp_checkbox) {
                $totalApbdp += $tbl['apbdp'];
            }
            $totalBap += $tbl['dipenda'];
            $totalBpkad += $tbl['bpkad'];
            $totalSelisih += $selisihnya;
        }
    }

    if (!empty($groupedData)) {
        foreach ($groupedData as $nmrek2 => $data) {
            ?>
            <tr>
                <td><?= htmlspecialchars($data['kdrek2']) ?></td>
                <td><strong><?= htmlspecialchars($nmrek2) ?></strong></td>
                <td><b><?= number_format($data['totalApbd'], 2) ?></b></td>
                <?php if ($apbdp_checkbox): ?>
                    <td><b><?= number_format($data['totalApbdp'], 2) ?></b></td>
                <?php endif; ?>
                <td><b><?= number_format($data['totalBap'], 2) ?></b></td>
                <td><b><?= number_format($data['totalBpkad'], 2) ?></b></td>
                <td><b><?= number_format($data['totalSelisih'], 2) ?></b></td>
                <td><?= htmlspecialchars($tbl['keterangan']) ?></td>
                <td></td>
            </tr>

            <?php foreach ($data['subTotals'] as $nmrek3 => $subData) { ?>
                <tr>
                    <td><?= htmlspecialchars($subData['kdrek3']) ?></td>
                    <td><strong><?= htmlspecialchars($nmrek3) ?></strong></td>
                    <td><b><?= number_format($subData['totalApbd'], 2) ?></b></td>
                    <?php if ($apbdp_checkbox): ?>
                        <td><b><?= number_format($subData['totalApbdp'], 2) ?></b></td>
                    <?php endif; ?>
                    <td><b><?= number_format($subData['totalBap'], 2) ?></b></td>
                    <td><b><?= number_format($subData['totalBpkad'], 2) ?></b></td>
                    <td><b><?= number_format($subData['totalSelisih'], 2) ?></b></td>
                    <td><?= htmlspecialchars($tbl['keterangan']) ?></td>
                    <td></td>
                </tr>
            <?php foreach ($subData['subTotals'] as $kdrek4 => $subSubData) { 
                if (!empty($subSubData['nmrek4'])) { ?>
                    <tr>
                        <td><?= htmlspecialchars($kdrek4) ?></td>
                        <td><?= htmlspecialchars($subSubData['nmrek4']) ?></td>
                        <td><?= number_format($subSubData['totalApbd'], 2) ?></td>
                        <?php if ($apbdp_checkbox): ?>
                            <td><?= number_format($subSubData['totalApbdp'], 2) ?></td>
                        <?php endif; ?>
                        <td><?= number_format($subSubData['totalBap'], 2) ?></td>
                        <td><?= number_format($subSubData['totalBpkad'], 2) ?></td>
                        <td><?= number_format($subSubData['totalSelisih'], 2) ?></td>
                        <td><?= htmlspecialchars($tbl['keterangan']) ?></td>
                        <td><?= htmlspecialchars($tbl['nmdinas']) ?></td>
                    </tr>
                    <?php foreach ($subSubData['subSubTotals'] as $kdrek5 => $subSubSubData) { 
                        if (!empty($subSubSubData['nmrek5'])) { ?>
                            <tr>
                                <td><?= htmlspecialchars($kdrek5) ?></td>
                                <td><?= htmlspecialchars($subSubSubData['nmrek5']) ?></td>
                                <td><?= number_format($subSubSubData['apbd'], 2) ?></td>
                                <?php if ($apbdp_checkbox): ?>
                                    <td><?= number_format($subSubSubData['apbdp'], 2) ?></td>
                                <?php endif; ?>
                                <td><?= number_format($subSubSubData['dipenda'], 2) ?></td>
                                <td><?= number_format($subSubSubData['bpkad'], 2) ?></td>
                                <td><?= number_format($subSubSubData['selisih'], 2) ?></td>
                                <td><?= htmlspecialchars($tbl['keterangan']) ?></td>
                                <td><?= htmlspecialchars($tbl['nmdinas']) ?></td>
                            </tr>
                        <?php }
                    }
                }
            } ?>
        <?php } ?>
    <?php } ?>
    <tr style="  background-color: #f2f2f2;">
        <td colspan="2"><b>JUMLAH PENDAPATAN + PEMBIAYAAN</b></td>
        <td><b><?= number_format($totalApbd, 2) ?></b></td>
        <?php if ($apbdp_checkbox): ?>
            <td><b><?= number_format($totalApbdp, 2) ?></b></td>
        <?php endif; ?>
        <td><b><?= number_format($totalBap, 2) ?></b></td>
        <td><b><?= number_format($totalBpkad, 2) ?></b></td>
        <td><b><?= number_format($totalSelisih, 2) ?></b></td>
        <td></td>
        <td></td>
    </tr>

<?php } ?>

</tbody>


</table>
<div class="footer-section">
    <div class="tgl_cetak">
        <p>Bandar Lampung, <?= $tgl_cetak_format ?></p>
    </div>
</div>
<?php if (!empty($tanda_tangan_1)) : ?>
    <div class="signature">
        <p><?= htmlspecialchars($tanda_tangan_1['jabatan1']) ?></p>
        <p><?= htmlspecialchars($tanda_tangan_1['jabatan2']) ?>,</p>
        <p class="name"><?= htmlspecialchars($tanda_tangan_1['nama']) ?></p>
        <p>NIP. <?= htmlspecialchars($tanda_tangan_1['nip']) ?></p>
    </div>
<?php endif; ?>
<?php if (!empty($tanda_tangan_2)) : ?>
    <div class="signature2">
        <p><?= htmlspecialchars($tanda_tangan_2['jabatan1']) ?></p>
        <p><?= htmlspecialchars($tanda_tangan_2['jabatan2']) ?>,</p>
        <p class="name"><?= htmlspecialchars($tanda_tangan_2['nama']) ?></p>
        <p>NIP. <?= htmlspecialchars($tanda_tangan_2['nip']) ?></p>
    </div>
<?php endif; ?>

</body>
</html>