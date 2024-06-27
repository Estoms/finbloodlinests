<?php
// Inclusion du fichier de connexion à la base de données
include("connexion.php");
 
//insertion dans la base de données suivant le formulaire
// Initialisation de la variable pour le message JavaScript
$message = "";
// Vérification si le formulaire de Sorties est soumis
if (isset($_POST['sendSorties'])) {
    // Récupération des valeurs du formulaire de Sorties
    $sorties_boissons = $_POST['sorties_boissons'];
    $sorties_biscuits = $_POST['sorties_biscuits'];
    $sorties_usager = $_POST['sorties_usager'];
    $idEvenement = $_POST['idEvenement'];
    // Formatage de la chaîne pour Sorties
    $sorties = "Collation[Boisson($sorties_boissons)|Biscuits($sorties_biscuits)]Usager[$sorties_usager]";
    // Requête SQL d'insertion pour Sorties
    $sql_sorties = "INSERT INTO gestPF (Sorties, idEvenement) VALUES ('$sorties', '$idEvenement')";
    // Exécution de la requête d'insertion pour Sorties
    if (mysqli_query($conn, $sql_sorties)) {
        $message = "Sorties enregistrées avec succès.";
    } else {
        $message = "Erreur lors de l'enregistrement des Sorties : " . mysqli_error($conn);
    }
}

// Vérification si le formulaire de Retour est soumis
if (isset($_POST['sendRetour'])) {
    // Récupération des valeurs du formulaire de Retour
    $retour_boissons = $_POST['retour_boissons'];
    $retour_biscuits = $_POST['retour_biscuits'];
    $retour_usager = $_POST['retour_usager'];
    $idEvenement = $_POST['idEvenement']; 

    // Formatage de la chaîne pour Retour
    $retour = "Collation[Boisson($retour_boissons)|Biscuits($retour_biscuits)]Usager[$retour_usager]";
    // Requête SQL d'insertion pour Retour
    $sql_retour = "INSERT INTO gestPF (Retour, idEvenement) VALUES ('$retour', '$idEvenement')";
    // Exécution de la requête d'insertion pour Retour
    if (mysqli_query($conn, $sql_retour)) {
        $message = "Retour enregistré avec succès.";
    } else {
        $message = "Erreur lors de l'enregistrement du Retour : " . mysqli_error($conn);
    }
}
// Fermeture de la connexion à la base de données
mysqli_close($conn);




include("connexion.php");
$idEvenement = $_GET['id'];
// Préparer et exécuter la requête pour récupérer les données
$sql = "SELECT * FROM gestPF WHERE idEvenement = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idEvenement);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si des données ont été trouvées
$data = [];
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
}
// Renvoyer les données au format JSON
echo json_encode($data);

// Fermeture de la connexion à la base de données
mysqli_close($conn);


?>
