<?php
//3.1
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
    // $pecahkan = explode('-', $tanggal);
    // var_dump($pecahkan);
    // die;
    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun

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
    //$hari = date ("D");
    $hari1 = explode('-', $hari);
    $hari2 = $hari1[2];
    $hari = date("D", strtotime($hari));
    // var_dump( $hari);die;
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

    /* td[colspan="2"] {
        text-align: center;
    } */
</style>
<table width="900" border="0" cellpadding="2">

    <tr>
        <td width="900" align="center">
            <table border="0">
                <tr>
                    <td width="10"></td>
                    <td width="900"><strong>
                            <font size="+2">BERITA ACARA REKONSILIASI BIDANG PEMBUKUAN DAN PELAPORAN DENGAN BIDANG PAJAK
                                <br>
                                PEMBAYARAN SURAT PEMBERITAHUAN PAJAK DAERAH (SPTPD) YANG TIDAK KEMBALI <?= $nmrekening; ?> 
                                <br>
                                MASA PAJAK BULAN : <?= $format_bulan ?>  <?= $format_tahun ?>

                            </font>
                        </strong>

                    </td>
                    <td width="10"></td>

                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td width="10"></td>
                    <td width="850" align="left"><i>
                            <font size="+1">Pada hari ini, <?= hari_ini($tglcetak) ?>, tanggal <?= tanggal_hari_ini($tglcetak) ?>, bulan <?= bulan_tanggal($tglcetak) ?>,
                            <?= terbilang($format_tahun) ?>, telah dilakukan Rekonsiliasi Data Surat Pemberitahuan Pajak Daerah (SPTPD) yang tidak kembali
                            <?= $nmrekening; ?> antara Bidang Pajak dan Bidang Pembukuan dan Pelaporan Badan Pengelola Pajak dan Retribusi Daerah Kota Bandar Lampung dari
                                Bulan <?= $format_bulan ?> Tahun <?= terbilang($format_tahun) ?> dan diperoleh data SPTPD yg tidak kembali (Rincian Terlampir), sebagai berikut :
                            </font>
                        </i>

                    </td>
                    <td width="10"></td>
                </tr>
            </table>
        </td>

    </tr>
    <tr>
        <td></td>
    </tr>
    <br>
    <tr>
        <td>
            <table border="2" align="center">

                <tr>
                    <th>No. Urut</th>
                    <th>Nama Wajib Pajak (WP)</th>
                    <th>UPTD</th>
                    <th colspan="2">Masa Pajak</th>
                    <th>Tanggal Terbit</th>
                    <th>No. SPTPD/SSPD/STS </th>
                    <th>NPWP</th>
                    <th>Keterangan</th>
                </tr>
                <tr>
                    <td>II</td>
                    <td colspan="9" style="text-align: left;">SPTPD BELUM DIBAYAR:</td>
                </tr>
                <?php
                
                $no = 1;
                foreach ($tablenya as $row) :
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td></td>
                        <td><?= htmlspecialchars($row['masabulan']) ?></td>
                        <td><?= htmlspecialchars($row['thnpajak']) ?></td>
                        <td><?= htmlspecialchars($row['tgl_input']) ?></td>
                        <td><?= htmlspecialchars($row['sspd']) ?></td>
                        <td><?= htmlspecialchars($row['npwpd']) ?></td>
                        <td>SPTPD TIDAK KEMBALI</td>
                    </tr>
                <?php endforeach ?>
            </table>
        </td>



    </tr>
    <br>

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
                    <td>
                        KEPALA BIDANG PEMBUKUAN DAN PELAPORAN,<br><br><br><br>
                        <?= isset($nama_1) ? $nama_1 : '' ?><br>
                        NIP. <?= isset($nip_1) ? $nip_1 : '' ?><br>
                    </td>
                    <td></td>
                    <td>
                        KASUBBID PEMBUKUAN,<br><br><br><br>
                        <?= isset($nama_2) ? $nama_2 : '' ?><br>
                        NIP. <?= isset($nip_2) ? $nip_2 : '' ?><br>
                    </td>
                </tr>
                <tr>
                    <td>
                        KEPALA BIDANG PAJAK,<br><br><br><br>
                        <?= isset($nama_3) ? $nama_3 : '' ?><br>
                        NIP. <?= isset($nip_3) ? $nip_3 : '' ?><br>
                    </td>
                    <td></td>
                    <td>
                        KASUBBID PAJAK HOTEL, HIBURAN dan PAJAK LAINNYA<br><br><br>
                        <?= isset($nama_4) ? $nama_4 : '' ?><br>
                        NIP. <?= isset($nip_4) ? $nip_4 : '' ?><br>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Mengetahui,</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        KEPALA BPPRD,<br>KOTA BANDAR LAMPUNG,<br><br><br><br>
                        <?= isset($nama_5) ? $nama_5 : '' ?><br>
                        NIP. <?= isset($nip_5) ? $nip_5 : '' ?><br>
                    </td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
</table>