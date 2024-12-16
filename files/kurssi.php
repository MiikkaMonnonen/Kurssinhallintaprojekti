<?php
session_start();
include 'yhteys.php';
include 'navbar.php';

// Tarkistetaan, että käyttäjä on opettajanäkymässä
if ($_SESSION['view'] !== 'opettaja') {
    echo '<p>Virhe: Tämä sivu on tarkoitettu vain opettajanäkymään.</p>';
    exit();
}

// Tarkistetaan, että `kurssi_id` on annettu
if (!isset($_GET['kurssi_id']) || empty($_GET['kurssi_id'])) {
    echo '<p>Virhe: Kurssin ID:tä ei annettu.</p>';
    exit();
}

$kurssi_id = $_GET['kurssi_id'];

// Haetaan kurssin tiedot
$sql = "SELECT kurssit.nimi, kurssit.kuvaus, kurssit.alkupaiva, kurssit.loppupaiva,
        opettajat.etunimi AS opettaja_etunimi, opettajat.sukunimi AS opettaja_sukunimi,
        tilat.nimi AS tila_nimi
        FROM kurssit
        JOIN opettajat ON kurssit.opettaja_id = opettajat.tunnusnumero
        JOIN tilat ON kurssit.tila_id = tilat.tunnus
        WHERE kurssit.tunnus = :kurssi_id";

$kysely = $yhteys->prepare($sql);
$kysely->execute(['kurssi_id' => $kurssi_id]);
$kurssi = $kysely->fetch(PDO::FETCH_ASSOC);

// Jos kurssia ei löydy
if (!$kurssi) {
    echo '<p>Virhe: Kurssia ei löytynyt annetuilla tiedoilla.</p>';
    exit();
}

// Haetaan kurssille ilmoittautuneet opiskelijat
$sql_opiskelijat = "SELECT opiskelijat.etunimi, opiskelijat.sukunimi, opiskelijat.vuosikurssi
                    FROM kurssikirjautumiset
                    JOIN opiskelijat ON kurssikirjautumiset.opiskelija_id = opiskelijat.opiskelijanumero
                    WHERE kurssikirjautumiset.kurssi_id = :kurssi_id";

$kysely_opiskelijat = $yhteys->prepare($sql_opiskelijat);
$kysely_opiskelijat->execute(['kurssi_id' => $kurssi_id]);
$osallistujat = $kysely_opiskelijat->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Kurssin tiedot</title>
</head>
<body>
    <h2>Kurssi: <?= htmlspecialchars($kurssi['nimi']); ?></h2>
    <p><strong>Kuvaus:</strong> <?= htmlspecialchars($kurssi['kuvaus']); ?></p>
    <p><strong>Alkupäivä:</strong> <?= htmlspecialchars($kurssi['alkupaiva']); ?></p>
    <p><strong>Loppupäivä:</strong> <?= htmlspecialchars($kurssi['loppupaiva']); ?></p>
    <p><strong>Opettaja:</strong> <?= htmlspecialchars($kurssi['opettaja_etunimi']) . ' ' . htmlspecialchars($kurssi['opettaja_sukunimi']); ?></p>
    <p><strong>Tila:</strong> <?= htmlspecialchars($kurssi['tila_nimi']); ?></p>

    <h3>Osallistujat:</h3>
    <?php if ($osallistujat): ?>
        <table border="1">
            <tr>
                <th>Etunimi</th>
                <th>Sukunimi</th>
                <th>Vuosikurssi</th>
            </tr>
            <?php foreach ($osallistujat as $opiskelija): ?>
            <tr>
                <td><?= htmlspecialchars($opiskelija['etunimi']); ?></td>
                <td><?= htmlspecialchars($opiskelija['sukunimi']); ?></td>
                <td><?= htmlspecialchars($opiskelija['vuosikurssi']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Kurssille ei ole ilmoittautuneita opiskelijoita.</p>
    <?php endif; ?>
</body>
</html>
