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
            font-size: 14px;
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
            font-weight: 300;
            font-style: italic;
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
     function tanggal_hari_ini($tanggal)
    {
        $hari = (int)date('j', strtotime($tanggal));
        return terbilang($hari);
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
    ?>

<div class="header">

    <h2>BERITA ACARA REKONSILIASI BIDANG PEMBUKUAN DAN PELAPORAN DENGAN BIDANG PAJAK</h2>
    <h2>PEMBAYARAN SURAT PEMBERITAHUAN PAJAK DAERAH(SPTPD) PAJAK REKLAME, AIR TANAH DAN MINERAL BUKAN LOGAM DAN BATUAN</h2>
    <h2>BULAN <?= strtoupper(htmlspecialchars($format_bulan)) ?> s.d <?= strtoupper(htmlspecialchars($format_bulan_akhir)) ?>  <?= htmlspecialchars($format_tahun) ?></h2>
   
</div>
<div class="sub-header">
    <h3>Pada hari ini, <?= hari_ini($tglcetak) ?>, tanggal <?= tanggal_hari_ini($tglcetak) ?> , bulan <?= $format_bulan ?> ,
    <?= terbilang($format_tahun) ?>, telah dilakukan Rekonsiliasi Data Pembayaran Surat Pemberitahuan Pajak Daerah (SPTPD)
    Pajak Reklame, Air Tanah dan Mineral Bukan Logam dan Batuan yang Diterbitkan pada Bidang Pajak
    dengan Surat Setoran Pajak Daerah/Surat Tanda Setoran (SSPD/STS) Pajak Reklame, Air Tanah dan Mineral Bukan Logam dan Batuan Yang Diterima
    pada Bidang Pembukuan dan Pelaporan (Buklap) Badan Pendapatan Daerah (BAPENDA) Kota Bandar Lampung
    Bulan <?= $format_bulan ?> s.d Bulan <?= $format_bulan_akhir ?> Tahun <?= terbilang($format_tahun) ?> dan diperoleh data Selisih SPTPD dan SSPD/STS, sebagai berikut : 


</h3>
</div>

    <table border="2" align="center" cellpadding="3" cellspacing="3">
        <thead>
        <tr>
            <th width="25" rowspan="2">NO URUT</th>
            <th colspan="14">SPTPD TERBIT BULAN <?= $format_bulan ?> s.d <?= $format_bulan_akhir ?> <?= $format_tahun ?> </th>
            <th colspan="8">SSPD/STS TERBAYAR BULAN <?= $format_bulan ?> s.d <?= $format_bulan_akhir ?> <?= $format_tahun ?> </th>
            <th colspan="3">SELISIH SPTPD DENGAN SSPD/STS</th>
            <th rowspan="2">KETERANGAN</th>
        </tr>
                <tr>
                    <th>No. Pelaporan</th>
                    <th>NPWPD</th>
                    <th>Nama Pajak</th>
                    <th>Nama WP</th>
                    <th>Alamat OP</th>
                    <th>Tahun Pajak</th>
                    <th>Masa Pajak</th>
                    <th>Tanggal Disetujui</th>
                    <th>Pokok</th>
                    <th>Denda</th>
                    <th>Total</th>
                    <th>Kode Bayar</th>
                    <th>Tanggal Bayar</th>
                    <th>Status</th>
                    <th>Tanggal Transaksi</th>
                    <th>Nama Objek Pajak</th>
                    <th>UPTD</th>
                    <th>Masa Pajak</th>
                    <th>no. SSPD/STS</th>
                    <th>Pokok</th>
                    <th>Denda</th>
                    <th>Jumlah</th>
                    <th>Pokok</th>
                    <th>Denda</th>
                    <th>Jumlah</th>
                </tr>
               
        </thead>
        <tbody>
        <?php
          
            $no = 1;
            $total_pokok = 0;
            $total_denda = 0;
            $total_seluruh = 0;
           ?>
            <?php if (!empty($tablenya)) : ?>
                <?php foreach ($tablenya as $row) : 
                    $total_pokok += $row['pokok'];
                    $total_denda += $row['denda'];
                    $total_seluruh += $row['total'];
                    ?>
                    <tr>
                    <td><?= $no++; ?> </td>
                        <td><?= $row['thnpajak']; ?> </td>
                        <td><?= $row['nomor']; ?> </td>
                        <td><?= $row['tgl_input']; ?> </td>
                        <td><?= $row['nama']; ?> </td>
                        <td><?= $row['alamat']; ?> </td>
                        <td><?= $row['npwpd']; ?> </td>
                        <td><?= $row['masabulan']; ?> </td>
                        <td><?= $row['thnpajak']; ?> </td>
                        <td><?= number_format($row['pokok'], 2); ?> </td>
                        <td><?= number_format($row['denda'], 2); ?> </td>
                        <td>0</td>
                        <td><?= number_format($row['total'], 2); ?> </td>
                        <td> - </td>
                        <td><?= $row['sspd']; ?> </td>
                        <td><?= ($row['tgl_bayar'] == '0000-00-00' ? 'Belum Lunas' : 'Lunas') ?></td>
                        <td><?= $row['tgl_bayar']; ?> </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="17" style="text-align: center; font-weight: bold; font-size:10px; padding:5px;">
                        Tidak ada data pada bulan <?= htmlspecialchars($format_bulan) ?> <?= htmlspecialchars($format_tahun) ?>
                    </td>
                </tr>
            <?php endif; ?>

            <tr>
                <td colspan="9" style="font-weight:bold; padding:5px;">TOTAL</td>
                <td style="font-weight:bold;"><?= number_format($total_pokok, 2); ?></td>
                <td style="font-weight:bold;"><?= number_format($total_denda, 2); ?></td>
                <td style="font-weight:bold;">0</td>
                <td style="font-weight:bold;"><?= number_format($total_seluruh, 2); ?></td>
                <td colspan="4"></td>
            </tr>
           
    </table>

    <div class="footer-section">
    <div class="tgl_cetak">
        <p>Bandar Lampung, <?= $tgl_cetak_format ?></p>
    </div>
</div>

</body>
</html>
