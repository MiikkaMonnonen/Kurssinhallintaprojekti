<?php
session_start();
include 'yhteys.php';
include 'navbar.php';

if ($_SESSION['view'] === 'opettaja') {
    // Opettajan näkymä: Näytetään kaikki kurssit ja niiden osallistujat
    echo '<h2>Kaikki kurssit</h2>';

    $sql = "SELECT kurssit.tunnus, kurssit.nimi, kurssit.alkupaiva, kurssit.loppupaiva, 
            kurssit.viikonpaiva, kurssit.alkuaika, kurssit.loppuaika,
            opettajat.etunimi AS opettaja_etunimi, opettajat.sukunimi AS opettaja_sukunimi, 
            tilat.nimi AS tila_nimi
            FROM kurssit
            JOIN opettajat ON kurssit.opettaja_id = opettajat.tunnusnumero
            JOIN tilat ON kurssit.tila_id = tilat.tunnus";

    $kysely = $yhteys->query($sql);
    $kurssit = $kysely->fetchAll(PDO::FETCH_ASSOC);

    foreach ($kurssit as $kurssi) {
        echo '<h3>Kurssi: ' . htmlspecialchars($kurssi['nimi']) . '</h3>';
        echo '<p><strong>Alkupäivä:</strong> ' . htmlspecialchars($kurssi['alkupaiva']) . '</p>';
        echo '<p><strong>Loppupäivä:</strong> ' . htmlspecialchars($kurssi['loppupaiva']) . '</p>';
        echo '<p><strong>Viikonpäivä:</strong> ' . htmlspecialchars($kurssi['viikonpaiva']) . '</p>';
        echo '<p><strong>Kellonajat:</strong> ' . htmlspecialchars($kurssi['alkuaika']) . ' - ' . htmlspecialchars($kurssi['loppuaika']) . '</p>';
        echo '<p><strong>Opettaja:</strong> ' . htmlspecialchars($kurssi['opettaja_etunimi'] . ' ' . $kurssi['opettaja_sukunimi']) . '</p>';
        echo '<p><strong>Tila:</strong> ' . htmlspecialchars($kurssi['tila_nimi']) . '</p>';

        // Haetaan kurssille ilmoittautuneet opiskelijat
        $sql_opiskelijat = "SELECT opiskelijat.etunimi, opiskelijat.sukunimi, opiskelijat.vuosikurssi
                            FROM kurssikirjautumiset
                            JOIN opiskelijat ON kurssikirjautumiset.opiskelija_id = opiskelijat.opiskelijanumero
                            WHERE kurssikirjautumiset.kurssi_id = :kurssi_id";

        $kysely_opiskelijat = $yhteys->prepare($sql_opiskelijat);
        $kysely_opiskelijat->execute(['kurssi_id' => $kurssi['tunnus']]);
        $opiskelijat = $kysely_opiskelijat->fetchAll(PDO::FETCH_ASSOC);

        if ($opiskelijat) {
            echo '<h4>Opiskelijat:</h4>';
            echo '<table border="1">
                <tr>
                    <th>Etunimi</th>
                    <th>Sukunimi</th>
                    <th>Vuosikurssi</th>
                </tr>';
            foreach ($opiskelijat as $opiskelija) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($opiskelija['etunimi']) . '</td>';
                echo '<td>' . htmlspecialchars($opiskelija['sukunimi']) . '</td>';
                echo '<td>' . htmlspecialchars($opiskelija['vuosikurssi']) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>Ei opiskelijoita tällä kurssilla.</p>';
        }
    }
} else {
    // Opiskelijan näkymä: Näytetään vain Matin kurssit
    echo '<h2>Matin kurssit</h2>';

    // Hae Matin ID tietokannasta
    $opiskelija_nimi = 'Matti Mehiläinen';
    $sql_matti = "SELECT opiskelijanumero FROM opiskelijat WHERE CONCAT(etunimi, ' ', sukunimi) = :nimi";
    $kysely_matti = $yhteys->prepare($sql_matti);
    $kysely_matti->execute(['nimi' => $opiskelija_nimi]);
    $matti = $kysely_matti->fetch(PDO::FETCH_ASSOC);

    if (!$matti) {
        echo '<p>Virhe: Opiskelijaa "Matti Mehiläinen" ei löytynyt.</p>';
        exit();
    }

    $opiskelija_id = $matti['opiskelijanumero'];

    // Haetaan Matin kurssit
    $sql = "SELECT kurssit.nimi, kurssit.alkupaiva, kurssit.loppupaiva, kurssit.viikonpaiva, kurssit.alkuaika, kurssit.loppuaika, tilat.nimi AS tila_nimi
            FROM kurssikirjautumiset
            JOIN kurssit ON kurssikirjautumiset.kurssi_id = kurssit.tunnus
            JOIN tilat ON kurssit.tila_id = tilat.tunnus
            WHERE kurssikirjautumiset.opiskelija_id = :opiskelija_id";

    $kysely = $yhteys->prepare($sql);
    $kysely->execute(['opiskelija_id' => $opiskelija_id]);
    $kurssit = $kysely->fetchAll(PDO::FETCH_ASSOC);

    if ($kurssit) {
        echo '<table border="1">
            <tr>
                <th>Kurssi</th>
                <th>Alkupäivä</th>
                <th>Loppupäivä</th>
                <th>Viikonpäivä</th>
                <th>Kellonaika</th>
                <th>Tila</th>
            </tr>';
        foreach ($kurssit as $kurssi) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($kurssi['nimi']) . '</td>';
            echo '<td>' . htmlspecialchars($kurssi['alkupaiva']) . '</td>';
            echo '<td>' . htmlspecialchars($kurssi['loppupaiva']) . '</td>';
            echo '<td>' . htmlspecialchars($kurssi['viikonpaiva']) . '</td>';
            echo '<td>' . htmlspecialchars($kurssi['alkuaika']) . ' - ' . htmlspecialchars($kurssi['loppuaika']) . '</td>';
            echo '<td>' . htmlspecialchars($kurssi['tila_nimi']) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>Ei kursseja.</p>';
    }
}
?>
</main>
</body>
</html>
