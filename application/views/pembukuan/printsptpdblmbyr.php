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
            border: 1px solid black;
            font-size: 9px;
        }
        th {
        background-color: #DDDDDD;
        font-weight: bold;
        vertical-align: middle;
        }

        td {
            vertical-align: middle;
        }
     
        tbody td {
            text-align: center;
            padding: 2px;
            font-size: 8px;
        }
      
        .tgl_cetak p {
            font-size: 10px;
            text-align: center;
            margin-top: 50px;
            margin-bottom: 50px;
            margin-right: 70px;
            position: relative;
            float: right;
            clear: both;
        } 

    </style>
</head>
<body>

<div class="header">
<img src="<?= base_url('/assets/img/logo.png') ?>" alt="Logo">
    <h2>DAFTAR SPTPD <?php if (!empty($kdrekening)) : ?>
                                    <?= strtoupper($kdrekening['nmrekening']); ?>
                                <?php endif; ?> YANG BELUM DIBAYAR</h2>
    <h3> <?= strtoupper(htmlspecialchars($format_bulan)) ?> <?= htmlspecialchars($format_tahun) ?></h3>
    <h3>  SPTPD YANG BELUM DIBAYAR BULAN <?= strtoupper(htmlspecialchars($format_bulan)) ?> <?= htmlspecialchars($format_tahun) ?></h3>
    <h3>(DATA BIDANG PEMBUKUAN DAN PELAPORAN)</h3>
   
</div>

    <table border="2" align="center" cellpadding="3" cellspacing="3">
        <thead>
        <tr>
                    <th rowspan="2">NO. URUT</th>
                    <th rowspan="2">TAHUN PAJAK</th>
                    <th colspan="2">SPTPD</th>
                    <th rowspan="2">NAMA WAJIB PAJAK (WP)</th>
                    <th rowspan="2">ALAMAT OBJEK PAJAK</th>
                    <th rowspan="2">NPWPD</th>
                    <th colspan="2">MASA PAJAK</th>
                    <th rowspan="2">POKOK</th>
                    <th rowspan="2">DENDA</th>
                    <th rowspan="2">DISCOUNT (POTONGAN PAJAK)</th>
                    <th rowspan="2">JUMLAH</th>
                </tr>
                <tr>
                    <th>NOMOR</th>
                    <th>TANGGAL TERBIT</th>
                    <th>BULAN</th>
                    <th>TAHUN</th>
                </tr>
        </thead>
        <tbody>
        <?php
           function penyebut($nilai)
           {
               $nilai = abs($nilai);
               $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
               $temp = "";
               
               if ($nilai < 12) {
                   $temp = " " . $huruf[$nilai];
               } elseif ($nilai < 20) {
                   $temp = penyebut($nilai - 10) . " belas";
               } elseif ($nilai < 100) {
                   $temp = penyebut(intval($nilai / 10)) . " puluh" . penyebut($nilai % 10);
               } elseif ($nilai < 200) {
                   $temp = " seratus" . penyebut($nilai - 100);
               } elseif ($nilai < 1000) {
                   $temp = penyebut(intval($nilai / 100)) . " ratus" . penyebut($nilai % 100);
               } elseif ($nilai < 2000) {
                   $temp = " seribu" . penyebut($nilai - 1000);
               } elseif ($nilai < 1000000) {
                   $temp = penyebut(intval($nilai / 1000)) . " ribu" . penyebut($nilai % 1000);
               } elseif ($nilai < 1000000000) {
                   $temp = penyebut(intval($nilai / 1000000)) . " juta" . penyebut($nilai % 1000000);
               } elseif ($nilai < 1000000000000) {
                   $temp = penyebut(intval($nilai / 1000000000)) . " milyar" . penyebut(fmod($nilai, 1000000000));
               } elseif ($nilai < 1000000000000000) {
                   $temp = penyebut(intval($nilai / 1000000000000)) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
               }
               
               return $temp;
           }
           
           function terbilang($nilai)
           {
               if ($nilai < 0) {
                   $hasil = "minus " . trim(penyebut($nilai));
               } else {
                   $hasil = trim(penyebut($nilai));
               }
               return $hasil;
           }
           
           function bulan_indo($tanggal)
           {
               $bulan = array(
                   1 => 'Januari',
                   2 => 'Februari',
                   3 => 'Maret',
                   4 => 'April',
                   5 => 'Mei',
                   6 => 'Juni',
                   7 => 'Juli',
                   8 => 'Agustus',
                   9 => 'September',
                   10 => 'Oktober',
                   11 => 'November',
                   12 => 'Desember'
               );
           
               $bulan_num = (int)date('m', strtotime($tanggal)); 
               return $bulan[$bulan_num];
           }
           
           function hari_ini($hari)
           {
               $hari = date("D", strtotime($hari));
               switch ($hari) {
                   case 'Sun':
                       $hari_ini = "Minggu";
                       break;
                   case 'Mon':
                       $hari_ini = "Senin";
                       break;
                   case 'Tue':
                       $hari_ini = "Selasa";
                       break;
                   case 'Wed':
                       $hari_ini = "Rabu";
                       break;
                   case 'Thu':
                       $hari_ini = "Kamis";
                       break;
                   case 'Fri':
                       $hari_ini = "Jumat";
                       break;
                   case 'Sat':
                       $hari_ini = "Sabtu";
                       break;
                   default:
                       $hari_ini = "Tidak di ketahui";
                       break;
               }
           
               return $hari_ini;
           }
            $no = 1;
            $total_pokok = 0;
            $total_denda = 0;
            $total_seluruh = 0;
           ?>
            <?php if (!empty($tablenya)) : ?>
                <?php foreach ($tablenya as $row) : 
                    $total_pokok += $row['pokok'];
                    $total_denda += $row['denda'];
                    $total_seluruh = $total_pokok + $total_denda;
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['thnpajak']; ?></td>
                        <td><?= $row['nomor']; ?></td>
                        <td><?= $row['tgl_input']; ?></td>
                        <td><?= $row['nama']; ?></td>
                        <td><?= $row['alamat']; ?></td>
                        <td><?= $row['npwpd']; ?></td>
                        <td><?= $row['masabulan']; ?></td>
                        <td><?= $row['thnpajak']; ?></td>
                        <td><?= number_format($row['pokok'], 2); ?></td>
                        <td><?= number_format($row['denda'], 2); ?></td>
                        <td>0</td>
                        <td><?= number_format($row['total'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="13" style="text-align: center; font-weight: bold; font-size:10px; padding:5px;">
                        Tidak ada data pada bulan <?= htmlspecialchars($format_bulan) ?> <?= htmlspecialchars($format_tahun) ?>
                    </td>
                </tr>
            <?php endif; ?>

            <tr>
                <td colspan="9" style="font-weight:bold;">TOTAL</td>
                <td style="font-weight:bold;"><?= number_format($total_pokok, 2); ?></td>
                <td style="font-weight:bold;"><?= number_format($total_denda, 2); ?></td>
                <td style="font-weight:bold;">0</td>
                <td style="font-weight:bold;"><?= number_format($total_seluruh, 2); ?></td>
            </tr>
           
    </table>

    <div class="footer-section">
    <div class="tgl_cetak">
        <p>Bandar Lampung, <?= $tgl_cetak_format ?></p>
    </div>
</div>

</body>
</html>
