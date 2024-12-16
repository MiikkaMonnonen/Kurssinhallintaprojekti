<?php
include 'yhteys.php';
include 'navbar.php';

$sql = "SELECT opettajat.etunimi, opettajat.sukunimi, opettajat.aine,
        kurssit.nimi AS kurssi_nimi, kurssit.alkupaiva, kurssit.loppupaiva
        FROM opettajat
        LEFT JOIN kurssit ON opettajat.tunnusnumero = kurssit.opettaja_id";

$kysely = $yhteys->query($sql);
$opettajat = $kysely->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Opettajat</title>
</head>
<body>
    <h2>Opettajat</h2>
    <table border="1">
        <tr>
            <th>Etunimi</th>
            <th>Sukunimi</th>
            <th>Aine</th>
            <th>Kurssi</th>
            <th>Alkupäivä</th>
            <th>Loppupäivä</th>
        </tr>
        <?php foreach ($opettajat as $opettaja): ?>
        <tr>
            <td><?= $opettaja['etunimi']; ?></td>
            <td><?= $opettaja['sukunimi']; ?></td>
            <td><?= $opettaja['aine']; ?></td>
            <td><?= $opettaja['kurssi_nimi']; ?></td>
            <td><?= $opettaja['alkupaiva']; ?></td>
            <td><?= $opettaja['loppupaiva']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
