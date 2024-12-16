<?php
include 'yhteys.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etunimi = $_POST['etunimi'];
    $sukunimi = $_POST['sukunimi'];
    $aine = $_POST['aine'];

    try {
        $sql = "INSERT INTO opettajat (etunimi, sukunimi, aine) VALUES (:etunimi, :sukunimi, :aine)";
        $kysely = $yhteys->prepare($sql);
        $kysely->execute(['etunimi' => $etunimi, 'sukunimi' => $sukunimi, 'aine' => $aine]);
        echo "Opettaja lisätty!";
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
    <title>Lisää opettaja</title>
</head>
<body>
    <h2>Lisää uusi opettaja</h2>
    <form method="POST">
        Etunimi: <input type="text" name="etunimi" required><br>
        Sukunimi: <input type="text" name="sukunimi" required><br>
        Aine: <input type="text" name="aine" required><br>
        <input type="submit" value="Tallenna">
    </form>
</body>
</html>
