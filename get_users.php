<?php
include("connexion.php");
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
header('Content-Type: application/json');
echo json_encode($users);
?>
