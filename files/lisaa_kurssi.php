<?php
include 'yhteys.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nimi = $_POST['nimi'];
    $kuvaus = $_POST['kuvaus'];
    $alkupaiva = $_POST['alkupaiva'];
    $loppupaiva = $_POST['loppupaiva'];
    $viikonpaiva = $_POST['viikonpaiva'];
    $alkuaika = $_POST['alkuaika'];
    $loppuaika = $_POST['loppuaika'];
    $opettaja_id = $_POST['opettaja_id'];
    $tila_id = $_POST['tila_id'];

    $sql = "INSERT INTO kurssit (nimi, kuvaus, alkupaiva, loppupaiva, viikonpaiva, alkuaika, loppuaika, opettaja_id, tila_id)
            VALUES (:nimi, :kuvaus, :alkupaiva, :loppupaiva, :viikonpaiva, :alkuaika, :loppuaika, :opettaja_id, :tila_id)";
    $stmt = $yhteys->prepare($sql);
    $stmt->execute([
        ':nimi' => $nimi,
        ':kuvaus' => $kuvaus,
        ':alkupaiva' => $alkupaiva,
        ':loppupaiva' => $loppupaiva,
        ':viikonpaiva' => $viikonpaiva,
        ':alkuaika' => $alkuaika,
        ':loppuaika' => $loppuaika,
        ':opettaja_id' => $opettaja_id,
        ':tila_id' => $tila_id
    ]);

    echo "Kurssi lisätty onnistuneesti!";
}

// Hae opettajat ja tilat valintoja varten
$opettajat = $yhteys->query("SELECT tunnusnumero, CONCAT(etunimi, ' ', sukunimi) AS nimi FROM opettajat")->fetchAll(PDO::FETCH_ASSOC);
$tilat = $yhteys->query("SELECT tunnus, nimi FROM tilat")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Lisää kurssi</title>
</head>
<body>
<h2>Lisää uusi kurssi</h2>
<form method="POST" action="lisaa_kurssi.php">
    <label>Nimi:</label><br>
    <input type="text" name="nimi" required><br><br>

    <label>Kuvaus:</label><br>
    <textarea name="kuvaus" required></textarea><br><br>

    <label>Alkupäivä:</label><br>
    <input type="date" name="alkupaiva" required><br><br>

    <label>Loppupäivä:</label><br>
    <input type="date" name="loppupaiva" required><br><br>

    <label>Viikonpäivä:</label><br>
    <select name="viikonpaiva" required>
        <option value="Maanantai">Maanantai</option>
        <option value="Tiistai">Tiistai</option>
        <option value="Keskiviikko">Keskiviikko</option>
        <option value="Torstai">Torstai</option>
        <option value="Perjantai">Perjantai</option>
    </select><br><br>

    <label>Alkuaika:</label><br>
    <input type="time" name="alkuaika" required><br><br>

    <label>Loppuaika:</label><br>
    <input type="time" name="loppuaika" required><br><br>

    <label>Opettaja:</label><br>
    <select name="opettaja_id" required>
        <?php foreach ($opettajat as $opettaja): ?>
            <option value="<?php echo $opettaja['tunnusnumero']; ?>"><?php echo $opettaja['nimi']; ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Tila:</label><br>
    <select name="tila_id" required>
        <?php foreach ($tilat as $tila): ?>
            <option value="<?php echo $tila['tunnus']; ?>"><?php echo $tila['nimi']; ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Lisää kurssi</button>
</form>
</body>
</html>
