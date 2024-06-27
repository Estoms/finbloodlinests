<?php
// Inclure les informations de connexion
include("connexion.php");

// Requête SQL pour récupérer les utilisateurs
$sql = "SELECT email, Badge FROM utilisateur";
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    // Parcourir les résultats et ajouter chaque utilisateur à la liste
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Fermer la connexion
$conn->close();

// Renvoyer les utilisateurs en JSON
header('Content-Type: application/json');
echo json_encode($users);
?>
