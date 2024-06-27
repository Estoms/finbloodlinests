<?php
// Inclure les informations de connexion
include("connexion.php");

// Récupérer les données envoyées depuis le formulaire
$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(["message" => "Données invalides"]);
    exit;
}

// Vérifier et nettoyer les données
foreach ($data as $user) {
    // Vérifier si les clés "email", "motDePasse" et "badge" existent et sont non vides
    if (!isset($user['email']) || !isset($user['motDePasse']) || !isset($user['badge']) || empty($user['email']) || empty($user['motDePasse']) || empty($user['badge'])) {
        http_response_code(400);
        echo json_encode(["message" => "Les données doivent inclure un email, un mot de passe et un badge valides"]);
        exit;
    }

    // Filtrer et échapper les valeurs pour prévenir les injections SQL
    $email = mysqli_real_escape_string($conn, htmlspecialchars($user['email']));
    $motDePasse = mysqli_real_escape_string($conn, htmlspecialchars($user['motDePasse']));
    $badge = mysqli_real_escape_string($conn, htmlspecialchars($user['badge']));

    // Vérifier si le badge est valide (Membre ou Administrateur)
    if (!in_array($badge, ['Membre', 'Administrateur'])) {
        http_response_code(400);
        echo json_encode(["message" => "Le badge doit être 'Membre' ou 'Administrateur'"]);
        exit;
    }

    // Préparer la requête SQL pour l'insertion des utilisateurs
    $sql = "INSERT INTO utilisateur (email, motDePasse, Badge) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["message" => "Erreur de préparation de la requête"]);
        exit;
    }

    // Exécuter la requête d'insertion
    $stmt->bind_param("sss", $email, $motDePasse, $badge);
    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(["message" => "Erreur lors de l'insertion des utilisateurs"]);
        exit;
    }

    // Fermer le statement
    $stmt->close();
}

// Fermer la connexion et retourner une réponse réussie
$conn->close();
echo json_encode(["message" => "Utilisateurs ajoutés avec succès"]);
?>
