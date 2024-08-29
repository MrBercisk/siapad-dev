<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
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
            border: 2px solid black;
        }
        th {
            padding: 4px;
            text-align: center;
            font-size: 11px;
            text-wrap:nowrap;
        }
        td {
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
            /* text-wrap: nowrap; */
        }
        tbody td {
            text-align: right;
            text-wrap: nowrap;
            /* padding: 2px; */
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
    <h3>BADAN PENDAPATAN DAERAH</h3>
    <h3>REKONSILIASI SSPD/STS DAN SPTPD/STPD</h3>
    <?php if (!empty($kdrekening)) : ?>
        <h3> <?= strtoupper($kdrekening['nmrekening']) ?></h3>
    <?php endif; ?>
    <h3>BULAN : <?= $format_bulan; ?> <?= $format_tahun; ?></h3>
</div>


<table>
  
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Tgl Transaksi</th>
            <th rowspan="2">No SPTPD/STPD</th>
            <th rowspan="2">Tgl SPTPD/STPD</th>
            <th rowspan="2">Nama Wajib Pajak</th>
            <th rowspan="2">Masa Pajak</th>
            <th colspan="1">Bidang Pembukuan & Pelaporan</th>
            <th colspan="1">Bidang Pendapatan</th>      
            <th rowspan="2">Selisih</th>
            <th rowspan="2">Keterangan</th>
        </tr>
        <tr>
            <th>SSPD/STS (RP)</th>
            <th>SPTPD/STPD (RP)</th>
        </tr>
    </thead>
    <tbody>
    <?php
    function formatTanggal($tanggal) {
        $parts = explode('-', $tanggal);
    
        if (count($parts) == 3) {
            return "{$parts[2]}-{$parts[1]}-{$parts[0]}";
        }
        return $tanggal; 
    }
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
        
        foreach ($tablenya as $row): 
        ?>
            <tr>
                <td style="text-align: center;"><?= $no++ ?></td>
                <td style="text-align: center;" ><?= formatTanggal(htmlspecialchars($row['tanggal'])) ?></td>
                <td style="text-align: left;" ><?= htmlspecialchars($row['nosptpd']) ?></td>
                <td style="text-align: left;" ><?= htmlspecialchars($row['tglsptpd']) ?></td>
                <td style="text-align: left;" ><?= htmlspecialchars($row['nmwp']) ?></td>
                <td style="text-align: center;" ><?= htmlspecialchars($row['masapajak']) ?></td>
                <td style="text-align: right;" ><?= number_format($row['jmlsts'],2) ?></td>
                <td style="text-align: right;" ><?= number_format($row['jmlsptpd'],2) ?></td>
                <td style="text-align: right;" ><?= number_format($row['selisih'],2) ?></td>
                <td style="text-align: left;" ><?= htmlspecialchars($row['keterangan']) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php else: ?>
                <tr>
                    <td colspan="10" style="text-align: center; font-weight: bold; font-size:10px; padding:5px;">
                        Tidak ada data pada bulan <?= htmlspecialchars($format_bulan) ?> <?= htmlspecialchars($format_tahun) ?>
                    </td>
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