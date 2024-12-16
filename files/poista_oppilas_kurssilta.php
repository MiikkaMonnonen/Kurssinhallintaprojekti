<?php
session_start();
include 'yhteys.php';
include 'navbar.php';

if ($_SESSION['view'] !== 'opettaja') {
    echo "<p>Sinulla ei ole oikeuksia tähän toimintaan.</p>";
    exit();
}

// Haetaan opiskelijat ja kurssit
$sql_opiskelijat = "SELECT opiskelijanumero, etunimi, sukunimi FROM opiskelijat";
$kysely_opiskelijat = $yhteys->query($sql_opiskelijat);
$opiskelijat = $kysely_opiskelijat->fetchAll(PDO::FETCH_ASSOC);

$sql_kurssit = "SELECT tunnus, nimi FROM kurssit";
$kysely_kurssit = $yhteys->query($sql_kurssit);
$kurssit = $kysely_kurssit->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $opiskelija_id = $_POST['opiskelija_id'];
    $kurssi_id = $_POST['kurssi_id'];

    // Poistetaan opiskelija kurssilta
    $sql_poista = "DELETE FROM kurssikirjautumiset WHERE opiskelija_id = :opiskelija_id AND kurssi_id = :kurssi_id";
    $kysely_poista = $yhteys->prepare($sql_poista);
    $kysely_poista->execute(['opiskelija_id' => $opiskelija_id, 'kurssi_id' => $kurssi_id]);

    echo "<p>Opiskelija poistettu kurssilta onnistuneesti!</p>";
}
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Poista oppilas kurssilta</title>
</head>
<body>
    <h2>Poista oppilas kurssilta</h2>
    <form method="POST">
        <label for="opiskelija_id">Valitse opiskelija:</label>
        <select name="opiskelija_id" required>
            <option value="">Valitse opiskelija</option>
            <?php foreach ($opiskelijat as $opiskelija): ?>
                <option value="<?= $opiskelija['opiskelijanumero']; ?>"><?= $opiskelija['etunimi'] . ' ' . $opiskelija['sukunimi']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="kurssi_id">Valitse kurssi:</label>
        <select name="kurssi_id" required>
            <option value="">Valitse kurssi</option>
            <?php foreach ($kurssit as $kurssi): ?>
                <option value="<?= $kurssi['tunnus']; ?>"><?= $kurssi['nimi']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <input type="submit" value="Poista oppilas kurssilta">
    </form>
</body>
</html>
