<?php
// Inclure le fichier de connexion à la base de données
include("connexion.php");

// Créer une nouvelle connexion à la base de données
$conn = mysqli_connect($servername, $username, $passwordDB, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ID du donneur
$idDonneur = 5; // Cet ID devrait être déterminé dynamiquement

// Tableau associatif des questions
$questions_map = [
    'question1' => 'Avez-vous eu de la fièvre au cours des 7 derniers jours ?',
    'question2' => 'Avez-vous eu une infection au cours des 15 derniers jours ?',
    // Ajouter d'autres questions ici...
];

// Requête pour récupérer les réponses non vides du donneur
$sql = "SELECT idQuestion, reponse, date_reponse FROM participationdonneur WHERE idDonneur = '$idDonneur' AND reponse <> ''";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2 style='color: #333;'>Réponses du donneur</h2>";
    echo "<div style='overflow-x:auto;'>";
    echo "<table style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background-color: #f2f2f2;'>";
    echo "<th style='border: 1px solid #ddd; padding: 8px;'>Question</th>";
    echo "<th style='border: 1px solid #ddd; padding: 8px;'>Réponse</th>";
    echo "<th style='border: 1px solid #ddd; padding: 8px;'>Date</th>";
    echo "</tr>";
    while ($row = $result->fetch_assoc()) {
        if ($row["date_reponse"] != '0000-00-00') { // Vérifie si la date est différente de '0000-00-00'
            $libelle_question = isset($questions_map[$row["idQuestion"]]) ? $questions_map[$row["idQuestion"]] : $row["idQuestion"];
            echo "<tr style='border: 1px solid #ddd;'>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $libelle_question . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $row["reponse"] . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $row["date_reponse"] . "</td>";
            echo "</tr>";
        } else {
            $libelle_question = isset($questions_map[$row["idQuestion"]]) ? $questions_map[$row["idQuestion"]] : $row["idQuestion"];
            echo "<tr style='border: 1px solid #ddd;'>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $libelle_question . "</td>";
            echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $row["reponse"] . "</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
    echo "</div>";
} else {
    echo "<p>Aucune réponse trouvée pour ce donneur.</p>";
}

// Fermer la connexion
$conn->close();
?>
