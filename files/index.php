<?php
session_start();
include 'yhteys.php';
include 'navbar.php';

// Tarkista näkymä ja aseta oletus
if (!isset($_SESSION['view'])) {
    $_SESSION['view'] = 'opettaja'; // Oletusnäkymä
}

// Vaihda näkymää painikkeen klikkauksella
if (isset($_GET['change_view'])) {
    $_SESSION['view'] = $_SESSION['view'] === 'opettaja' ? 'opiskelija' : 'opettaja';
}

// Näytetään teksti näkymän mukaan
if ($_SESSION['view'] === 'opettaja') {
    echo '<h2>Opettajan näkymä</h2>';
    echo '<p>Tässä näkymässä voit hallita opiskelijoita, opettajia ja kursseja.</p>';
} else {
    echo '<h2>Tervetuloa oppilas</h2>';
    echo '<p>Täällä voit selata sinulle määritettyjä kursseja.</p>';
}
?>
</main>
</body>
</html>
