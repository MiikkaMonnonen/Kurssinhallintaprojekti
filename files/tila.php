<?php
include 'yhteys.php';
include 'navbar.php';

$tila_id = $_GET['tila_id'];

$sql = "SELECT tilat.nimi, tilat.kapasiteetti,
        kurssit.nimi AS kurssi_nimi, kurssit.alkupaiva, kurssit.loppupaiva,
        opettajat.etunimi AS opettaja_etunimi, opettajat.sukunimi AS opettaja_sukunimi,
        COUNT(kurssikirjautumiset.opiskelija_id) AS osallistujat
        FROM tilat
        LEFT JOIN kurssit ON tilat.tunnus = kurssit.tila_id
        LEFT JOIN opettajat ON kurssit.opettaja_id = opettajat.tunnusnumero
        LEFT JOIN kurssikirjautumiset ON kurssit.tunnus = kurssikirjautumiset.kurssi_id
        WHERE tilat.tunnus = :tila_id
        GROUP BY kurssit.tunnus";

$kysely = $yhteys->prepare($sql);
$kysely->execute(['tila_id' => $tila_id]);
$tilat = $kysely->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Tilanäkymä</title>
</head>
<body>
    <h2>Tila: <?= $tilat[0]['nimi']; ?></h2>
    <p>Kapasiteetti: <?= $tilat[0]['kapasiteetti']; ?></p>
    <h3>Kurssit:</h3>
    <table border="1">
        <tr>
            <th>Kurssi</th>
            <th>Opettaja</th>
            <th>Alkupäivä</th>
            <th>Loppupäivä</th>
            <th>Osallistujat</th>
        </tr>
        <?php foreach ($tilat as $tila): ?>
        <tr>
            <td><?= $tila['kurssi_nimi']; ?></td>
            <td><?= $tila['opettaja_etunimi'] . " " . $tila['opettaja_sukunimi']; ?></td>
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
