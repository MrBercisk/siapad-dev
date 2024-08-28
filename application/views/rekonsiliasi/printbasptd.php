<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .sub-header{
            font-size: 13px;
            font-weight: 400;
            font-style: italic;
            
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
            margin-bottom : 20px;
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
            padding: 5px;
            text-wrap: nowrap;

        }
        tbody td:first-child,
        tbody td:nth-child(2) {
            text-align: left;
        }
        tbody td:nth-child(2) {
            text-wrap: nowrap;
        
   
        }
        .tgl_cetak p {
            font-size: 12px;
            text-align: center;
            margin-top: 50px;
            margin-bottom: 50px;
            margin-right: 50px;
            position: relative;
            float: right;
            clear: both;
        }
        .footer-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
         }
     
        .signature2 {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin-top: 60px;
            margin-right: 30px;
            position: relative;
            float: right;
            clear: both;
        }
        .signature2 .jabatan1 {
            margin-top: 10px;
        }
        .signature2 .name {
            text-decoration: underline;
            font-weight: bold;
            margin-top: 70px;
        }
        .signature {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin-top: 40px;
            margin-left: 30px;
            position: relative;
            float: left;
            clear: both;
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
function penyebut($nilai)
{
    $nilai = abs($nilai);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " " . $huruf[$nilai];
    } else if ($nilai < 20) {
        $temp = penyebut($nilai - 10) . " belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
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
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );


    return  $bulan[(int)$tanggal[1]];
}
function bulan_tanggal($tanggal)
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

    $bulan_tanggal = (int)date('m', strtotime($tanggal));
    return $bulan[$bulan_tanggal];
}
function tanggal_hari_ini($tanggal)
{
    $hari = (int)date('j', strtotime($tanggal));
    return terbilang($hari);
}
function hari_ini($hari)
{

    $hari1 = explode('-', $hari);
    $hari2 = $hari1[2];
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

    return  $hari_ini;
}

?>
<div class="header">
    <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo">
    <h3>PEMERINTAH KOTA BANDAR LAMPUNG</h3>
    <h3>BERITA ACARA REKONSILIASI BIDANG PEMBUKUAN DAN PELAPORAN DENGAN BIDANG PAJAK</h3>
    <h3>PEMBAYARAN SURAT PEMBERITAHUAN PAJAK DAERAH (SPTPD) PAJAK HOTEL, RESTORAN, HIBURAN DAN PARKIR</h3>
    <h3>BULAN: <?= $format_bulan; ?> s.d. <?= $format_bulan_akhir; ?> <?= $format_tahun;?></h3>
   
</div>
<div class="sub-header">
    <h3>Pada hari ini, <?= hari_ini($tglcetak) ?>, tanggal <?= tanggal_hari_ini($tglcetak) ?> , bulan <?= bulan_tanggal($tglcetak) ?> , 
    <?= terbilang($format_tahun) ?>, telah dilakukan Rekonsiliasi Data Pembayaran Surat Pemberitahuan Pajak Daerah (SPTPD) Pajak Reklame, Air Tanah dan Mineral Bukan Logam dan Batuan yang Diterbitkan pada Bidang Pajak dengan Surat Setoran Pajak Daerah/Surat Tanda Setoran (SSPD/STS) Pajak Pajak Reklame, Air Tanah dan Mineral Bukan Logam dan Batuan Yang Diterima pada Bidang Bidang Pembukuan dan Pelaporan (Buklap) Badan Pendapatan Daerah (BAPENDA) Kota Bandar Lampung Bulan 
    <?= $format_bulan; ?> s.d. <?= $format_bulan_akhir; ?> Tahun <?= terbilang($format_tahun) ?> dan diperoleh data Selisih SPTPD dan SSPD/STS, sebagai berikut :
</h3>
</div>

<table>
  
    <thead>
        <tr>
            <th rowspan="2">N0</th>
            <th colspan="14">SPTPD DITERBITKAN BULAN <?= strtoupper($format_bulan); ?> s.d. <?= strtoupper($format_bulan_akhir); ?> <?= $format_tahun;?></th>
            <th colspan="8">SSPD/STS TERBAYAR BULAN <?= strtoupper($format_bulan); ?> s.d. <?= strtoupper($format_bulan_akhir); ?> <?= $format_tahun;?></th>
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
            <th>TANGGAL TRANSAKSI</th>
            <th>NAMA OBJEK PAJAK</th>
            <th>UPTD</th>
            <th>MASA PAJAK</th>
            <th>NO. SSPD/STS</th>
            <th>POKOK</th>
            <th>DENDA</th>
            <th>JUMLAH</th>
            <th>POKOK</th>
            <th>DENDA</th>
            <th>JUMLAH</th>
   
        </tr>
    </thead>
    <tbody>

        <?php 
        function bulan_indonesia($bulan)
        {
            $bulan_arr = array(
                '01' => 'Januari',
                '02' => 'Februari',
                '03' => 'Maret',
                '04' => 'April',
                '05' => 'Mei',
                '06' => 'Juni',
                '07' => 'Juli',
                '08' => 'Agustus',
                '09' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember'
            );
        
            return $bulan_arr[$bulan] ?? $bulan;  
        }
        if (!empty($tablenya)): 
            $no = 1;
            $totalpokok = 0;
            $totaldenda = 0;
            $totalseluruh = 0;
            $totalpokoksts = 0;
            $totaldendasts = 0;
            $totalseluruhsts = 0;
            ?>
            <?php foreach ($tablenya as $row):
                   $selisihpokok = $row['pokok'] - $row['pokok_sts'];
                   $selisihdenda = $row['denda'] - $row['denda_sts'];
                   $selisihtotal = $row['total'] - $row['jumlah_sts'];
                   $totalpokok += $row['pokok'];
                   $totaldenda += $row['denda'];
                   $totalseluruh += $row['total'];
       
                   $totalpokoksts += $row['pokok_sts'];
                   $totaldendasts += $row['denda_sts'];
                   $totalseluruhsts += $row['jumlah_sts'];
       
                   $totalselisihpokok =  $totalpokok - $totalpokoksts;
                   $totalselisihdenda =  $totaldenda - $totaldendasts;
                   $totalselisihseluruh =  $totalseluruh - $totalseluruhsts;
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td style="text-align: left;"><?= $row['nopelaporan']; ?></td>
                    <td style="text-align: left;"><?= $row['npwpd']; ?></td>
                    <td style="text-align: left;"><?= $row['nama']; ?></td>
                    <td style="text-align: left;"><?= $row['namawp']; ?></td>
                    <td style="text-align: left;"><?= $row['alamat']; ?></td>
                    <td><?= $row['thnpajak']; ?></td>
                    <td><?= bulan_indonesia($row['masapajak']); ?></td>
                    <td><?= $row['tglsts']; ?></td>
                    <td><?= number_format($row['pokok'], 2); ?></td>
                    <td><?= number_format($row['denda'], 2); ?></td>
                    <td><?= number_format($row['total'], 2); ?></td> 
                    <td><?= $row['kodebayar']; ?></td> 
                    <td><?= $row['tglsts']; ?></td>
                    <td><?= ($row['tglsts'] == '0000-00-00' ? 'Belum Lunas' : 'Lunas') ?></td>
                    <td style="text-align: center;"><?= $row['tgl_bayar']; ?></td>
                    <td style="text-align: left;"><?= $row['namawp']; ?></td>
                    <td style="text-align: center;"><?= $row['namauptd']; ?></td>
                    <td style="text-align: left;"><?= $row['blnpajak']; ?>-<?= $row['thnpajak']; ?></td>
                    <td style="text-align: left;"><?= $row['sspd']; ?></td>
                    <td style="text-align: right;"><?= number_format($row['pokok_sts'], 2); ?></td>
                    <td style="text-align: right;"><?= number_format($row['denda_sts'], 2); ?></td>
                    <td style="text-align: right;"><?= number_format($row['jumlah_sts'], 2); ?></td>
                    <td style="text-align: right;"><?= number_format($selisihpokok, 2); ?></td>
                    <td style="text-align: right;"><?= number_format($selisihdenda, 2); ?></td>
                    <td style="text-align: right;"><?= number_format($selisihtotal, 2); ?></td>
                    <td style="text-align: left;"><?= $row['keterangan']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="12">Tidak ada data yang terbit.</td>
            </tr>
        <?php endif; ?>
        <tr>
        <td colspan=9" style="font-weight: bold;">JUMLAH</td>
        <td style="text-align: right; font-weight: bold;"><?= number_format($totalpokok, 2); ?></td> 
        <td style="text-align: right; font-weight: bold;"><?= number_format($totaldenda, 2); ?></td> 
        <td style="text-align: right; font-weight: bold;"><?= number_format($totalseluruh, 2); ?></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right; font-weight: bold;"><?= number_format($totalpokoksts, 2); ?></td> 
        <td style="text-align: right; font-weight: bold;"><?= number_format($totaldendasts, 2); ?></td> 
        <td style="text-align: right; font-weight: bold;"><?= number_format($totalseluruhsts, 2); ?></td>
        <td style="text-align: right; font-weight: bold;"><?= number_format($totalselisihpokok, 2); ?></td> 
        <td style="text-align: right; font-weight: bold;"><?= number_format($totalselisihdenda, 2); ?></td> 
        <td style="text-align: right; font-weight: bold;"><?= number_format($totalselisihseluruh, 2); ?></td>
        <td></td>
    </tr>
    


     </tbody>


</table>
<div style="width: 1000px; margin: 0 auto;">
    <div style="text-align: right;">
        Bandar Lampung, <?= $tgl_cetak_format; ?>
    </div>

    <div style="display: flex; justify-content: space-between;  margin-top: 60px;">
        <div style="width: 50%;">
            <p>KEPALA BIDANG PEMBUKUAN DAN PELAPORAN,</p>
            <br><br>
            <p><?= isset($nama_1) ? $nama_1 : '' ?><br>
            NIP. <?= isset($nip_1) ? $nip_1 : '' ?></p>
        </div>

        <div style="width: 50%; text-align: right;">
            <p>KASUBBID PEMBUKUAN,</p>
            <br><br>
            <p><?= isset($nama_2) ? $nama_2 : '' ?><br>
            NIP. <?= isset($nip_2) ? $nip_2 : '' ?></p>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; margin-top: 60px;">
        <div style="width: 48%;">
            <p>KEPALA BIDANG PAJAK,</p>
            <br><br>
            <p><?= isset($nama_3) ? $nama_3 : '' ?><br>
            NIP. <?= isset($nip_3) ? $nip_3 : '' ?></p>
        </div>

        <div style="width: 48%; text-align: right;">
            <p>KASUBBID PAJAK HOTEL, HIBURAN dan PAJAK LAINNYA</p>
            <br><br>
            <p><?= isset($nama_4) ? $nama_4 : '' ?><br>
            NIP. <?= isset($nip_4) ? $nip_4 : '' ?></p>
        </div>
    </div>

    <div style="text-align: center; margin-top: 60px;">
        <p>Mengetahui,</p>
    </div>

    <div style="text-align: center; margin-top: 40px;">
        <p>KEPALA BAPENDA,<br>KOTA BANDAR LAMPUNG,</p>
        <br><br>
        <p><?= isset($nama_5) ? $nama_5 : '' ?><br>
        NIP. <?= isset($nip_5) ? $nip_5 : '' ?></p>
    </div>
</div>

</body>
</html>