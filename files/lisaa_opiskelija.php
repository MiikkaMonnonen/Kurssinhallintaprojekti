<?php
include 'yhteys.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etunimi = $_POST['etunimi'];
    $sukunimi = $_POST['sukunimi'];
    $vuosikurssi = $_POST['vuosikurssi'];

    try {
        $sql = "INSERT INTO opiskelijat (etunimi, sukunimi, vuosikurssi) VALUES (:etunimi, :sukunimi, :vuosikurssi)";
        $kysely = $yhteys->prepare($sql);
        $kysely->execute(['etunimi' => $etunimi, 'sukunimi' => $sukunimi, 'vuosikurssi' => $vuosikurssi]);
        echo "Opiskelija lisätty!";
    } catch (PDOException $e) {
        die("VIRHE: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Lisää opiskelija</title>
</head>
<body>
    <h2>Lisää uusi opiskelija</h2>
    <form method="POST">
        Etunimi: <input type="text" name="etunimi" required><br>
        Sukunimi: <input type="text" name="sukunimi" required><br>
        Vuosikurssi: <input type="number" name="vuosikurssi" min="1" max="3" required><br>
        <input type="submit" value="Tallenna">
    </form>
</body>
</html>
