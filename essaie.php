<?php
function formatData($collation, $usager)
{
    // Formater les sections Collation et Usager
    $collationFormatted = "Collation[Boisson(" . $collation['boisson'] . ")|Biscuits(" . $collation['biscuits'] . ")]";
    $usagerFormatted = "Usager[PocheDeSang(" . $usager['pocheDeSang'] . ")]";
    // Combiner les deux sections
    return $collationFormatted . $usagerFormatted;
}
$sortieData = array(
    'collation' => array('boisson' => 50, 'biscuits' => 50),
    'usager' => array('pocheDeSang' => 100)
);

$retourData = array(
    'collation' => array('boisson' => 10, 'biscuits' => 10),
    'usager' => array('pocheDeSang' => 60)
);

// Formater les données
$sortieFormatted = formatData($sortieData['collation'], $sortieData['usager']);
$retourFormatted = formatData($retourData['collation'], $retourData['usager']);

// Préparation de la requête d'insertion
$pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
$statement = $pdo->prepare("INSERT INTO gestPF (Sorties, Retour) VALUES (:sorties, :retour)");

$statement->bindParam(':sorties', $sortieFormatted);
$statement->bindParam(':retour', $retourFormatted);

// Exécution de la requête
if ($statement->execute()) {
    echo "Enregistrement réussi";
} else {
    echo "Erreur lors de l'enregistrement";
}
