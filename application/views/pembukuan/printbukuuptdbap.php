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
<img src="<?= base_url('/assets/img/logo.png') ?>" alt="Logo">
    <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
    <h3>DINAS PENDAPATAN DAERAH</h3>
    <h3>BUKU PENERIMAAN KAS</h3>
    <?php if(!empty($iduptd)) :?>
        <h3>UPT <?= $iduptd['nama']?></h3>
    <?php endif; ?>
    <h3>PER TANGGAL : <?= $tgl_format; ?></h3>
</div>
<table>
    <thead>
        <tr>
            <th>NO BUKTI</th>
            <th>KODE REKENING</th>
            <th>URAIAN</th>
            <th>NAMA WAJIB PAJAK</th>
            <th>MASA PAJAK</th>
            <th>JUMLAH</th>
            <th>KETERANGAN</th>
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
            $total_jmlhariini = 0;

            foreach ($tablenya as $row): 
               $total_sampai_bulan = $row['saldoawal']; 
               $total_jmlhariini += $row['total'];
               $total_seluruh = $total_jmlhariini + $total_sampai_bulan;
            ?>
                    
            <tr>
                <td style="text-align: left; padding:5px;" ><?= htmlspecialchars($row['nomor']) ?></td>
                <td style="text-align: left; padding:5px;" ><?= htmlspecialchars($row['kdrekening']) ?></td>
                <td style="text-align: left; padding:5px;" ><?= htmlspecialchars($row['nmrekening']) ?></td>
                <td style="text-align: left; padding:5px;" ><?= htmlspecialchars($row['nmwp']) ?></td>
                <td style="text-align: left; padding:5px;" ><?= htmlspecialchars($row['masapajak']) ?></td>
                <td style="text-align: right; padding:5px;" ><?= number_format($row['total'],2) ?></td>
                <td style="text-align: left; padding:5px;" ><?= htmlspecialchars($row['keterangan']) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="5" style="font-weight: bold;">JUMLAH PER </td>
                <td style="text-align: right; padding:5px;" ><?= number_format($total_jmlhariini) ?></td>
                <td></td>

               
            </tr>
            <tr>
                <td colspan="5" style="font-weight: bold;">JUMLAH S.D  </td>
                <td style="text-align: right; padding:5px;" ><?= number_format($total_sampai_bulan) ?></td>
                <td></td>
           
            
            </tr>
            <tr>
                <td colspan="5" style="font-weight: bold;">JUMLAH PER  </td>
                <td style="text-align: right; padding:5px;" ><?= number_format($total_seluruh) ?></td>
                <td></td>
               
           
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