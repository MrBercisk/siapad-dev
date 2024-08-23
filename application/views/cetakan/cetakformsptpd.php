<!DOCTYPE html>
<html lang="en">
<?php
function base64_encode_image($filename)
{
    $img_data = file_get_contents($filename);
    $img_base64 = base64_encode($img_data);
    return 'data:image/' . pathinfo($filename, PATHINFO_EXTENSION) . ';base64,' . $img_base64;
}

$logo_base64 = base64_encode_image(base_url('assets/img/logo.png'));
?>

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
            left: 50px;
            top: 2px;
        }

        .header h2,
        .header h3,
        .header h4 {
            margin: 0;
            margin-bottom: 5px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            table-layout: fixed;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th {
            padding: 10px;
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
        }

        tbody td:first-child,
        tbody td:nth-child(2) {
            text-align: left;
        }

        .tgl_cetak p {
            text-align: right;
            margin: 50px 50px -45px 0;
        }

        .signature-container {
            display: flex;
            justify-content: space-between;
            margin: 50px 10px;
        }

        .signature {
            /* font-weight: bold;
            text-align: center;
            margin-top: 60px;
            margin-right: 30px;
            position: relative;
            float: right;
            clear: both; */
            position: absolute;
            text-align: center;
            width: 300px;
        }

        .signature.left {
            left: 0;
        }

        .signature.right {
            right: 0;
        }

        .signature .jabatan1 {
            margin-top: -12px;
        }

        .signature .name {
            text-decoration: underline;
            font-weight: bold;
            margin-top: 70px;
        }

        .signature .title {
            margin-top: 5px;
            font-style: italic;
        }

        .signature .nip {
            margin-top: 5px;
        }


        th:nth-child(1) {
            width: 5%;
        }

        th:nth-child(2) {
            width: 20%;
        }

        th:nth-child(3) {
            width: 15%;
        }

        th:nth-child(4) {
            width: 20%;
        }

        th:nth-child(5) {
            width: 15%;
        }

        th:nth-child(6) {
            width: 20%;
        }
    </style>
</head>

<body>
    <?php
    setlocale(LC_ALL, 'id-ID', 'id_ID');
    $tanggal_saat_ini = strftime('%d %B %Y');
    $tanggal_sebelumnya = strftime('%d %B %Y', strtotime('-1 day'));
    ?>
    <div class="header">
        <img src="<?= $logo_base64; ?>" alt="Logo">
        <h2>PEMERINTAH KOTA BANDAR LAMPUNG</h2>
        <h3>DINAS PENDAPATAN DAERAH</h3>
        <h3>DAFTAR SPTPD / STPD <?= strtoupper($pajak) ?></h3>
        <h3>MASA PAJAK <?= strtoupper($bulan . ' ' . $tahun) ?></h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>TANGGAL SPTPD</th>
                <th>NOMOR SPTPD/STPD</th>
                <th>NAMA WAJIB PAJAK</th>
                <th>JUMLAH (Rp)</th>
                <th>KETERANGAN</th>
            </tr>

            <tr>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>
                <th>6</th>

            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            $no = 1;
            if (!empty($tablenya)) :
                foreach ($tablenya as $tbl) :
                    $total += $tbl['jumlah'];
            ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= htmlspecialchars($tbl['tanggal']) ?></strong></td>
                        <td><?= htmlspecialchars($tbl['nomor']) ?></td>
                        <td><?= htmlspecialchars($tbl['nmwp']) ?></td>
                        <td><?= number_format($tbl['jumlah'], 2) ?></td>
                        <td><?= htmlspecialchars($tbl['keterangan']) ?></td>

                    </tr>
                <?php endforeach; ?> <tr>
                    <td colspan="2"><strong>Total </strong></td>
                    <td></td>
                    <td></td>
                    <td><?= number_format($total, 2) ?></td>
                    <td></td>


                </tr>
            <?php endif; ?>
        </tbody>


    </table>
    <div class="tgl_cetak">
        <p>Bandar Lampung, <?= strftime('%d %B %Y') ?></p>
    </div>
    <div class="signature-container">
        <div class="signature left">
            <p>Mengetahui,</p>
            <p class="title"> KEPALA BIDANG PENDAPATAN</p>
            <p class="name"><?= $ttd[0]->nama ?></p>
            <p class="nip"><?= $ttd[0]->nip ?></p>
        </div>
        <div class="signature right">
            <p>&nbsp;</p>
            <p class="title">PEMBUAT DAFTAR,</p>
            <p class="name"> <?= $pembuat ?></p>
            <p class="nip"> <?= $nip ?></p>
        </div>
    </div>
</body>

</html>