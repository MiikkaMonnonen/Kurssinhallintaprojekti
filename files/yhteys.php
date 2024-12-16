<?php
// Tietokannan yhteystiedot
$servername = "localhost";
$username = "root"; // Oletus XAMPP:ssa
$password = "";     // Oletus XAMPP:ssa
$dbname = "kurssienhallinta"; // Tietokannan nimi

try {
    // PDO-yhteyden luominen
    $yhteys = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    // Asetetaan virheiden käsittelytila
    $yhteys->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Vahvistus (valinnainen)
} catch (PDOException $e) {
    // Jos yhteys epäonnistuu
    die("Yhteyden muodostaminen epäonnistui: " . $e->getMessage());
}
?>
