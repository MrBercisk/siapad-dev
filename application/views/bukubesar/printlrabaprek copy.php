<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
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
            font-size: 8px;
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
    <h3>BUKU BESAR PEMBANTU PENDAPATAN DAERAH</h3>
    <h3>REALISASI ANGGARAN PAJAK BAPENDA KOTA BANDAR LAMPUNG PER WAJIB PAJAK</h3>
    <?php if (!empty($kdrekening)) : ?>
        <h3>REKENING : <?= $kdrekening['nmrekening'] ?></h3>
    <?php endif; ?>   
    <h3>TAHUN ANGGARAN : <?= $format_tahun ?></h3>
</div>


    <table border="1">
        <thead>
        <tr>
            <th>NO</th>
            <th>Nama Wajib Pajak</th>
            <th>APBD</th>
            <th>APBDP</th>
            <th>Januari</th>
            <th>Februari</th>
            <th>Maret</th>
            <th>April</th>
            <th>Mei</th>
            <th>Juni</th>
            <th>Juli</th>
            <th>Agustus</th>
            <th>September</th>
            <th>Oktober</th>
            <th>November</th>
            <th>Desember</th>
            <th>Jumlah</th>
            <th>Ket</th>
        </tr>
      
        </thead>
        <tbody>
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

        foreach ($tablenya as $row):  ?>
                
        <tr>
            <td rowspan="3" style="text-align: center;"><?= $no++ ?></td>
            <td rowspan="3" style="text-align: left;"><b><?= htmlspecialchars($row['nmwp']) ?></b>
            Alamat&nbsp; &nbsp;: <?= htmlspecialchars($row['alamat']) ?> <br>
            NPWPD&nbsp;: <?= htmlspecialchars($row['npwpd']) ?>
            </td>
            <td rowspan="3"></td>
            <td rowspan="3"></td>
            <td rowspan="3"></td>
            <td rowspan="3"></td>
            <td rowspan="3"></td>
            <td rowspan="3"></td>
            <td rowspan="3"></td>
            <td rowspan="3"></td>
            <td rowspan="3"></td>
            <td rowspan="3"></td>
            <td rowspan="3"></td>
            <td rowspan="3"></td>
            <td rowspan="3"></td>
            <td rowspan="3"></td>
            <td rowspan="3"></td>
            <td style="text-align: center;"><?= number_format($row['apbd'], 2) ?></td>
            <td style="text-align: center;"><?= number_format($row['apbdp'], 2) ?></td>  
            <td style="text-align: center;"><?= htmlspecialchars($row['jan_nosspd']) ?><br>
            <?= htmlspecialchars($row['jan_tglsspd']) ?> <br>
            <?= number_format($row['jan_pokok'],2) ?> <br>
            <?= number_format($row['jan_bunga'],2) ?> <br>
            <?= number_format($row['jan_jumlah'],2) ?>
            </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['feb_nosspd']) ?><br>
            <?= htmlspecialchars($row['feb_tglsspd']) ?> <br>
            <?= number_format($row['feb_pokok'],2) ?> <br>
            <?= number_format($row['feb_bunga'],2) ?> <br>
            <?= number_format($row['feb_jumlah'],2) ?>
            </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['mar_nosspd']) ?><br>
            <?= htmlspecialchars($row['mar_tglsspd']) ?> <br>
            <?= number_format($row['mar_pokok'],2) ?> <br>
            <?= number_format($row['mar_bunga'],2) ?> <br>
            <?= number_format($row['mar_jumlah'],2) ?>
            </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['apr_nosspd']) ?><br>
            <?= htmlspecialchars($row['apr_tglsspd']) ?> <br>
            <?= number_format($row['apr_pokok'],2) ?> <br>
            <?= number_format($row['apr_bunga'],2) ?> <br>
            <?= number_format($row['apr_jumlah'],2) ?>
            </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['mei_nosspd']) ?><br>
            <?= htmlspecialchars($row['mei_tglsspd']) ?> <br>
            <?= number_format($row['mei_pokok'],2) ?> <br>
            <?= number_format($row['mei_bunga'],2) ?> <br>
            <?= number_format($row['mei_jumlah'],2) ?>
            </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jun_nosspd']) ?><br>
            <?= htmlspecialchars($row['jun_tglsspd']) ?> <br>
            <?= number_format($row['jun_pokok'],2) ?> <br>
            <?= number_format($row['jun_bunga'],2) ?> <br>
            <?= number_format($row['jun_jumlah'],2) ?>
            </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jul_nosspd']) ?><br>
            <?= htmlspecialchars($row['jul_tglsspd']) ?> <br>
            <?= number_format($row['jul_pokok'],2) ?> <br>
            <?= number_format($row['jul_bunga'],2) ?> <br>
            <?= number_format($row['jul_jumlah'],2) ?>
            </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['aug_tglsspd']) ?><br>
            <?= htmlspecialchars($row['aug_tglsspd']) ?> <br>
            <?= number_format($row['aug_pokok'],2) ?> <br>
            <?= number_format($row['aug_bunga'],2) ?> <br>
            <?= number_format($row['aug_jumlah'],2) ?>
            </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['sep_tglsspd']) ?><br>
            <?= htmlspecialchars($row['sep_tglsspd']) ?> <br>
            <?= number_format($row['sep_pokok'],2) ?> <br>
            <?= number_format($row['sep_bunga'],2) ?> <br>
            <?= number_format($row['sep_jumlah'],2) ?>
            </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['okt_tglsspd']) ?><br>
            <?= htmlspecialchars($row['okt_tglsspd']) ?> <br>
            <?= number_format($row['okt_pokok'],2) ?> <br>
            <?= number_format($row['okt_bunga'],2) ?> <br>
            <?= number_format($row['okt_jumlah'],2) ?>
            </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['nov_tglsspd']) ?><br>
            <?= htmlspecialchars($row['nov_tglsspd']) ?> <br>
            <?= number_format($row['nov_pokok'],2) ?> <br>
            <?= number_format($row['nov_bunga'],2) ?> <br>
            <?= number_format($row['nov_jumlah'],2) ?>
            </td>
            <td style="text-align: center;"><?= htmlspecialchars($row['des_tglsspd']) ?><br>
            <?= htmlspecialchars($row['des_tglsspd']) ?> <br>
            <?= number_format($row['des_pokok'],2) ?> <br>
            <?= number_format($row['des_bunga'],2) ?> <br>
            <?= number_format($row['des_jumlah'],2) ?>
            </td>
            <td style="text-align: center;">
            <?= number_format($row['totpokok'],2) ?> <br>
            <?= number_format($row['totbunga'],2) ?> <br>
            <?= number_format($row['totjumlah'],2) ?>
            </td>
            <td></td>
           
    
        </tr>
        <tr>
            <td></td>
            <td style="text-align: left;">Nomor SSPD</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: left;">Tanggal SSPD</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: left;">Pokok Pajak</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: left;">Bunga</td>
        </tr>
        <tr>
            <td></td>    
            <td style="text-align: left;">Jumlah yang dibayar</td>
        </tr>
        <?php endforeach; ?>
        
    <?php else: ?>
        <tr>
            <td colspan="6" style="text-align: center;">Tidak Ada Data</td>
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
