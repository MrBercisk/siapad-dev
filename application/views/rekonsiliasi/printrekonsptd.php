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
            margin-bottom: 60px;
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
            text-wrap: nowrap;
        }
        tbody td {
            text-align: right;
            text-wrap: nowrap;
            padding: 5px;
        }
        tbody td:first-child,
        tbody td:nth-child(2) {
            text-align: left;
        }
        tbody td:nth-child(2) {
            text-wrap: nowrap;
        
   
        }
        .tgl_cetak p {
            text-align: center;
            margin-top: 50px;
            margin-bottom: 50px;
            margin-right: 70px;
            position: relative;
            float: right;
            clear: both;
            font-size: 12px;
        }
        .signature {
            font-weight: bold;
            text-align: center;
            margin-top: 60px;
            margin-right: 20px;
            position: relative;
            float: right;
            clear: both;
            font-size: 12px;
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
        '01' =>   'Januari',
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
    <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
    <h3>REKONSILIASI SURAT PEMBERITAHUAN PAJAK DAERAH (SPTPD) DAN SURAT SETORAN PAJAK DAERAH (SSPD)/SURAT TANDA SETORAN (STS)</h3>
    <?php if (!empty($kdrekening)) : ?>
        <h3> <?= strtoupper($kdrekening['nmrekening']) ?></h3>
    <?php endif; ?>
</div>


<table>
  
    <thead>
        <tr>
            <th rowspan="2">N0</th>
            <th colspan="11">SPTPD TERBAYAR S.D. BULAN DESEMBER <?= $format_tahun;?></th>
            <th colspan="3">SELISIH SPTPD DENGAN SSPD/STS</th>
            <th colspan="8">SSPD/STS TERBAYAR S.D. BULAN DESEMBER 2024</th>
            <th rowspan="2">KETERANGAN</th>
        </tr>
        <tr>
            <!-- <th>Jenis Pajak</th> -->
            <th>No. Pelaporan</th>
            <th>NPWPD</th>
            <th>Nama Pajak</th>
            <th>Tahun Pajak</th>
            <th>Masa Pajak</th>
            <th>Pokok</th>
            <th>Denda</th>
            <th>Total</th>
            <th>Kode Bayar</th>
            <th>Tanggal Bayar</th>
            <th>Status</th>
            <th>Pokok</th>
            <th>Denda</th>
            <th>Jumlah</th>
            <th>Tanggal Transaksi</th>
            <th>Nama Objek Pajak</th>
            <th>UPTD</th>
            <th>Masa Pajak</th>
            <th>NO. SSPD/STS</th>
            <th>Pokok</th>
            <th>Denda</th>
            <th>Jumlah</th>
   
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
        $v = [];
        $baristanpdabeda = [];
        $totalpokok = 0;
        $totaldenda = 0;
        $totalseluruh = 0;
        $totalpokoksts = 0;
        $totaldendasts = 0;
        $totalseluruhsts = 0;
      
        foreach ($tablenya as $row):
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
    
            if ($selisihpokok != 0 || $selisihdenda != 0 || $selisihtotal != 0) {
                $v[] = $row;
            } else {
                $baristanpdabeda[] = $row;
            }
        endforeach;
    
      
        foreach ($baristanpdabeda as $row):
            $selisihpokok = $row['pokok'] - $row['pokok_sts'];
            $selisihdenda = $row['denda'] - $row['denda_sts'];
            $selisihtotal = $row['total'] - $row['jumlah_sts'];
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td style="text-align: left;"><?= $row['nopelaporan']; ?></td>
                <td style="text-align: left;"><?= $row['npwpd']; ?></td>
                <td style="text-align: left;"><?= $row['namawp']; ?></td>
                <td style="text-align: center;"><?= $row['thnpajak']; ?></td>
                <td style="text-align: center;"><?= bulan_indonesia($row['masapajak']); ?></td>
                <td style="text-align: right;"><?= number_format($row['pokok'], 2); ?></td>
                <td style="text-align: right;"><?= number_format($row['denda'], 2); ?></td>
                <td style="text-align: right;"><?= number_format($row['total'], 2); ?></td> 
                <td style="text-align: center;"><?= $row['kodebayar']; ?></td> 
                <td style="text-align: center;"><?= $row['tgl_input']; ?></td>
                <td style="text-align: left;"><?= ($row['tgl_bayar'] == '0000-00-00' ? 'Belum Lunas' : 'Lunas') ?></td>
                <td style="text-align: right;">-</td>
                <td style="text-align: right;">-</td>
                <td style="text-align: right;">-</td>
                <td style="text-align: center;"><?= $row['tgl_bayar']; ?></td>
                <td style="text-align: left;"><?= $row['namawp']; ?></td>
                <td style="text-align: center;"><?= $row['namauptd']; ?></td>
                <td style="text-align: left;"><?= $row['blnpajak']; ?>-<?= $row['thnpajak']; ?></td>
                <td style="text-align: left;"><?= $row['sspd']; ?></td>
                <td style="text-align: right;"><?= number_format($row['pokok_sts'], 2); ?></td>
                <td style="text-align: right;"><?= number_format($row['denda_sts'], 2); ?></td>
                <td style="text-align: right;"><?= number_format($row['jumlah_sts'], 2); ?></td> 
                <td style="text-align: left;"><?= $row['keterangan']; ?></td>
            </tr>
        <?php endforeach;

        foreach ($v as $row):
            $selisihpokok = $row['pokok'] - $row['pokok_sts'];
            $selisihdenda = $row['denda'] - $row['denda_sts'];
            $selisihtotal = $row['total'] - $row['jumlah_sts'];
            ?>
            <tr style="background-color: yellow;">
                <td><?= $no++ ?></td>
                <td style="text-align: left;"><?= $row['nopelaporan']; ?></td>
                <td style="text-align: left;"><?= $row['npwpd']; ?></td>
                <td style="text-align: left;"><?= $row['namawp']; ?></td>
                <td style="text-align: center;"><?= $row['thnpajak']; ?></td>
                <td style="text-align: center;"><?= bulan_indonesia($row['masapajak']); ?></td>
                <td style="text-align: right;"><?= number_format($row['pokok'], 2); ?></td>
                <td style="text-align: right;"><?= number_format($row['denda'], 2); ?></td>
                <td style="text-align: right;"><?= number_format($row['total'], 2); ?></td> 
                <td style="text-align: center;"><?= $row['kodebayar']; ?></td> 
                <td style="text-align: center;"><?= $row['tgl_input']; ?></td>
                <td style="text-align: left;"><?= ($row['tgl_bayar'] == '0000-00-00' ? 'Belum Lunas' : 'Lunas') ?></td>
                <td style="text-align: right;"><?= number_format($selisihpokok, 2); ?></td>
                <td style="text-align: right;"><?= number_format($selisihdenda, 2); ?></td>
                <td style="text-align: right;"><?= number_format($selisihtotal, 2); ?></td>
                <td style="text-align: center;"><?= $row['tgl_bayar']; ?></td>
                <td style="text-align: left;"><?= $row['namawp']; ?></td>
                <td style="text-align: center;"><?= $row['namauptd']; ?></td>
                <td style="text-align: left;"><?= $row['blnpajak']; ?>-<?= $row['thnpajak']; ?></td>
                <td style="text-align: left;"><?= $row['sspd']; ?></td>
                <td style="text-align: right;"><?= number_format($row['pokok_sts'], 2); ?></td>
                <td style="text-align: right;"><?= number_format($row['denda_sts'], 2); ?></td>
                <td style="text-align: right;"><?= number_format($row['jumlah_sts'], 2); ?></td> 
                <td style="text-align: left;"><?= $row['keterangan']; ?></td>
            </tr>
        <?php endforeach;
    
    else: ?>
        <tr>
            <td colspan="15" style="text-align: center;">Tidak Ada Data</td>
        </tr>
    <?php endif; ?>  
    <tr>
        <td colspan=6" style="font-weight: bold;">JUMLAH</td>
        <td style="text-align: right; font-weight: bold;"><?= number_format($totalpokok, 2); ?></td> 
        <td style="text-align: right; font-weight: bold;"><?= number_format($totaldenda, 2); ?></td> 
        <td style="text-align: right; font-weight: bold;"><?= number_format($totalseluruh, 2); ?></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right; font-weight: bold;"><?= number_format($totalselisihpokok, 2); ?></td> 
        <td style="text-align: right; font-weight: bold;"><?= number_format($totalselisihdenda, 2); ?></td> 
        <td style="text-align: right; font-weight: bold;"><?= number_format($totalselisihseluruh, 2); ?></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right; font-weight: bold;"><?= number_format($totalpokoksts, 2); ?></td> 
        <td style="text-align: right; font-weight: bold;"><?= number_format($totaldendasts, 2); ?></td> 
        <td style="text-align: right; font-weight: bold;"><?= number_format($totalseluruhsts, 2); ?></td>
        <td></td>
    </tr>

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