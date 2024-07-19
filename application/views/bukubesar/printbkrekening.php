<!DOCTYPE html>
<html>
<head>
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
            border: 1px solid black;
            font-size: 12px;
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
        }
        tbody td:first-child,
        tbody td:nth-child(2) {
            text-align: left;
        }
        .tgl_cetak p {
            text-align: center;
            margin-top: 50px;
            margin-bottom: 50px;
            margin-right: 70px;
            position: relative;
            float: right;
            clear: both;
        } .signature {
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

<div class="header">
    <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
    <h3>BADAN PENDAPATAN DAERAH</h3>
    <h3>BUKU BESAR PEMBANTU PENERIMAAN</h3>
   
</div>
<div class="sub-header">
<?php if (!empty($kdrekening)) : ?>
        <h3>Kode Rekening&nbsp;&nbsp;  : <?= $kdrekening['kdrekening'] ?></h3>
        <h3>Nama Rekening&nbsp;&nbsp;  : <?= $kdrekening['nmrekening'] ?></h3>
    <?php endif; ?>
    <h3>APBD&nbsp;&nbsp;   : Rp. <?= number_format($total_apbd, 2) ?></h3>
    <h3>APBD-P&nbsp;&nbsp;: Rp. <?= number_format($total_apbdp, 2) ?></h3>
    <h3>BULAN&nbsp;&nbsp;  : <?= $format_bulan; ?> <?= $format_tahun ?></h3>
</div>
    <table border="1">
        <thead>
        <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2">Tanggal</th>
            <th rowspan="2">Uraian / No. Bukti</th>
            <th rowspan="2">Badan/Dinas/Instansi</th>
            <th rowspan="2">Ref.</th>
            <th rowspan="2">Jumlah (Rp)</th>
            <th colspan="2">Saldo</th>
            <th rowspan="2" colspan="2">Keterangan</th>
        </tr>
        <tr>
            <th>(Rp)</th>
            <th>(%)</th>
        </tr>
        <tr>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7</th>
            <th>8</th>
            <th colspan="2">9</th>
        </tr>
        <tr>
            <th></th>
            <th><?= $format_tahun ?> </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th colspan="2"></th>
        </tr>
        </thead>
        <tbody>
            <?php 
             $bulan_indonesia = array(
                'January' => 'Januari',
                'February' => 'Februari',
                'March' => 'Maret',
                'April' => 'April',
                'May' => 'Mei',
                'June' => 'Juni',
                'July' => 'Juli',
                'August' => 'Agustus',
                'September' => 'September',
                'October' => 'Oktober',
                'November' => 'November',
                'December' => 'Desember'
            );
            if (!empty($tablenya)):
                $no = 1;
                $saldo_awal = 0;
                $saldo_kumulatif = 0;
                $total_hari_ini = 0;
                $bulan_sebelumnya = null;
                $data_pertama = true; 
                
                    foreach ($tablenya as $row):  
                        $bulan_saat_ini_en = date('F', strtotime($row['tanggal'])); 
                        $bulan_saat_ini = $bulan_indonesia[$bulan_saat_ini_en]; 
                        $tanggal = date('d', strtotime($row['tanggal'])); 

                        if ($data_pertama || (substr($tanggal, -2) == '01' && $bulan_sebelumnya !== $bulan_saat_ini)): 
                    
                            ?>
                            <tr>
                                <td></td>
                                <td colspan="9" style="text-align: left; font-weight: bold;"><?= $bulan_saat_ini; ?></td>
                            </tr>
                            <?php 
                            $data_pertama = false;
                        endif;

                        if ($row['issaldoawal'] == 1): 
                            $saldo_awal = $row['jumlah'];
                            $saldo_kumulatif = $saldo_awal;
                            $persentase = ($total_apbd != 0) ? ($saldo_kumulatif / $total_apbd) * 100 : 0;
                            ?>
                            <tr>
                                <td style="text-align: center;"></td>
                                <td style="text-align: right;"> <?= htmlspecialchars($tanggal) ?></td>
                                <td style="text-align: left;"><?= htmlspecialchars($row['nobukti']) ?></td>
                                <td></td>
                                <td></td>
                                <td style="text-align: right;"></td>
                                <td style="text-align: right;"><?= number_format($saldo_awal, 2) ?></td>
                                <td style="text-align: right;"><?= number_format($persentase, 2) ?>%</td>
                                <td colspan="2" style="text-align: left;"><?= htmlspecialchars($row['keterangan']) ?></td>
                            </tr>
                        <?php else:
                            $saldo_kumulatif += $row['jumlah'];
                            $total_hari_ini += $row['jumlah']; 
                            $persentase = ($total_apbd != 0) ? ($saldo_kumulatif / $total_apbd) * 100 : 0;
                            ?>
                            <tr>
                                        <td style="text-align: center;"><?= $no++ ?></td>
                                        <td style="text-align: right;"><?= htmlspecialchars(date('d', strtotime($row['tanggal']))) ?></td>
                                        <td style="text-align: left;"><?= htmlspecialchars($row['nobukti']) ?></td>
                                        <td style="text-align: left;"><?= htmlspecialchars($row['nmdinas']) ?></td>
                                        <td></td>
                                        <td style="text-align: right;"><?= number_format($row['jumlah'], 2) ?></td>
                                        <td style="text-align: right;"><?= number_format($saldo_kumulatif, 2) ?></td>
                                        <td style="text-align: right;"><?= number_format($persentase, 2) ?>%</td>
                                        <td colspan="2" style="text-align: left;"><?= htmlspecialchars($row['keterangan']) ?></td>
                                    </tr>
                        <?php endif; 
                        $bulan_sebelumnya = $bulan_saat_ini;
                    endforeach; 
                    ?>
                    <tr style="font-weight: bold;">
                                <td></td>
                                <td></td>
                                <td style="text-align: right;">Jumlah</td>
                                <td></td>
                                <td></td>
                                <td><?= number_format($total_hari_ini, 2) ?></td>
                                <td><?= number_format($saldo_kumulatif, 2) ?></td>
                                <td style="text-align: right;"><?= number_format($persentase, 2) ?>%</td>
                                <td colspan="2"></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">Tidak Ada Data</td>
                    </tr>
                <?php endif; ?>

        </tbody>
    </table>

    <div class="footer-section">
    <div class="tgl_cetak">
        <p>Bandar Lampung, <?= $tgl_cetak_format ?></p>
    </div>
</div>
<?php if (!empty($tanda_tangan)) : ?>
    <div class="signature">
        <p><?= htmlspecialchars($tanda_tangan['jabatan1']) ?></p>
        <p><?= htmlspecialchars($tanda_tangan['jabatan2']) ?>,</p>
        <p class="name"><?= htmlspecialchars($tanda_tangan['nama']) ?></p>
        <p>NIP. <?= htmlspecialchars($tanda_tangan['nip']) ?></p>
    </div>
<?php endif; ?>
</div>
</body>
</html>
