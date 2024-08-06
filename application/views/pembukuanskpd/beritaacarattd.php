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
                             <font size="+2" >BERITA ACARA REKONSILIASI BIDANG PEMBUKUAN DAN PELAPORAN DENGAN BIDANG PAJAK
                                <br>
                                PEMBAYARAN SURAT PEMBERITAHUAN PAJAK DAERAH (SPTPD) 
                                <?= strtoupper($nmrekening); ?>
                               
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
                            <font size="+1">Pada hari ini, <?= hari_ini($tglcetak) ?>, tanggal <?= tanggal_hari_ini($tglcetak) ?> , bulan <?= bulan_tanggal($tglcetak) ?> ,
                                <?= terbilang($format_tahun) ?>, telah dilakukan Rekonsiliasi Data Pembayaran Surat Pemberitahuan Pajak Daerah (SPTPD)  <?= $nmrekening; ?>  yang Diterbitkan pada Bidang Pajak dengan Surat Setoran Pajak Daerah/Surat Tanda Setoran (SSPD/STS)
                                <?= $nmrekening; ?> Yang Diterima pada Bidang Bidang Pembukuan dan Pelaporan (Buklap) Badan Pengelola Pajak dan Retribusi Daerah (BPPRD) Kota Bandar Lampung dari
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
            <table  border="1" align="center">
                <tr>
                    <th rowspan="3">NO</th>
                    <th rowspan="3" colspan="5">Uraian</th>
                    <th colspan="5">Data SPTPD/SSPD/STS YANG DIBAYAR</th>
                    <th rowspan="3">Keterangan</th>

                </tr>
                <tr>
                    <th rowspan="2">JUMLAH NOMOR TERBIT</th>
                    <th colspan="4">Nominal (Rp)</th>
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
                <?php 
                foreach ($tablenya as $row) : ?>
                <tr>
                    <td>I</td>
                    <td colspan="5">SPTPD Yang Terbit (Data Bidang Pajak)</td>
                    <td><?= number_format($row['totalnomor'], 2); ?></td>
                    <td><?= number_format($row['totaljumlah'], 2); ?></td>
                    <td><?= number_format($row['totaldenda'], 2); ?></td>
                    <td></td>
                    <td><?= number_format($row['totaljumlah'], 2); ?></td>
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
                    <td><?= number_format($row['totaljumlah'], 2); ?></td>
                    <td></td>
                </tr>

                <tr>
                    <td>II</td>
                    <td colspan="5">SSPD/STS yang Diterima (Data Bidang Buklap)</td>
                    <td><?= number_format($row['totalnomorsb'], 2); ?></td>
                    <td><?= number_format($row['totaljumlahsb'], 2); ?></td>
                    <td><?= number_format($row['totaldendasb'], 2); ?></td>
                    <td></td>
                    <td><?= number_format($row['totaljumlahsb'], 2); ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="5">Selisih</td>
                    <td><?= number_format($row['totalnomorbb'], 2); ?></td>
                    <td><?= number_format($row['totaljumlahbb'], 2); ?></td>
                    <td><?= number_format($row['totaldendabb'], 2); ?></td>
                    <td></td>
                    <td><?= number_format($row['totaljumlahbb'], 2); ?></td>
                    <td></td>
                </tr>
                <?php endforeach ?>
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
                    <td colspan="11" style="text-align: left;">SPTPD BELUM DIBAYAR:</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td colspan="11" style="text-align: left;">PEMBAYARAN BULAN MARET 2023:</td>
                </tr>
                <?php $no = 1;
                foreach ($tablenya as $row) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td></td>
                        <td><?= htmlspecialchars($row['masabulan']) ?></td>
                        <td><?= htmlspecialchars($row['thnpajak']) ?></td>
                        <td><?= htmlspecialchars($row['tgl_input']) ?></td>
                        <td><?= htmlspecialchars($row['sspd']) ?></td>
                        <td><?= number_format($row['pokok'], 2) ?></td>
                        <td><?= number_format($row['denda'], 2) ?></td>
                        <td></td>
                        <td><?= number_format($row['total'], 2) ?></td>
                        <td><?= htmlspecialchars($row['keterangan']) ?></td>
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
