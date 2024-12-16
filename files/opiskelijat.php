<?php
session_start();
include 'yhteys.php';
include 'navbar.php';

if ($_SESSION['view'] === 'opettaja') {
    // Opettajan näkymä: Näytetään kaikki opiskelijat ja heidän kurssit

    echo '<h2>Opiskelijat ja heidän kurssinsa</h2>';

    $sql = "SELECT opiskelijat.etunimi, opiskelijat.sukunimi, opiskelijat.vuosikurssi,
                   GROUP_CONCAT(kurssit.nimi ORDER BY kurssit.nimi SEPARATOR ', ') AS kurssit,
                   opiskelijat.opiskelijanumero
            FROM opiskelijat
            LEFT JOIN kurssikirjautumiset ON opiskelijat.opiskelijanumero = kurssikirjautumiset.opiskelija_id
            LEFT JOIN kurssit ON kurssikirjautumiset.kurssi_id = kurssit.tunnus
            GROUP BY opiskelijat.opiskelijanumero";

    $kysely = $yhteys->query($sql);
    $opiskelijat = $kysely->fetchAll(PDO::FETCH_ASSOC);

    if ($opiskelijat) {
        echo '<table border="1">
            <tr>
                <th>Etunimi</th>
                <th>Sukunimi</th>
                <th>Vuosikurssi</th>
                <th>Kurssit</th>
            </tr>';

        foreach ($opiskelijat as $opiskelija) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($opiskelija['etunimi']) . '</td>';
            echo '<td>' . htmlspecialchars($opiskelija['sukunimi']) . '</td>';
            echo '<td>' . htmlspecialchars($opiskelija['vuosikurssi']) . '</td>';
            echo '<td>' . htmlspecialchars($opiskelija['kurssit']) . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo '<p>Ei opiskelijoita.</p>';
    }
}
?>
