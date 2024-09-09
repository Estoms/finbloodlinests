<?php
// Démarrer la session
session_start();

// Vérifier si des variables de session existent
if (!empty($_SESSION)) {
    echo "<h2>Variables de session</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Clé</th><th>Valeur</th></tr>";
    foreach ($_SESSION as $key => $value) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($key) . "</td>";
        echo "<td>" . htmlspecialchars(print_r($value, true)) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Aucune variable de session n'est définie.";
}
?>
