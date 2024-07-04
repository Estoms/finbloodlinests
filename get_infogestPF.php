<?php
include("connexion.php");
$idEvenement = $_GET['id'];

// Récupérer les données de gestPF pour l'événement
$sqlGestPF = "SELECT * FROM gestPF WHERE idEvenement = '$idEvenement'";
$resultGestPF = mysqli_query($conn, $sqlGestPF);
$gestPF = mysqli_fetch_assoc($resultGestPF);

echo $gestPF ; 

// Fermer la connexion
mysqli_close($conn);

// Extraire les données de Sorties et Retour
$sorties_boissons = "";
$sorties_biscuits = "";
$sorties_usager = "";
$retour_boissons = "";
$retour_biscuits = "";
$retour_usager = "";

if ($gestPF) {
    // Extraction des données de Sorties
    if (!empty($gestPF['Sorties'])) {
        preg_match('/Boisson\((.*?)\)/', $gestPF['Sorties'], $matches);
        $sorties_boissons = $matches[1] ?? "";
        preg_match('/Biscuits\((.*?)\)/', $gestPF['Sorties'], $matches);
        $sorties_biscuits = $matches[1] ?? "";
        preg_match('/Usager\[(.*?)\]/', $gestPF['Sorties'], $matches);
        $sorties_usager = $matches[1] ?? "";
    }

    

    // Extraction des données de Retour
    if (!empty($gestPF['Retour'])) {
        preg_match('/Boisson\((.*?)\)/', $gestPF['Retour'], $matches);
        $retour_boissons = $matches[1] ?? "";
        preg_match('/Biscuits\((.*?)\)/', $gestPF['Retour'], $matches);
        $retour_biscuits = $matches[1] ?? "";
        preg_match('/Usager\[(.*?)\]/', $gestPF['Retour'], $matches);
        $retour_usager = $matches[1] ?? "";
    }
}
?>

