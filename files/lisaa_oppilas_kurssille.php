<?php
session_start();
include 'yhteys.php';
include 'navbar.php';

// Hae kaikki oppilaat (korjaa sarakenimi vastaamaan taulun rakennetta)
$opiskelijat_sql = "SELECT opiskelijanumero AS id, CONCAT(etunimi, ' ', sukunimi) AS nimi FROM opiskelijat";
$opiskelijat_kysely = $yhteys->query($opiskelijat_sql);
$opiskelijat = $opiskelijat_kysely->fetchAll(PDO::FETCH_ASSOC);

// Hae kaikki kurssit
$kurssit_sql = "SELECT tunnus, nimi FROM kurssit";
$kurssit_kysely = $yhteys->query($kurssit_sql);
$kurssit = $kurssit_kysely->fetchAll(PDO::FETCH_ASSOC);

// Käsittele lomakkeen tiedot
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $opiskelija_id = $_POST['opiskelija_id'];
    $kurssi_id = $_POST['kurssi_id'];
    $kirjautumisaika = date('Y-m-d H:i:s'); // Lisää nykyinen päivä ja aika

    // Lisää oppilas kurssille
    $lisays_sql = "INSERT INTO kurssikirjautumiset (opiskelija_id, kurssi_id, kirjautumisaika)
                   VALUES (:opiskelija_id, :kurssi_id, :kirjautumisaika)";
    $lisays_kysely = $yhteys->prepare($lisays_sql);
    $lisays_kysely->execute([
        ':opiskelija_id' => $opiskelija_id,
        ':kurssi_id' => $kurssi_id,
        ':kirjautumisaika' => $kirjautumisaika
    ]);

    $viesti = "Oppilas lisättiin kurssille onnistuneesti!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Lisää oppilas kurssille</title>
</head>
<body>
<h2>Lisää oppilas kurssille</h2>

<?php if (isset($viesti)): ?>
    <p style="color: green;"><?php echo $viesti; ?></p>
<?php endif; ?>

<form method="POST" action="lisaa_oppilas_kurssille.php">
    <label for="opiskelija_id">Valitse oppilas:</label>
    <select name="opiskelija_id" id="opiskelija_id" required>
        <?php foreach ($opiskelijat as $opiskelija): ?>
            <option value="<?php echo $opiskelija['id']; ?>">
                <?php echo $opiskelija['nimi']; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <label for="kurssi_id">Valitse kurssi:</label>
    <select name="kurssi_id" id="kurssi_id" required>
        <?php foreach ($kurssit as $kurssi): ?>
            <option value="<?php echo $kurssi['tunnus']; ?>">
                <?php echo $kurssi['nimi']; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <button type="submit">Lisää oppilas kurssille</button>
</form>
</body>
</html>
