<?php
include 'yhteys.php';
include 'navbar.php';

$sql = "SELECT tilat.nimi, tilat.kapasiteetti,
        kurssit.nimi AS kurssi_nimi, kurssit.alkupaiva, kurssit.loppupaiva,
        COUNT(kurssikirjautumiset.opiskelija_id) AS osallistujat
        FROM tilat
        LEFT JOIN kurssit ON tilat.tunnus = kurssit.tila_id
        LEFT JOIN kurssikirjautumiset ON kurssit.tunnus = kurssikirjautumiset.kurssi_id
        GROUP BY kurssit.tunnus";

$kysely = $yhteys->query($sql);
$tilat = $kysely->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Tilat</title>
</head>
<body>
    <h2>Tilat</h2>
    <table border="1">
        <tr>
            <th>Nimi</th>
            <th>Kapasiteetti</th>
            <th>Kurssi</th>
            <th>Alkupäivä</th>
            <th>Loppupäivä</th>
            <th>Osallistujat</th>
        </tr>
        <?php foreach ($tilat as $tila): ?>
        <tr>
            <td><?= $tila['nimi']; ?></td>
            <td><?= $tila['kapasiteetti']; ?></td>
            <td><?= $tila['kurssi_nimi']; ?></td>
            <td><?= $tila['alkupaiva']; ?></td>
            <td><?= $tila['loppupaiva']; ?></td>
            <td>
                <?= $tila['osallistujat']; ?>
                <?php if ($tila['osallistujat'] > $tila['kapasiteetti']): ?>
                <span style="color: red;">(Ylitetty)</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
