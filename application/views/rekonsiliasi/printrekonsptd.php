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
        }
        tbody td {
            text-align: right;
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
    <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
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
            <th colspan="14">KODE SPTPD DITERBITKAN BULAN <?= strtoupper($format_bulan); ?> s.d. <?= strtoupper($format_bulan_akhir); ?> <?= $format_tahun;?></th>
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
    $totalApbd = 0;
    $totalApbdp = 0;
    $totalBap = 0;
    $totalBpkad = 0;
    $totalSelisih = 0;
    $groupedData = [];

    if (!empty($tablenya)) {
        foreach ($tablenya as $tbl) {
            $selisihnya = $tbl['dipenda'] - $tbl['bpkad'];
            if (!isset($groupedData[$tbl['nmrek2']])) {
                $groupedData[$tbl['nmrek2']] = [
                    'kdrek2' => $tbl['kdrek2'],
                    'totalApbd' => 0,
                    'totalApbdp' => 0,
                    'totalBap' => 0,
                    'totalBpkad' => 0,
                    'totalSelisih' => 0,
                    'subTotals' => []
                ];
            }
    
            if (!isset($groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']])) {
                $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']] = [
                    'kdrek3' => $tbl['kdrek3'],
                    'totalApbd' => 0,
                    'totalApbdp' => 0,
                    'totalBap' => 0,
                    'totalBpkad' => 0,
                    'totalSelisih' => 0,
                    'subTotals' => []
                ];
            }
    
            if (!isset($groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']])) {
                $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']] = [
                    'kdrek4' => $tbl['kdrek4'],
                    'nmrek4' => $tbl['nmrek4'],
                    'totalApbd' => 0,
                    'totalApbdp' => 0,
                    'totalBap' => 0,
                    'totalBpkad' => 0,
                    'totalSelisih' => 0,
                    'subSubTotals' => []
                ];
            }
            if (!isset($groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']])) {
                $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']] = [
                    'kdrek5' => $tbl['kdrek5'],
                    'nmrek5' => $tbl['nmrek5'],
                    'apbd' => 0,
                    'apbdp' => 0,
                    'dipenda' => 0,
                    'bpkad' => 0,
                    'selisih' => 0
                ];
            }

            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['apbd'] += $tbl['apbd'];
            if ($apbdp_checkbox) {
                $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['apbdp'] += $tbl['apbdp'];
            }
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['dipenda'] += $tbl['dipenda'];
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['bpkad'] += $tbl['bpkad'];
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['subSubTotals'][$tbl['kdrek5']]['selisih'] += $selisihnya;
    
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['totalApbd'] += $tbl['apbd'];
            if ($apbdp_checkbox) {
                $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['totalApbdp'] += $tbl['apbdp'];
            }
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['totalBap'] += $tbl['dipenda'];
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['totalBpkad'] += $tbl['bpkad'];
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['subTotals'][$tbl['kdrek4']]['totalSelisih'] += $selisihnya;
    
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['totalApbd'] += $tbl['apbd'];
            if ($apbdp_checkbox) {
                $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['totalApbdp'] += $tbl['apbdp'];
            }
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['totalBap'] += $tbl['dipenda'];
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['totalBpkad'] += $tbl['bpkad'];
            $groupedData[$tbl['nmrek2']]['subTotals'][$tbl['nmrek3']]['totalSelisih'] += $selisihnya;
    
            $groupedData[$tbl['nmrek2']]['totalApbd'] += $tbl['apbd'];
            if ($apbdp_checkbox) {
                $groupedData[$tbl['nmrek2']]['totalApbdp'] += $tbl['apbdp'];
            }
            $groupedData[$tbl['nmrek2']]['totalBap'] += $tbl['dipenda'];
            $groupedData[$tbl['nmrek2']]['totalBpkad'] += $tbl['bpkad'];
            $groupedData[$tbl['nmrek2']]['totalSelisih'] += $selisihnya;
    
            $totalApbd += $tbl['apbd'];
            if ($apbdp_checkbox) {
                $totalApbdp += $tbl['apbdp'];
            }
            $totalBap += $tbl['dipenda'];
            $totalBpkad += $tbl['bpkad'];
            $totalSelisih += $selisihnya;
        }
    }

    if (!empty($groupedData)) {
        foreach ($groupedData as $nmrek2 => $data) {
            ?>
            <tr>
                <td><?= htmlspecialchars($data['kdrek2']) ?></td>
                <td><strong><?= htmlspecialchars($nmrek2) ?></strong></td>
                <td><b><?= number_format($data['totalApbd'], 2) ?></b></td>
                <?php if ($apbdp_checkbox): ?>
                    <td><b><?= number_format($data['totalApbdp'], 2) ?></b></td>
                <?php endif; ?>
                <td><b><?= number_format($data['totalBap'], 2) ?></b></td>
                <td><b><?= number_format($data['totalBpkad'], 2) ?></b></td>
                <td><b><?= number_format($data['totalSelisih'], 2) ?></b></td>
                <td><?= htmlspecialchars($tbl['keterangan']) ?></td>
                <td></td>
            </tr>

            <?php foreach ($data['subTotals'] as $nmrek3 => $subData) { ?>
                <tr>
                    <td><?= htmlspecialchars($subData['kdrek3']) ?></td>
                    <td><strong><?= htmlspecialchars($nmrek3) ?></strong></td>
                    <td><b><?= number_format($subData['totalApbd'], 2) ?></b></td>
                    <?php if ($apbdp_checkbox): ?>
                        <td><b><?= number_format($subData['totalApbdp'], 2) ?></b></td>
                    <?php endif; ?>
                    <td><b><?= number_format($subData['totalBap'], 2) ?></b></td>
                    <td><b><?= number_format($subData['totalBpkad'], 2) ?></b></td>
                    <td><b><?= number_format($subData['totalSelisih'], 2) ?></b></td>
                    <td><?= htmlspecialchars($tbl['keterangan']) ?></td>
                    <td></td>
                </tr>
            <?php foreach ($subData['subTotals'] as $kdrek4 => $subSubData) { 
                if (!empty($subSubData['nmrek4'])) { ?>
                    <tr>
                        <td><?= htmlspecialchars($kdrek4) ?></td>
                        <td><?= htmlspecialchars($subSubData['nmrek4']) ?></td>
                        <td><?= number_format($subSubData['totalApbd'], 2) ?></td>
                        <?php if ($apbdp_checkbox): ?>
                            <td><?= number_format($subSubData['totalApbdp'], 2) ?></td>
                        <?php endif; ?>
                        <td><?= number_format($subSubData['totalBap'], 2) ?></td>
                        <td><?= number_format($subSubData['totalBpkad'], 2) ?></td>
                        <td><?= number_format($subSubData['totalSelisih'], 2) ?></td>
                        <td><?= htmlspecialchars($tbl['keterangan']) ?></td>
                        <td><?= htmlspecialchars($tbl['nmdinas']) ?></td>
                    </tr>
                    <?php foreach ($subSubData['subSubTotals'] as $kdrek5 => $subSubSubData) { 
                        if (!empty($subSubSubData['nmrek5'])) { ?>
                            <tr>
                                <td><?= htmlspecialchars($kdrek5) ?></td>
                                <td><?= htmlspecialchars($subSubSubData['nmrek5']) ?></td>
                                <td><?= number_format($subSubSubData['apbd'], 2) ?></td>
                                <?php if ($apbdp_checkbox): ?>
                                    <td><?= number_format($subSubSubData['apbdp'], 2) ?></td>
                                <?php endif; ?>
                                <td><?= number_format($subSubSubData['dipenda'], 2) ?></td>
                                <td><?= number_format($subSubSubData['bpkad'], 2) ?></td>
                                <td><?= number_format($subSubSubData['selisih'], 2) ?></td>
                                <td><?= htmlspecialchars($tbl['keterangan']) ?></td>
                                <td><?= htmlspecialchars($tbl['nmdinas']) ?></td>
                            </tr>
                        <?php }
                    }
                }
            } ?>
        <?php } ?>
    <?php } ?>
    <tr style="  background-color: #f2f2f2;">
        <td colspan="2"><b>JUMLAH PENDAPATAN + PEMBIAYAAN</b></td>
        <td><b><?= number_format($totalApbd, 2) ?></b></td>
        <?php if ($apbdp_checkbox): ?>
            <td><b><?= number_format($totalApbdp, 2) ?></b></td>
        <?php endif; ?>
        <td><b><?= number_format($totalBap, 2) ?></b></td>
        <td><b><?= number_format($totalBpkad, 2) ?></b></td>
        <td><b><?= number_format($totalSelisih, 2) ?></b></td>
        <td></td>
        <td></td>
    </tr>

<?php } ?>

</tbody>


</table>
<div class="footer-section">
    <div class="tgl_cetak">
        <p>Bandar Lampung, <?= $tgl_cetak_format ?></p>
    </div>
</div>
<?php if (!empty($tanda_tangan_1)) : ?>
    <div class="signature">
        <p><?= htmlspecialchars($tanda_tangan_1['jabatan1']) ?></p>
        <p><?= htmlspecialchars($tanda_tangan_1['jabatan2']) ?>,</p>
        <p class="name"><?= htmlspecialchars($tanda_tangan_1['nama']) ?></p>
        <p>NIP. <?= htmlspecialchars($tanda_tangan_1['nip']) ?></p>
    </div>
<?php endif; ?>
<?php if (!empty($tanda_tangan_2)) : ?>
    <div class="signature2">
        <p><?= htmlspecialchars($tanda_tangan_2['jabatan1']) ?></p>
        <p><?= htmlspecialchars($tanda_tangan_2['jabatan2']) ?>,</p>
        <p class="name"><?= htmlspecialchars($tanda_tangan_2['nama']) ?></p>
        <p>NIP. <?= htmlspecialchars($tanda_tangan_2['nip']) ?></p>
    </div>
<?php endif; ?>

</body>
</html>