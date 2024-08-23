<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
<?php
setlocale(LC_ALL, 'id-ID', 'id_ID');
$tanggal_saat_ini = strftime('%d %B %Y');
$tanggal_sebelumnya = strftime('%d %B %Y', strtotime('-1 day'));
?>
<div class="header">
    <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo">
    <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
    <h3>BADAN PENDAPATAN DAERAH</h3>
    <h3>LAPORAN REALISASI ANGGARAN PENDAPATAN DAERAH</h3>
    <h3>TANGGAL <?= strtoupper($tgl_format); ?></h3>
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
            <?php if ($apbdp_checkbox || $audited || $un_audited): ?>
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
            <?php if ($apbdp_checkbox || $audited || $un_audited): ?>
                <th>4</th>
            <?php endif; ?>
            <th>5</th>
            <th>6</th>
            <th>7</th>
            <th>8</th>
            <th>9 = 7-4</th>
            <th>10</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $totalApbd = 0;
        $totalApbdp = 0;
        $totalLalu = 0;
        $totalIni = 0;
        $totalBap = 0;
        $totalBpkad = 0;
        $totalSelisih = 0;
        $groupedData = [];

        if (!empty($tablenya)) {
            foreach ($tablenya as $tbl) {

             // Initialize or update grouping structure
if (!isset($groupedData[$tbl['nmrek2']])) {
    $groupedData[$tbl['nmrek2']] = [
        'kdrek2' => $tbl['kdrek2'],
        'totlalu' => 0,
        'totini' => 0,
        'totalApbd' => 0,
        'totalApbdp' => 0,
        'subTotals' => []
    ];
}

if (!isset($groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']])) {
    $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']] = [
        'kdrek3' => $tbl['kdrek3'],
        'totlalu' => 0,
        'totini' => 0,
        'totalApbd' => 0,
        'totalApbdp' => 0,
        'subTotals' => []
    ];
}

if (!isset($groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']])) {
    $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']] = [
        'kdrek4' => $tbl['kdrek4'],
        'nmrek4' => $tbl['nmrek4'],
        'totlalu' => 0,
        'totini' => 0,
        'totalApbd' => 0,
        'totalApbdp' => 0,
        'subSubTotals' => []
    ];
}

if (!isset($groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']])) {
    $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']] = [
        'kdrek5' => $tbl['kdrek5'],
        'nmrek5' => $tbl['nmrek5'],
        'totlalu' => 0,
        'totini' => 0,
        'totalApbd' => 0,
        'totalApbdp' => 0,
        'subSubSubTotals' => []
    ];
}

if (!isset($groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['subSubSubTotals'][$tbl['kdrek6']])) {
    $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['subSubSubTotals'][$tbl['kdrek6']] = [
        'kdrek6' => $tbl['kdrek6'],
        'nmrek6' => $tbl['nmrek6'],
        'totlalu' => 0,
        'totini' => 0,
        'totalApbd' => 0,
        'totalApbdp' => 0
    ];
}

// Update totals
$groupedData[$tbl['nmrek2']]['totlalu'] += $tbl['totlalu'];
$groupedData[$tbl['nmrek2']]['totini'] += $tbl['totini'];
$groupedData[$tbl['nmrek2']]['totalApbd'] += $tbl['apbd'];
if ($apbdp_checkbox || $audited || $un_audited) {
    $groupedData[$tbl['nmrek2']]['totalApbdp'] += $tbl['apbdp'];
}

$groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['totlalu'] += $tbl['totlalu'];
$groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['totini'] += $tbl['totini'];
$groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['totalApbd'] += $tbl['apbd'];
if ($apbdp_checkbox || $audited || $un_audited) {
    $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['totalApbdp'] += $tbl['apbdp'];
}

$groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['totlalu'] += $tbl['totlalu'];
$groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['totini'] += $tbl['totini'];
$groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['totalApbd'] += $tbl['apbd'];
if ($apbdp_checkbox || $audited || $un_audited) {
    $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['totalApbdp'] += $tbl['apbdp'];
}

$groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['totlalu'] += $tbl['totlalu'];
$groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['totini'] += $tbl['totini'];
$groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['totalApbd'] += $tbl['apbd'];
if ($apbdp_checkbox || $audited || $un_audited) {
    $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['totalApbdp'] += $tbl['apbdp'];
}

$groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['subSubSubTotals'][$tbl['kdrek6']]['totlalu'] += $tbl['totlalu'];
$groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['subSubSubTotals'][$tbl['kdrek6']]['totini'] += $tbl['totini'];
$groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['subSubSubTotals'][$tbl['kdrek6']]['totalApbd'] += $tbl['apbd'];
if ($apbdp_checkbox || $audited || $un_audited) {
    $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['subSubSubTotals'][$tbl['kdrek6']]['totalApbdp'] += $tbl['apbdp'];
}

                if (!isset($totalApbd)) {
                    $totalApbd = 0;
                }
                $totalApbd += $tbl['apbd'];

                if ($apbdp_checkbox || $audited || $un_audited) {
                    if (!isset($totalApbdp)) {
                        $totalApbdp = 0;
                    }
                    $totalApbdp += $tbl['apbdp'];
                }
                /* End */

                if (!isset($totalLalu)) {
                    $totalLalu = 0;
                }
                $totalLalu += $tbl['totlalu'];

                if (!isset($totalIni)) {
                    $totalIni = 0;
                }
                $totalIni += $tbl['totini'];
                $totalSeluruh = ($totalLalu + $totalIni);
             
                if ($apbdp_checkbox || $audited || $un_audited) {
                    if ($totalApbdp != 0) {
                        $totalPersen = ($totalSeluruh / $totalApbdp) * 100;
                        $totalSisa = $totalSeluruh - $totalApbdp;
                        $totalSisaPersen = ($totalSisa / $totalApbdp) * 100;

                        $formatnyaTotalSisa = $totalSisa < 0 ? '(' . number_format($totalSisa, 2) . ')' : number_format($totalSisa, 2);
                        $formatnyaTotalSisaPersen = $totalSisaPersen < 0 ? '(' . number_format($totalSisaPersen, 2) . ')' : number_format($totalSisaPersen, 2);
                    }
                } else {
                    if ($totalApbd != 0) {
                        $totalPersen = ($totalSeluruh / $totalApbd) * 100;
                        $totalSisa = $totalSeluruh - $totalApbd;
                        $totalSisaPersen = ($totalSisa / $totalApbd) * 100;

                        $formatnyaTotalSisa = $totalSisa < 0 ? '(' . number_format(abs($totalSisa), 2) . ')' : number_format($totalSisa, 2);
                        $formatnyaTotalSisaPersen = $totalSisaPersen < 0 ? '(' . number_format(abs($totalSisaPersen), 2) . ')' : number_format($totalSisaPersen, 2);
                        
                    }
                }

          
            }
        }

        if (!empty($groupedData)) {
            foreach ($groupedData as $nmrek2 => $data) {
                $total = $data['totlalu'] + $data['totini'];
                $persen = 0;

                if ($apbdp_checkbox || $audited || $un_audited && $data['totalApbdp'] > 0) {
                    $persen = ($total / $data['totalApbdp']) * 100;
                    $sisa = $total - $data['totalApbdp'];
                    $persensisa = ($sisa / $data['totalApbdp']) * 100;
                } elseif ($data['totalApbd'] > 0) {
                    $persen = ($total / $data['totalApbd']) * 100;
                    $sisa = $total - $data['totalApbd'];
                    $persensisa = ($sisa / $data['totalApbd']) * 100;
                }
                ?>
                <tr>
                    <td><?= htmlspecialchars($data['kdrek2']) ?></td>
                    <td><strong><?= htmlspecialchars($nmrek2) ?></strong></td>
                    <td><b><?= number_format($data['totalApbd'], 2) ?></b></td>
                    <?php if ($apbdp_checkbox || $audited || $un_audited): ?>
                        <td><b><?= number_format($data['totalApbdp'], 2) ?></b></td>
                    <?php endif; ?>
                    <td><b><?= number_format($data['totlalu'], 2) ?></b></td>
                    <td><b><?= number_format($data['totini'], 2) ?></b></td>
                    <td><b><?= number_format($total, 2) ?></b></td>
                    <td><b><?= number_format($persen, 2) ?></b></td>
                    <td><b><?= number_format($sisa, 2) ?></b></td>
                    <td><b><?= number_format($persensisa, 2) ?></b></td>
                </tr>

                <?php foreach ($data['subTotals'] as $nmrek3 => $subData) { 
                      $subTotal = $subData['totlalu'] + $subData['totini'];
                      $subPersen = 0;

                   
                    if ($apbdp_checkbox || $audited || $un_audited && $subData['totalApbdp'] > 0) {
                        $subPersen = ($subTotal / $subData['totalApbdp']) * 100;
                        $subSisa = $subTotal - $subData['totalApbdp'];
                        $subPersensisa = ($subSisa / $subData['totalApbdp']) * 100;
                    } elseif ($subData['totalApbd'] > 0) {
                        $subPersen = ($subTotal / $subData['totalApbd']) * 100;
                        $subSisa = $subTotal - $subData['totalApbd'];
                        $subPersensisa = ($subSisa / $subData['totalApbd']) * 100;
                    }
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($subData['kdrek3']) ?></td>
                        <td><strong><?= htmlspecialchars($nmrek3) ?></strong></td>
                        <td><b><?= number_format($subData['totalApbd'], 2) ?></b></td>
                        <?php if ($apbdp_checkbox || $audited || $un_audited): ?>
                            <td><b><?= number_format($subData['totalApbdp'], 2) ?></b></td>
                        <?php endif; ?>
                        <td><b><?= number_format($subData['totlalu'], 2) ?></b></td>
                        <td><b><?= number_format($subData['totini'], 2) ?></b></td>
                        <td><b><?= number_format($subTotal, 2) ?></b></td>
                        <td><b><?= number_format($subPersen, 2) ?></b></td>
                        <td><b><?= number_format($subSisa, 2) ?></b></td>
                        <td><b><?= number_format($subPersensisa, 2) ?></b></td>
                    </tr>

                    <?php foreach ($subData['subTotals'] as $kdrek4 => $subSubData) {
                      $subSubTotal = $subSubData['totlalu'] + $subSubData['totini'];
                      $subSubPersen = 0;

                        if ($apbdp_checkbox || $audited || $un_audited && $subSubData['totalApbdp'] > 0) {
                            $subSubPersen = ($subSubTotal / $subSubData['totalApbdp']) * 100;
                            $subSubSisa = $subSubTotal - $subSubData['totalApbdp'];
                            $subSubPersensisa = ($subSubSisa / $subSubData['totalApbdp']) * 100;
                        } elseif ($subSubData['totalApbd'] > 0) {
                            $subSubPersen = ($subSubTotal / $subSubData['totalApbd']) * 100;
                            $subSubSisa = $subSubTotal - $subSubData['totalApbd'];
                            $subSubPersensisa = ($subSubSisa / $subSubData['totalApbd']) * 100;
                        }
                     
                        if (!empty($subSubData['nmrek4'])) { 
                           
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($kdrek4) ?></td>
                                <td><b><?= strtoupper(htmlspecialchars($subSubData['nmrek4'])) ?></b></td>
                                <td><b><?= number_format($subSubData['totalApbd'], 2) ?></b></td>
                                <?php if ($apbdp_checkbox || $audited || $un_audited): ?>
                                    <td><b><?= number_format($subSubData['totalApbdp'], 2) ?></b></td>
                                <?php endif; ?>
                                <td><b><?= number_format($subSubData['totlalu'], 2) ?></b></td>
                                <td><b><?= number_format($subSubData['totini'], 2) ?></b></td>
                                <td><b><?= number_format($subSubTotal, 2) ?></b></td>
                                <td><b><?= number_format($subSubPersen, 2) ?></b></td>
                                <td><b><?= number_format($subSubSisa, 2) ?></b></td>
                                <td><b><?= number_format($subSubPersensisa, 2) ?></b></td>
                            </tr>

                            <?php foreach ($subSubData['subSubTotals'] as $kdrek5 => $subSubSubData) {
                                $subSubSubTotal = $subSubSubData['totlalu'] + $subSubSubData['totini'];
                                $subSubSubPersen = 0;
                                $subSubSubSisa = 0;
                                $subSubSubPersensisa = 0;

                                if ($apbdp_checkbox || $audited || $un_audited && $subSubSubData['totalApbdp'] > 0) {
                                    $subSubSubPersen = ($subSubSubTotal / $subSubSubData['totalApbdp']) * 100;
                                    $subSubSubSisa = $subSubSubTotal - $subSubSubData['totalApbdp'];
                                    $subSubSubPersensisa = ($subSubSubSisa / $subSubSubData['totalApbdp']) * 100;
                                } elseif ($subSubSubData['totalApbd'] > 0) {
                                    $subSubSubPersen = ($subSubSubTotal / $subSubSubData['totalApbd']) * 100;
                                    $subSubSubSisa = $subSubSubTotal - $subSubSubData['totalApbd'];
                                    $subSubSubPersensisa = ($subSubSubSisa / $subSubSubData['totalApbd']) * 100;
                                }
            
                                if (!empty($subSubSubData['nmrek5'])) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($kdrek5) ?></td>
                                        <td><?= htmlspecialchars($subSubSubData['nmrek5']) ?></td>
                                        <td><?= number_format($subSubSubData['totalApbd'], 2) ?></td>
                                        <?php if ($apbdp_checkbox || $audited || $un_audited): ?>
                                            <td><?= number_format($subSubSubData['totalApbdp'], 2) ?></td>
                                        <?php endif; ?>
                                        <td><?= number_format($subSubSubData['totlalu'], 2) ?></td>
                                        <td><b><?= number_format($subSubSubData['totini'], 2) ?></b></td>
                                        <td><?= number_format($subSubSubTotal, 2) ?></td>
                                        <td><?= number_format($subSubSubPersen, 2) ?></td>
                                        <td><?= number_format($subSubSubSisa, 2) ?></td>
                                        <td><?= number_format($subSubSubPersensisa, 2) ?></td>
                                    </tr>

                                    <?php foreach ($subSubSubData['subSubSubTotals'] as $kdrek6 => $subSubSubSubData) {
                                         $subSubSubSubTotal = $subSubSubSubData['totlalu'] + $subSubSubSubData['totini'];
                                         $subSubSubSubPersen = 0;
                                         $subSubSubSubSisa = 0;
                                         $subSubSubSubPersensisa = 0;

                                         if ($apbdp_checkbox || $audited || $un_audited && $subSubSubSubData['totalApbdp'] > 0) {
                                             $subSubSubSubPersen = ($subSubSubSubTotal / $subSubSubSubData['totalApbdp']) * 100;
                                             $subSubSubSubSisa = $subSubSubSubTotal - $subSubSubSubData['totalApbdp'];
                                             $subSubSubSubPersensisa = ($subSubSubSubSisa / $subSubSubSubData['totalApbdp']) * 100;
                                         } elseif ($subSubSubSubData['totalApbd'] > 0) {
                                             $subSubSubSubPersen = ($subSubSubSubTotal / $subSubSubSubData['totalApbd']) * 100;
                                             $subSubSubSubSisa = $subSubSubSubTotal - $subSubSubSubData['totalApbd'];
                                             $subSubSubSubPersensisa = ($subSubSubSubSisa / $subSubSubSubData['totalApbd']) * 100;
                                         }
              
                                       if (!empty($subSubSubSubData['nmrek6'])) {
                                          
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($kdrek6) ?></td>
                                                <td><?= htmlspecialchars($subSubSubSubData['nmrek6']) ?></td>
                                                <td><?= number_format($subSubSubSubData['totalApbd'], 2) ?></td>
                                                <?php if ($apbdp_checkbox || $audited || $un_audited): ?>
                                                    <td><?= number_format($subSubSubSubData['totalApbdp'], 2) ?></td>
                                                <?php endif; ?>
                                                <td><?= number_format($subSubSubSubData['totlalu'], 2) ?></td>
                                                <td><?= number_format($subSubSubSubData['totini'], 2) ?></td>
                                                <td><?= number_format($subSubSubSubTotal, 2) ?></td>
                                                <td><?= number_format($subSubSubSubPersen, 2) ?></td>
                                                <td><?= number_format($subSubSubSubSisa, 2) ?></td>
                                                <td><?= number_format($subSubSubSubPersensisa, 2) ?></td>
                                            </tr>
                                        <?php } 
                                        }
                                    }
                                }
                            }
                        } ?>
                    <?php } ?>
                <?php } ?>
    
        <?php 
        // Calculate total persen values for the entire table
        // $totalPersenPend = ($totalApbd > 0) ? number_format(($totalTotlaluTotini / $totalApbd) * 100, 2) : '0.00';
        // $totalPersenSisa = ($totalApbd > 0) ? number_format(($totalSelisih / $totalApbd) * 100, 2) : '0.00';
        ?>
        <tr>
            <td colspan="2"><strong>Total Pendapatan + Pembiayaan</strong></td>
            <td><b><?= number_format($totalApbd, 2) ?></b></td>
            <?php if ($apbdp_checkbox || $audited || $un_audited): ?>
                <td><b><?= number_format($totalApbdp, 2) ?></b></td>
                <?php endif; ?>
            <td><b><?= number_format($totalLalu, 2) ?></b></td>
            <td><b><?= number_format($totalIni, 2) ?></b></td>
            <td><b><?= number_format($totalSeluruh, 2) ?></b></td>
            <td><b><?= number_format($totalPersen, 2); ?></b></td>
            <td><b><?= $formatnyaTotalSisa; ?></b></td>
            <td><b><?= $formatnyaTotalSisaPersen; ?></b></td>
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