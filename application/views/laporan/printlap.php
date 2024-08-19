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
            padding: 2px;
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
setlocale(LC_ALL, 'id-ID', 'id_ID');
$tanggal_saat_ini = strftime('%d %B %Y');
$tanggal_sebelumnya = strftime('%d %B %Y', strtotime('-1 day'));
?>
<div class="header">
    <img src="<?= base_url('/assets/img/logo.png') ?>" alt="Logo">
    <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
    <h3>BADAN PENDAPATAN DAERAH</h3>
    <h3>LAPORAN REALISASI ANGGARAN PENDAPATAN DAERAH</h3>
    <h3>TANGGAL <?= $tgl_format ?></h3>
    <?php if ($audited): ?>
        <h3>(AUDITED BPK TAHUN <?= $tahun_depannya; ?>)</h3>
    <?php endif; ?>
    <?php if ($un_audited): ?>
        <h3>(UN-AUDITED BPK TAHUN <?= $tahun_depannya; ?>)</h3>
    <?php endif; ?>
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
            <th colspan="4">REALISASI PENDAPATAN</th>
            <th colspan="2">SISA LEBIH / KURANG</th>
        </tr>
        <tr>
            <th>S.D HARI LALU</th>
            <th>HARI INI</th>
            <th>S.D HARI INI</th>
            <th>%</th>
            <th>JUMLAH</th>
            <th>%</th>
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
            <th>7</th>
            <th>8</th>
            <th>9</th>
            <th>10</th>
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

            if (!isset($groupedData[$tbl['nmrek2']])) {
                $groupedData[$tbl['nmrek2']] = [
                    'kdrek2' => $tbl['kdrek2'],
                    'totalApbd' => 0,
                    'totalApbdp' => 0,
                
                ];
            }
    
            if (!isset($groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']])) {
                $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']] = [
                    'kdrek3' => $tbl['kdrek3'],
                    'totalApbd' => 0,
                    'totalApbdp' => 0,
                  
                ];
            }
    
            if (!isset($groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']])) {
                $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']] = [
                    'kdrek4' => $tbl['kdrek4'],
                    'nmrek4' => $tbl['nmrek4'],
                    'totalApbd' => 0,
                    'totalApbdp' => 0,
         
                ];
            }
            if (!isset($groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']])) {
                $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']] = [
                    'kdrek5' => $tbl['kdrek5'],
                    'nmrek5' => $tbl['nmrek5'],
                    'apbd' => 0,
                    'apbdp' => 0,
            
                ];
            }
            if (!isset($groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['subSubSubTotals'][$tbl['kdrek6']])) {
                $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['subSubSubTotals'][$tbl['kdrek6']] = [
                    'kdrek6' => $tbl['kdrek6'],
                    'nmrek6' => $tbl['nmrek6'],
                    'apbd' => 0,
                    'apbdp' => 0,
            
                ];
            }

            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['subSubSubTotals'][$tbl['kdrek6']]['apbd'] += $tbl['apbd'];
            if ($apbdp_checkbox) {
                $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['subSubSubTotals'][$tbl['kdrek6']]['apbdp'] += $tbl['apbdp'];
            }


    
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['totalApbd'] += $tbl['apbd'];
            if ($apbdp_checkbox) {
                $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['totalApbdp'] += $tbl['apbdp'];
            }

 
    
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['totalApbd'] += $tbl['apbd'];
            if ($apbdp_checkbox) {
                $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['totalApbdp'] += $tbl['apbdp'];
            }

    
            $groupedData[$tbl['nmrek2']]['totalApbd'] += $tbl['apbd'];
            if ($apbdp_checkbox) {
                $groupedData[$tbl['nmrek2']]['totalApbdp'] += $tbl['apbdp'];
            }
           

    
            $totalApbd += $tbl['apbd'];
            if ($apbdp_checkbox) {
                $totalApbdp += $tbl['apbdp'];
            }
   

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
                    <td></td>
                </tr>
    
                <?php foreach ($subData['subTotals'] as $kdrek4 => $subSubData) { 
                    if (!empty($subSubData['nmrek4'])) { ?>
                        <tr>
                            <td><?= htmlspecialchars($kdrek4) ?></td>
                            <td><b><?= strtoupper(htmlspecialchars($subSubData['nmrek4'])) ?></b></td>
                            <td><b><?= number_format($subSubData['totalApbd'], 2) ?></b></td>
                            <?php if ($apbdp_checkbox): ?>
                                <td><b><?= number_format($subSubData['totalApbdp'], 2) ?></b></td>
                            <?php endif; ?>
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
                                    <td><?= htmlspecialchars($tbl['nmdinas']) ?></td>
                                </tr>
    
                                <?php foreach ($subSubSubData['subSubSubTotals'] as $kdrek6 => $subSubSubSubData) { 
                                    if (!empty($subSubSubSubData['nmrek6'])) { ?>
                                        <tr>
                                            <td><?= htmlspecialchars($kdrek6) ?></td>
                                            <td>-<?= htmlspecialchars($subSubSubSubData['nmrek6']) ?></td>
                                            <td><?= number_format($subSubSubSubData['apbd'], 2) ?></td>
                                            <?php if ($apbdp_checkbox): ?>
                                                <td><?= number_format($subSubSubSubData['apbdp'], 2) ?></td>
                                            <?php endif; ?>
                                            <td><?= htmlspecialchars($tbl['nmdinas']) ?></td>
                                        </tr>
                                    <?php }
                                }
                            }
                        }
                    }
                } ?>
            <?php } ?>
        <?php } ?>
    }
    
        <?php 
        // Calculate total percentage values for the entire table
        // $totalPersenPend = ($totalApbd > 0) ? number_format(($totalTotlaluTotini / $totalApbd) * 100, 2) : '0.00';
        // $totalPersenSisa = ($totalApbd > 0) ? number_format(($totalSelisih / $totalApbd) * 100, 2) : '0.00';
        ?>
        <tr>
            <td colspan="2"><strong>Total Pendapatan + Pembiayaan</strong></td>
            <td><?= number_format($totalApbd, 2) ?></td>
            <?php if ($apbdp_checkbox): ?>
                <td><?= number_format($totalApbdp, 2) ?></td>
            <?php endif; ?>
         
        </tr>
        <?php } ?>
</tbody>


</table>
<?php if(!empty($tgl_cetak)): ?>
    <div class="tgl_cetak">
        <p>Bandar Lampung, <?= strftime('%d %B %Y') ?></p>
    </div>
<?php endif; ?>

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