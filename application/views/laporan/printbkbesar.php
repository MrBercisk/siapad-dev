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
            table-layout: fixed;
        }
        table, th, td {
            border: 1px solid black;
        }
        th {
            padding: 10px;
            text-align: center;
            font-size: 15px;
        }
        td {
            font-size: 15px;
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
$tanggal_sebelumnya = date('Y-m-d', strtotime('-1 day', strtotime($format_tanggal)));
$tanggal_sebelumnya_display = strftime('%d %B %Y', strtotime($tanggal_sebelumnya));
$tgl_cetak_format = strftime('%d %B %Y', strtotime($tgl_cetak));
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
            <th>NO. BUKTI<br>(STS/NOTA DEBET/KREDIT)</th>
            <th>KODE REKENING</th>
            <th>URAIAN</th>
            <th>DINAS</th>
            <th>JUMLAH</th>
            <th>KETERANGAN</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $tanggal_saat_ini = strftime('%d %B %Y'); 
        $tanggal_sebelumnya = strftime('%d %B %Y', strtotime('-1 day'));
        if (!empty($tablenya)):
            foreach($tablenya as $tbl): ?>
                <tr>
                    <td><?= htmlspecialchars($tbl['nomor'])?></td>
                    <td><?= htmlspecialchars($tbl['kdrekening'])?></td>
                    <td><?= htmlspecialchars($tbl['uraian'])?></td>
                    <td><?= htmlspecialchars($tbl['nmdinas'])?></td>
                    <td><?= number_format($tbl['jumlah'], 2)?></td>
                    <td><?= htmlspecialchars($tbl['keterangan'])?></td>

                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
<h4>Keterangan</h4>
<table cellpadding="4">
            
            <tr>
                <td style="width:20%;">Penerimaan Kasda</td>
                <td style="width:40%;">Rp. 999.690.684.927,78</td>
            </tr>
            <tr>
                <td style="width:20%;">PEMBIAYAAN</td>
                <td style="width:40%;">Rp. 999.690.684.927,78</td>
            </tr>
            <tr>
                <td style="width:30%;">Jumlah Pendapatan + Pembiayaan</td>
                <td style="width:30%;">Rp. 1.007.550.565.412,16</td>
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
