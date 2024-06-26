<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
         if (!empty($tablenya)):
            $no = 1;
            foreach($tablenya as $tbl): ?>       
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td><?= htmlspecialchars($tbl['tanggal']) ?></td>
                    <td style="text-align: left;"><?= htmlspecialchars($tbl['nmwp']) ?></td>
                    <td><?= htmlspecialchars($tbl['uptd']) ?></td>
                    <td><?= htmlspecialchars($tbl['tgl']) ?></td>
                    <td style="text-align: right;"><?= number_format($tbl['skpd'], 2) ?></td>
                    <td style="text-align: center;"><?= htmlspecialchars($tbl['masapajak'] ) ?></td>
                    <td style="text-align: center;"><?= htmlspecialchars( $tbl['nomor']) ?></td>
                    <td style="text-align: right;"><?= number_format($tbl['pokok'], 2) ?></td>
                    <td style="text-align: right;"><?= number_format($tbl['denda'], 2) ?></td>
                    <td style="text-align: right;"><?= number_format($tbl['pokok_lalu'], 2) ?></td>
                    <td style="text-align: right;"><?= number_format($tbl['denda_lalu'], 2) ?></td>
                </tr>
            <?php endforeach; ?>        
        <?php endif; ?>
        
</body>
</html>