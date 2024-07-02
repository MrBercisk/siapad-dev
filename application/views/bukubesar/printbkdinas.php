<!DOCTYPE html>
<html>
<head>
    <title>Laporan Buku Besar</title>
</head>
<body>
    <h1>Laporan Buku Besar</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>No Bukti</th>
                <th>Nama Dinas</th>
                <th>Jumlah</th>
                <th>Pembagi</th>
                <th>APBD</th>
                <th>APBDP</th>
                <th>Is Saldo Awal</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($buku_besar)): ?>
                <?php foreach ($buku_besar as $row): ?>
                    <tr>
                        <td><?php echo $row['tanggal']; ?></td>
                        <td><?php echo $row['nobukti']; ?></td>
                        <td><?php echo $row['nmdinas']; ?></td>
                        <td><?php echo $row['jumlah']; ?></td>
                        <td><?php echo $row['pembagi']; ?></td>
                        <td><?php echo $row['apbd']; ?></td>
                        <td><?php echo $row['apbdp']; ?></td>
                        <td><?php echo $row['issaldoawal']; ?></td>
                        <td><?php echo $row['keterangan']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">Tidak ada data</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
