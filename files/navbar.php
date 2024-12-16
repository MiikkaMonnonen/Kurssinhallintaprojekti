<?php

// Asetetaan oletusnäkymä, jos sitä ei ole asetettu
if (!isset($_SESSION['view'])) {
    $_SESSION['view'] = 'opettaja'; // Oletusnäkymä on opettaja
}

// Vaihdetaan näkymä, jos käyttäjä klikkaa vaihtolinkkiä
if (isset($_GET['change_view'])) {
    $_SESSION['view'] = $_SESSION['view'] === 'opettaja' ? 'opiskelija' : 'opettaja';
}

// Asetetaan etusivulinkki näkymän perusteella
$etusivu_linkki = $_SESSION['view'] === 'opettaja' ? 'index.php' : 'student_home.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Kurssienhallinta</title>
</head>
<body>
<header>
    Kurssienhallintajärjestelmä
</header>
<nav>
    <ul>
        <!-- Etusivu -->
        <li><a href="<?php echo $etusivu_linkki; ?>">Etusivu</a></li>

        <!-- Opettajan näkymän valikot -->
        <?php if ($_SESSION['view'] === 'opettaja'): ?>
            <li><a href="opiskelijat.php">Opiskelijat</a>
                <ul>
                    <li><a href="lisaa_opiskelija.php">Lisää oppilas</a></li>
                    <li><a href="poista_opiskelija.php">Poista oppilas</a></li>
                    <li><a href="lisaa_oppilas_kurssille.php">Lisää oppilas kurssille</a></li>
                    <li><a href="poista_oppilas_kurssilta.php">Poista oppilas kurssilta</a></li> <!-- Poista oppilas kurssilta -->
                </ul>
            </li>
            <li><a href="opettajat.php">Opettajat</a></li>
            <li><a href="tilat.php">Tilat</a></li>
        <?php endif; ?>

        <!-- Kurssit -->
        <li><a href="kurssit.php">Kurssit</a></li>

        <!-- Näkymänvaihto -->
        <li><a href="?change_view=1">
            <?php echo $_SESSION['view'] === 'opettaja' ? 'Opiskelijanäkymä' : 'Opettajanäkymä'; ?>
        </a></li>
    </ul>
</nav>
<main>
