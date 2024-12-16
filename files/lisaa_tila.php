<?php
include 'yhteys.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nimi = $_POST['nimi'];
    $kapasiteetti = $_POST['kapasiteetti'];

    try {
        $sql = "INSERT INTO tilat (nimi, kapasiteetti) VALUES (:nimi, :kapasiteetti)";
        $kysely = $yhteys->prepare($sql);
        $kysely->execute(['nimi' => $nimi, 'kapasiteetti' => $kapasiteetti]);
        echo "Tila lisätty!";
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
    <title>Lisää tila</title>
</head>
<body>
    <h2>Lisää uusi tila</h2>
    <form method="POST">
        Tilannimi: <input type="text" name="nimi" required><br>
        Kapasiteetti: <input type="number" name="kapasiteetti" min="1" required><br>
        <input type="submit" value="Tallenna">
    </form>
</body>
</html>
