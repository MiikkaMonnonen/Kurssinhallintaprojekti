<?php
include 'yhteys.php';
include 'navbar.php';

$opiskelija_id = $_GET['opiskelija_id'];

$sql = "SELECT opiskelijat.etunimi, opiskelijat.sukunimi, opiskelijat.vuosikurssi,
        kurssit.nimi AS kurssi_nimi, kurssit.alkupaiva
        FROM opiskelijat
        LEFT JOIN kurssikirjautumiset ON opiskelijat.opiskelijanumero = kurssikirjautumiset.opiskelija_id
        LEFT JOIN kurssit ON kurssikirjautumiset.kurssi_id = kurssit.tunnus
        WHERE opiskelijat.opiskelijanumero = :opiskelija_id";

$kysely = $yhteys->prepare($sql);
$kysely->execute(['opiskelija_id' => $opiskelija_id]);
$opiskelija = $kysely->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Opiskelija</title>
</head>
<body>
    <h2>Opiskelija: <?= $opiskelija[0]['etunimi'] . " " . $opiskelija[0]['sukunimi']; ?></h2>
    <p>Vuosikurssi: <?= $opiskelija[0]['vuosikurssi']; ?></p>
    <h3>Kurssit:</h3>
    <ul>
        <?php foreach ($opiskelija as $kurssi): ?>
        <li><?= $kurssi['kurssi_nimi'] . " (" . $kurssi['alkupaiva'] . ")"; ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
