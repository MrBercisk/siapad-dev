<?php
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
<style>
    .headers {
        text-align: center;
    }


    td {
        text-align: center;
       
    }
  
    th {
        background-color: #DDDDDD;
        font-weight: bold;
        vertical-align: middle;
    }


</style>
<table class="headers" width="900"  cellpadding="2" border="0">

    <tr>
        <td width="900" align="center">
            <table border="0">
                <tr>
                    <td width="10"></td>
                    <td width="850"><strong>
                             <font size="+2" >BERITA ACARA REKONSILIASI
                                <br>
                                <u>LAPORAN REALISASI ANGGARAN PENDAPATAN DAERAH</u>   
                                <br>
                                NOMOR : <?= htmlspecialchars($nomor) ?>
                            </font>
                        </strong>

                    </td>
                    <td width="10"></td>

                </tr>
                <tr>
                    <td width="10"></td>
                    <td width="850" align="left"><i>
                            <font size="+1">Pada hari ini, <?= hari_ini($tglcetak) ?>, tanggal <?= tanggal_hari_ini($tglcetak) ?> , bulan <?= bulan_tanggal($tglcetak) ?> ,
                                <?= terbilang($format_tahun) ?>, telah dilakukan Rekonsiliasi
                                Anggaran Pendapatan Daerah s.d. Bulan <?= $format_bulan ?> Tahun <?= terbilang($format_tahun) ?> antara Badan Pendapatan Daerah
                                (BAPENDA) Kota Bandar Lampung dan Badan Keuangan dan Asset Daerah (BKAD) Kota Bandar Lampung, dan diperoleh
                                Realisasi Anggaran Pendapatan Daerah s.d. Bulan <?= $format_bulan ?> Tahun <?= $format_tahun ?> sebagai berikut :
                            </font>
                        </i>

                    </td>
                    <td width="10"></td>

                </tr>
            </table>
        </td>

    </tr>
    <br>
    <tr>
        <td>
        <table border="1" align="center">
    <thead>
        <tr>
            <th>No</th>
            <th>Uraian</th>
            <th>Bidang Pembukuan dan Pelaporan(BAPENDA) (Rp)</th>
            <th>Bidang Akuntansi dan Pelaporan(BKAD)(Rp)</th>
            <th>Selisih(Rp)</th>
            <th>Penjelasan Selisih</th>
        </tr>
    </thead>
    <tbody>
        <?php
        function romawi($angka) {
            $angka = intval($angka);
            $hasil = '';
            $romawi = array(
                1000 => 'M',
                900 => 'CM', 
                500 => 'D', 
                400 => 'CD',
                100 => 'C', 
                90 => 'XC', 
                50 => 'L', 
                40 => 'XL',
                10 => 'X', 
                9 => 'IX', 
                5 => 'V', 
                4 => 'IV',
                1 => 'I'
            );
            foreach ($romawi as $nilai => $huruf) {
                $jumlah = intval($angka / $nilai);
                $angka %= $nilai;
                $hasil .= str_repeat($huruf, $jumlah);
            }
            return $hasil;
        }

        $current_group = ''; 
        $no = 1;
        $jum_dispenda = 0;
        $jum_bpkad = 0;
        $jum_selisih = 0;

        foreach ($tablenya as $row) : 
            $selisih = $row['dispenda'] - $row['bpkad'];
            $group_name = ($row['keterangan'] == 'Terlampir') ? 'Sebelum Rekonsiliasi' : 
                        (($row['keterangan'] == '-') ? 'Setelah Rekonsiliasi' : '');

            if ($current_group !== $group_name) {
                if ($current_group !== '') {
                    echo '<tr>
                        <td></td>
                        <td><strong>Jumlah Pendapatan + Pembiayaan</strong></td>
                        <td>' . number_format($jum_dispenda, 2) . '</td>
                        <td>' . number_format($jum_bpkad, 2) . '</td>
                        <td>' . number_format($jum_selisih, 2) . '</td>
                        <td></td>
                    </tr>';
                    echo '</tbody>';
                }
                if ($group_name !== '') {
                    echo '<tr>
                        <td></td>
                        <td><strong>' . htmlspecialchars($group_name) . ':</strong></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>';
                }
                $current_group = $group_name;
                echo '<tbody>';
                $jum_dispenda = 0;
                $jum_bpkad = 0;
                $jum_selisih = 0;
                $no = 1; 
            }
            
            $jum_dispenda += $row['dispenda'];
            $jum_bpkad += $row['bpkad'];
            $jum_selisih += $selisih;
        ?>
        <tr>
            <td><?= romawi($no++) ?></td>
            <td><?= htmlspecialchars($row['uraian']) ?></td>
            <td><?= number_format($row['dispenda'], 2); ?></td>
            <td><?= number_format($row['bpkad'], 2); ?></td>
            <td><?= number_format($selisih, 2); ?></td>
            <td><?= htmlspecialchars($row['keterangan']) ?></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td></td>
            <td><strong>Jumlah Pendapatan + Pembiayaan</strong></td>
            <td><?= number_format($jum_dispenda, 2); ?></td>
            <td><?= number_format($jum_bpkad, 2); ?></td>
            <td><?= number_format($jum_selisih, 2); ?></td>
            <td></td>
        </tr>
        <?php if ($current_group !== '') { echo '</tbody>'; } ?>
    </tbody>
</table>


        </td>
    </tr>
    <br>

 </table>
 <table width="900" border="0" cellpadding="2">
 <h3>Terbilang: #<?= htmlspecialchars($row['tottrblg']) ?><?= htmlspecialchars($row['terbilangkoma']) ?></h3>
</table>
 <table width="900" border="0" cellpadding="2">
    <tr>
        <td width="900">
            <table border="0" align="center">
                <tr>
                    <td></td>
                    <td></td>
                    <td>Bandar Lampung, <?= $tgl_cetak_format; ?><br></td>
                </tr>
                <tr>
                    <td class="signature">
                    <?php if (!empty($tanda_tangan_1)) : ?>
                    <?= htmlspecialchars($tanda_tangan_1['jabatan1']) ?>,<br><br><br><br>
                    <?= htmlspecialchars($tanda_tangan_1['jabatan2']) ?>,<br><br><br><br>
                    <?= htmlspecialchars($tanda_tangan_1['nama']) ?><br>
                        NIP. <?= htmlspecialchars($tanda_tangan_1['nip']) ?><br>
                        <?php endif; ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td  class="signature2">
                    <?php if (!empty($tanda_tangan_2)) : ?>
                    <?= htmlspecialchars($tanda_tangan_2['jabatan1']) ?>,<br><br><br><br>
                    <?= htmlspecialchars($tanda_tangan_2['jabatan2']) ?>,<br><br><br><br>
                    <?= htmlspecialchars($tanda_tangan_2['nama']) ?><br>
                        NIP. <?= htmlspecialchars($tanda_tangan_2['nip']) ?><br>
                        <?php endif; ?>
                    </td>
                </tr>
                
            </table>
        </td>
    </tr>
</table>
