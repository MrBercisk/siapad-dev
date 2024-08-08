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
        th:nth-child(1) {
            width: 15%;
        }
        th:nth-child(2) {
            width: 25%;
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
    <colgroup>
        <col style="width: 15%;">
        <col style="width: 25%;">
        <col style="width: 10%;">
        <?php if ($apbdp_checkbox): ?>
            <col style="width: 10%;">
        <?php endif; ?>

        <col>
    </colgroup>
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
    $totalGroupApbd = 0;
    $totalGroupApbdp = 0;
    $totalGroupBap = 0;
    $totalGroupBpkad = 0;
    $totalGroupSelisih = 0;

    $currentNmrek3 = '';

   if (!empty($tablenya)): ?>
        <?php
        $totalApbd = $totalBap = $totalBpkad = $totalSelisih = 0;
        $currentNmrek3 = '';
        $totalGroupApbd = $totalGroupApbdp = $totalGroupBap = $totalGroupBpkad = $totalGroupSelisih = 0;
        ?>
    
        <?php foreach ($tablenya as $tbl): ?>
            <?php
            $selisihnya = $tbl['dipenda'] - $tbl['bpkad'];
            $totalApbd += $tbl['apbd'];
            $totalBap += $tbl['dipenda'];
            $totalBpkad += $tbl['bpkad'];
            $totalSelisih += $selisihnya;
    
            if ($tbl['nmrek3'] !== $currentNmrek3) {
                if ($currentNmrek3 !== '') {
                    ?>
                    <tr>
                        <td></td>
                        <td><strong><?= htmlspecialchars($currentNmrek3) ?></strong></td>
                        <td><b><?= number_format($totalGroupApbd, 2) ?></b></td>
                        <?php if ($apbdp_checkbox): ?>
                            <td><b><?= number_format($totalGroupApbdp, 2) ?></b></td>
                        <?php endif; ?>
                        <td><b><?= number_format($totalGroupBap, 2) ?></b></td>
                        <td><b><?= number_format($totalGroupBpkad, 2) ?></b></td>
                        <td><b><?= number_format($totalGroupSelisih, 2) ?></b></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php
                }
                $currentNmrek3 = $tbl['nmrek3'];
                $totalGroupApbd = $totalGroupApbdp = $totalGroupBap = $totalGroupBpkad = $totalGroupSelisih = 0;
    
                ?>
            
                <?php
            }
            ?>
    
            <?php
            $totalGroupApbd += $tbl['apbd'];
            if ($apbdp_checkbox) {
                $totalGroupApbdp += $tbl['apbdp'];
            }
            $totalGroupBap += $tbl['dipenda'];
            $totalGroupBpkad += $tbl['bpkad'];
            $totalGroupSelisih += $selisihnya;
            ?>
    
            <tr>
                <td><?= htmlspecialchars($tbl['kdrek4']) ?></td>
                <td><strong><?= htmlspecialchars($tbl['nmrek4']) ?></strong></td>
                <td><?= number_format($tbl['apbd'], 2) ?></td>
                <?php if ($apbdp_checkbox): ?>
                    <td><?= number_format($tbl['apbdp'], 2) ?></td>
                <?php endif; ?>
                <td><?= number_format($tbl['dipenda'], 2) ?></td>
                <td><?= number_format($tbl['bpkad'], 2) ?></td>
                <td><?= number_format($selisihnya, 2) ?></td>
                <td><?= htmlspecialchars($tbl['keterangan']) ?></td>
                <td><?= htmlspecialchars($tbl['nmdinas']) ?></td>
            </tr>
    
            <tr>
                <td><?= htmlspecialchars($tbl['kdrek5']) ?></td>
                <td><strong><?= htmlspecialchars($tbl['nmrek5']) ?></strong></td>
                <td><?= number_format($tbl['apbd'], 2) ?></td>
                <?php if ($apbdp_checkbox): ?>
                    <td><?= number_format($tbl['apbdp'], 2) ?></td>
                <?php endif; ?>
                <td><?= number_format($tbl['dipenda'], 2) ?></td>
                <td><?= number_format($tbl['bpkad'], 2) ?></td>
                <td><?= number_format($selisihnya, 2) ?></td>
                <td><?= htmlspecialchars($tbl['keterangan']) ?></td>
                <td><?= htmlspecialchars($tbl['nmdinas']) ?></td>
            </tr>
        <?php endforeach; ?>

        <tr>
            <td></td>
            <td><strong><?= htmlspecialchars($currentNmrek3) ?></strong></td>
            <td><b><?= number_format($totalGroupApbd, 2) ?></b></td>
            <?php if ($apbdp_checkbox): ?>
                <td><b><?= number_format($totalGroupApbdp, 2) ?></b></td>
            <?php endif; ?>
            <td><b><?= number_format($totalGroupBap, 2) ?></b></td>
            <td><b><?= number_format($totalGroupBpkad, 2) ?></b></td>
            <td><b><?= number_format($totalGroupSelisih, 2) ?></b></td>
            <td></td>
            <td></td>
        </tr>
    
        <tr>
            <td></td>
            <td><strong>JUMLAH PENDAPATAN + PEMBIAYAAN</strong></td>
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
    <?php endif; ?>
    

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