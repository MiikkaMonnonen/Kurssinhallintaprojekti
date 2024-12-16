<?php
session_start();
include 'yhteys.php';
include 'navbar.php';

// Poistetaan opiskelija vain, jos on valittu opiskelija poistettavaksi
if (isset($_POST['poista'])) {
    $opiskelija_id = $_POST['opiskelija_id'];

    // Poistetaan opiskelija tietokannasta
    $sql_poista = "DELETE FROM opiskelijat WHERE opiskelijanumero = :opiskelija_id";
    $kysely_poista = $yhteys->prepare($sql_poista);
    $kysely_poista->execute(['opiskelija_id' => $opiskelija_id]);

    // Poistetaan myös kaikki kirjautumiset kyseiseltä opiskelijalta
    $sql_poista_kirjautumiset = "DELETE FROM kurssikirjautumiset WHERE opiskelija_id = :opiskelija_id";
    $kysely_poista_kirjautumiset = $yhteys->prepare($sql_poista_kirjautumiset);
    $kysely_poista_kirjautumiset->execute(['opiskelija_id' => $opiskelija_id]);

    echo "<p>Opiskelija poistettu onnistuneesti!</p>";
}

// Hae kaikki opiskelijat ja näytä heidät valintalistassa
$sql_opiskelijat = "SELECT opiskelijanumero, etunimi, sukunimi FROM opiskelijat";
$kysely_opiskelijat = $yhteys->query($sql_opiskelijat);
$opiskelijat = $kysely_opiskelijat->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Poista opiskelija</title>
</head>
<body>
    <h2>Poista opiskelija</h2>
    
    <form method="POST" action="poista_opiskelija.php">
        <label for="opiskelija_id">Valitse poistettava opiskelija:</label>
        <select name="opiskelija_id" id="opiskelija_id" required>
            <option value="">Valitse opiskelija</option>
            <?php foreach ($opiskelijat as $opiskelija): ?>
                <option value="<?= $opiskelija['opiskelijanumero'] ?>">
                    <?= htmlspecialchars($opiskelija['etunimi']) . ' ' . htmlspecialchars($opiskelija['sukunimi']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <input type="submit" name="poista" value="Poista opiskelija">
    </form>
</body>
</html>
