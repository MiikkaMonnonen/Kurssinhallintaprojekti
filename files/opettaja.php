<?php
include 'yhteys.php';
include 'navbar.php';

$opettaja_id = $_GET['opettaja_id'];

$sql = "SELECT opettajat.etunimi, opettajat.sukunimi, opettajat.aine,
        kurssit.nimi AS kurssi_nimi, kurssit.alkupaiva, kurssit.loppupaiva,
        tilat.nimi AS tila_nimi
        FROM opettajat
        LEFT JOIN kurssit ON opettajat.tunnusnumero = kurssit.opettaja_id
        LEFT JOIN tilat ON kurssit.tila_id = tilat.tunnus
        WHERE opettajat.tunnusnumero = :opettaja_id";

$kysely = $yhteys->prepare($sql);
$kysely->execute(['opettaja_id' => $opettaja_id]);
$opettaja = $kysely->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Opettajanäkymä</title>
</head>
<body>
    <h2>Opettaja: <?= $opettaja[0]['etunimi'] . " " . $opettaja[0]['sukunimi']; ?></h2>
    <p>Aine: <?= $opettaja[0]['aine']; ?></p>
    <h3>Kurssit:</h3>
    <ul>
        <?php foreach ($opettaja as $kurssi): ?>
        <li><?= $kurssi['kurssi_nimi'] . " (" . $kurssi['alkupaiva'] . " - " . $kurssi['loppupaiva'] . "), Tila: " . $kurssi['tila_nimi']; ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
