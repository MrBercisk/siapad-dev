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

<div class="header">
    <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
    <h3>BADAN PENDAPATAN DAERAH</h3>
    <h4>BUKU PENERIMAAN KAS</h4>
    <h4>PER TANGGAL <?= $format_tanggal ?></h4>
</div>
<table class="table-container">
    <thead>
        <tr>
                <th rowspan="2">No. Bukti (STS/Nota Debit/Kredit)</th>
                <th rowspan="2">Kode Rekening</th>
                <th rowspan="2">Uraian</th>
                <th rowspan="2">Dinas</th>
                <th colspan="2">Jumlah</th>
                <th rowspan="2">Keterangan</th>
        </tr>  
        <tr>
                <th>Per Kode Rekening</th>
                <th>Per Setoran</th>
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
        $tanggal_saat_ini = strftime('%d %B %Y'); 
        $tanggal_sebelumnya = strftime('%d %B %Y', strtotime('-1 day'));
        if (!empty($tablenya)):
            foreach($tablenya as $tbl): ?>
                <tr>
                    <td><?= htmlspecialchars($tbl['nomor'])?></td>
                    <td><?= htmlspecialchars($tbl['koderekening'])?></td>
                    <td><?= htmlspecialchars($tbl['namarekening'])?></td>
                    <td><?= htmlspecialchars($tbl['singkatdinas'])?></td>
                    <td></td>
                    <td></td>
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
