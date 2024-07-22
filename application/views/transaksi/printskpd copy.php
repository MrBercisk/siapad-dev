<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data SKPD</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Data SKPD</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID SKPD</th>
                    <th>ID WP</th>
                    <th>Tanggal</th>
                    <th>Teks</th>
                    <th>Bulan Pajak</th>
                    <th>Tahun Pajak</th>
                    <th>Jumlah</th>
                    <th>Bunga</th>
                    <th>Total</th>
                    <th>Is Bayar</th>
                    <th>Tanggal Bayar</th>
                    <th>Keterangan</th>
                    <th>Nama Wajib Pajak</th>
                    <th>Nomor SKPD</th>
                    <th>Tanggal Jatuh Tempo</th>
                    <th>Alamat</th>
                    <th>Tanggal SKP</th>
                </tr>
            </thead>
            <tbody>
            
                <?php if (!empty($tablenya)): 
                    $no = 1;
                    ?>
                    <?php foreach ($tablenya as $data): ?>
                        <tr>
                            <td><?= $no++?></td>
                            <td><?= $data->id_skpd; ?></td>
                            <td><?= $data->idwp; ?></td>
                            <td><?= $data->tglskp; ?></td>
                            <td><?= $data->teks; ?></td>
                            <td><?= $data->blnpajak; ?></td>
                            <td><?= $data->thnpajak; ?></td>
                            <td><?= number_format($data->jumlah, 2, ',', '.'); ?></td>
                            <td><?= number_format($data->bunga, 2, ',', '.'); ?></td>
                            <td><?= number_format($data->total, 2, ',', '.'); ?></td>
                            <td><?= $data->isbayar ? 'Ya' : 'Tidak'; ?></td>
                            <td><?= $data->tglbayar; ?></td>
                            <td><?= $data->keterangan; ?></td>
                            <td><?= $data->wajibpajak; ?></td>
                            <td><?= $data->noskpd; ?></td>
                            <td><?= $data->tgljthtmp; ?></td>
                            <td><?= $data->alamat; ?></td>
                            <td><?= $data->tglskp; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="17" class="text-center">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
