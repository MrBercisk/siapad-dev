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


    return  $bulan[(int)$tanggal[1]];
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
        vertical-align: middle;
    }



    th {
        background-color: #DDDDDD;
        font-weight: bold;
        vertical-align: middle;
    }


</style>
<table width="900" border="" cellpadding="2">

    <tr>
        <td width="900" align="center">
            <table border="0">
                <tr>
                    <td width="10"></td>
                    <td width="850"><strong>
                             <font size="+2" >BERITA ACARA REKONSILIASI BIDANG PEMBUKUAN DAN PELAPORAN DENGAN BIDANG PAJAK
                                <br>
                                PEMBAYARAN SURAT PEMBERITAHUAN PAJAK DAERAH (SPTPD) 
                                <?php if (!empty($kdrekening)) :?>
                                    <?= strtoupper($kdrekening['nmrekening']); ?>
                                <?php endif; ?>
                                <br>
                                BULAN : <?= htmlspecialchars($format_bulan) ?> <?= htmlspecialchars($format_tahun) ?>
                            </font>
                        </strong>

                    </td>
                    <td width="10"></td>

                </tr>
                <tr>
                    <td width="10"></td>
                    <td width="850" align="left"><i>
                            <font size="+1">Pada hari ini, <?= hari_ini($tglcetak) ?>, tanggal <?= terbilang($tglcetak) ?> , bulan <?= $format_bulan ?> ,
                                <?= terbilang($format_tahun) ?>, telah dilakukan Rekonsiliasi Data Pembayaran Surat Pemberitahuan Pajak Daerah (SPTPD) <?php if (!empty($kdrekening)) :?>
                                    <?= strtoupper($kdrekening['nmrekening']); ?>
                                <?php endif; ?>  yang Diterbitkan pada Bidang Pajak dengan Surat Setoran Pajak Daerah/Surat Tanda Setoran (SSPD/STS)
                                <?php if (!empty($kdrekening)) :?>
                                    <?= strtoupper($kdrekening['nmrekening']); ?>
                                <?php endif; ?> Yang Diterima pada Bidang Bidang Pembukuan dan Pelaporan (Buklap) Badan Pengelola Pajak dan Retribusi Daerah (BPPRD) Kota Bandar Lampung dari
                                Bulan <?= $format_bulan ?> Tahun <?= terbilang($format_tahun) ?> dan diperoleh data SPTPD dan SSPD/STS (Rincian Terlampir), sebagai berikut :
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
                <tr>
                    <th rowspan="3">NO</th>
                    <th rowspan="3" colspan="5">Uraian</th>
                    <th colspan="5">Data SPTPD/SSPD/STS YANG DIBAYAR</th>
                    <th rowspan="3">Keterangan</th>

                </tr>
                <tr>
                    <th rowspan="2">JUMLAH NOMOR TERBIT</th>
                    <th colspan="5">Nominal (Rp)</th>
                </tr>
                <tr>

                    <th>Pokok</th>
                    <th>Denda</th>
                    <th>DISCOUNT (POTONGAN PAJAK)</th>
                    <th>Jumlah</th>
                </tr>

                <tr>
                    <td>1</td>
                    <td colspan="5">2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                    <td>8</td>
           
                </tr>
           
                <tr>
                    <td>I</td>
                    <td colspan="5">SPTPD Yang Terbit (Data Bidang Pajak)</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="5"> Selisih Denda</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="5">Jumlah</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td>II</td>
                    <td colspan="5">SSPD/STS yang Diterima (Data Bidang Buklap)</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="5">Selisih</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="5">Penjelasan Selisih: </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>NO. URUT</th>
                    <th>NAMA WAJIB PAJAK (WP)</th>
                    <th>UPTD</th>
                    <th colspan="2">MASA PAJAK</th>
                    <th>TANGGAL TERBIT</th>
                    <th>NO. SPTPD/SSPD/STS </th>
                    <th>Pokok</th>
                    <th>Denda</th>
                    <th>DISCOUNT (POTONGAN PAJAK)</th>
                    <th>Jumlah</th>
                    <th>KETERANGAN</th>
                </tr>
                <tr>
                    <td>II</td>
                    <td colspan="11" align="left">SPTPD BELUM DIBAYAR:</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td colspan="11" align="left">PEMBAYARAN BULAN MARET 2023:</td>
                </tr>
                <?php $no = 1;
                foreach ($result as $val) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php endforeach ?>

            </table>
        </td>



    </tr>
    <br>

</table>
$pecah = explode('-', $params['tglcetak']);
    $bulanini = $pecah[1];
    $tanggalini = $pecah[2];
    $tahunini = $pecah[0];
    ?>
  <!-- ttd -->
 