<?php
// Inclusion du fichier de connexion à la base de données
include("connexion.php");
// Initialisation de la variable pour le message JavaScript
$message = "";
// Variable pour stocker l'URL de redirection
$redirect_url = "gestE_S.php"; 
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
        // Redirection avec JavaScript après l'alerte
        echo "<script>alert('$message'); window.location.href='$redirect_url?id=$idEvenement';</script>";
        exit; // Arrête l'exécution du script après la redirection
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
    $sql_retour = "UPDATE gestpf SET Retour ='$retour' WHERE idEvenement='$idEvenement'";
    // Exécution de la requête d'insertion pour Retour
    if (mysqli_query($conn, $sql_retour)) {
        $message = "Retour enregistré avec succès.";
        // Redirection avec JavaScript après l'alerte
        echo "<script>alert('$message'); window.location.href='$redirect_url?id=$idEvenement';</script>";
        exit; // Arrête l'exécution du script après la redirection
    } else {
        $message = "Erreur lors de l'enregistrement du Retour : " . mysqli_error($conn);
    }
}
// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>
