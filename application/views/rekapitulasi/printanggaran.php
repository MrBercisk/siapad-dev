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

    
    <h3>BULAN : <?= $format_bulan; ?> <?= $format_tahun ?></h3>
</div>
<table>
    <thead>
        <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2">JENIS PAJAK</th>
            <th rowspan="2">JUMLAH SSPD/STS (Rp.)</th>
            <th colspan="3">JUMLAH WAJIB PAJAK (WP)/SKPD/STTS/SSPD</th>
            <th rowspan="2">KETERANGAN</th>
        </tr>
        <tr>
            <th>S.D BULAN LALU</th>
            <th>BULAN INI</th>
            <th>S.D BULAN INI</th>
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
            $no = 1;
            $count = 0;
            $total_jmlrp = 0;
            
            foreach ($tablenya as $row): 
                $total_jmlrp +=  $row['jmlrpini'];
            ?>
                    
            <tr>
                <td style="text-align: center;"><?= $no++ ?></td>
                <td style="text-align: left; padding:5px;" ><?= htmlspecialchars($row['nmrek']) ?></td>
                <td style="text-align: right; padding:5px;" ><?= number_format($row['jmlrpini'],2) ?></td>
                <td style="text-align: right; padding:5px;" ><?= number_format($row['jmlwplalu']) ?></td>
                <td style="text-align: right; padding:5px;" ><?= number_format($row['jmlwpini']) ?></td>
                <td style="text-align: right; padding:5px;" ><?= number_format($row['jmlwpsdini']) ?></td>
                <td style="text-align: left; padding:5px;" ><?= htmlspecialchars($row['satuan']) ?></td>

        
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="2">JUMLAH PER </td>
                <td style="text-align: right; padding:5px;" ><b><?= number_format($total_jmlrp ,2) ?></b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
               
            </tr>
            <tr>
                <td colspan="2">JUMLAH S.D </td>
                <td style="text-align: right; padding:5px;" ><b></b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            
            </tr>
            <tr>
                <td colspan="2">JUMLAH PER </td>
                <td style="text-align: right; padding:5px;" ><b><?= number_format($total_jmlrp ,2) ?></b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
           
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