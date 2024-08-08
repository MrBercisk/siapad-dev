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
            padding: 5px;
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
    <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
    <h3>BADAN PENDAPATAN DAERAH</h3>
    <h3>REKAPITULASI REALISASI ANGGARAN PENDAPATAN DAERAH</h3>
    <h3><?= strtoupper($nmrekening); ?></h3>

    
    <h3>PER :  <?= htmlspecialchars($tglakhir_format) ?></h3>
</div>
<table>
    <thead>
        <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2">TANGGAL TRANSAKSI</th>
            <th rowspan="2">JUMLAH SSPD/STS (Rp.)</th>
            <th rowspan="2">NAMA WAJIB PAJAK</th>
            <th rowspan="2">MASA PAJAK</th>
            <th colspan="1">JUMLAH</th>
            <th rowspan="2">KETERANGAN</th>
        </tr>
        <tr>
            <th>SSPD/STS(Rp)</th>
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
// Sort data in ascending order by 'nmwp'
usort($tablenya, function($a, $b) {
    return strcmp($a['nmwp'], $b['nmwp']);
});

function formatTanggal($tanggal) {
    $parts = explode('-', $tanggal);

    if (count($parts) == 3) {
        return "{$parts[2]}-{$parts[1]}-{$parts[0]}";
    }
    return $tanggal; 
}

if (!empty($tablenya)):
    $no = 1;
    $previousNmwp = '';
    $uniqueNmwpCount = 0;
    $count = 0;
    $total_jmlrp = 0;
    $total_sampai_bulan = $total_sampai_bulan;
    
    foreach ($tablenya as $row): 
        $total_jmlrp +=  $row['jumlah'];
        $total_seluruh = $total_jmlrp + $row['totlalu'];
        
        if ($row['nmwp'] !== $previousNmwp) {
            $currentNo = $no++; 
            $uniqueNmwpCount++; 
        } else {
            $currentNo = ''; 
        }
        
        $previousNmwp = $row['nmwp']; 
    ?>
        <tr>
            <td style="text-align: center;"><?= $currentNo ?></td>
            <td style="text-align: center; padding:4px;" ><?= formatTanggal(htmlspecialchars($row['tanggal'])) ?></td>
            <td style="text-align: left; padding:4px;" ><?= htmlspecialchars($row['nobukti']) ?></td>
            <td style="text-align: left; padding:4px;" ><?= htmlspecialchars($row['nmwp']) ?></td>
            <td style="text-align: center; padding:4px;" ><?= htmlspecialchars($row['masapajak']) ?></td>
            <td style="text-align: right; padding:4px;" ><?= number_format($row['jumlah'],2) ?></td>
            <td style="text-align: left; padding:4px;" ></td>
        </tr>
    <?php endforeach; ?>
   
    <tr>
        <td colspan="5" style="font-weight: bold;">JUMLAH PER <?= htmlspecialchars($tglakhir_format) ?></td>
        <td style="text-align: right; padding:4px;  font-weight: bold;" " ><?= number_format($total_jmlrp,2) ?></td>
        <td style="text-align: right; padding:4px; font-weight: bold;" ><?= ($row['jmlini']) ?> WP</td>
    </tr>
    <tr>
        <td colspan="5" style="font-weight: bold;">JUMLAH S.D <?= htmlspecialchars($tglsebelum_format) ?></td>
        <td style="text-align: right; padding:4px; font-weight: bold;" ><?= number_format($row['totlalu'],2) ?></td>
        <td style="text-align: right; padding:4px; font-weight: bold;" ><?= ($row['jmllalu']) ?> WP</td>
    </tr>
    <tr>
        <td colspan="5" style="font-weight: bold;">JUMLAH PER <?= htmlspecialchars($tglakhir_format) ?></td>
        <td style="text-align: right; padding:4px; font-weight: bold;" ><?= number_format($total_seluruh ,2) ?></td>
        <td style="text-align: right; padding:4px; font-weight: bold;" ><?= ($row['jmlsdini']) ?> WP</td>
    </tr>
<?php else: ?>
    <tr>
        <td></td>
        <td colspan="6" style="text-align: center;">Tidak Ada Data</td>
    </tr>
<?php endif; ?>


    </tbody>
</table>

<?php if(!empty($tglcetak)): ?>
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