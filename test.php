<?php
session_start();
include("connexion.php");

// Connexion à la base de données
$conn = mysqli_connect($servername, $username, $passwordDB, $dbname);
if (!$conn) {
    die("La connexion a échoué: " . mysqli_connect_error());
}

$_SESSION['idEvenement'] = $_GET['id'];
$idService = $_SESSION['idService'];
$idEvenement = $_GET['id'];

// Récupérer les informations de l'organisme
$sql = "SELECT * FROM service WHERE idService = '$idService'";
$result = mysqli_query($conn, $sql);
$organisme = mysqli_fetch_assoc($result);
if (!$result) {
    die("Erreur lors de l'exécution de la requête : " . mysqli_error($conn));
}

//statistique tableau 2
// Récupérer le nombre total de donneurs par sexe pour l'événement
$sql_total_donneurs = "SELECT donneur.sexe, COUNT(DISTINCT donneur.idDonneur) as nb_total
FROM donneur
INNER JOIN participationdonneur ON donneur.idDonneur = participationdonneur.idDonneur
WHERE participationdonneur.idEvenement = '$idEvenement'
GROUP BY donneur.sexe";
$result_total_donneurs = mysqli_query($conn, $sql_total_donneurs);

// Initialisation des variables
$total_hommes = 0;
$total_femmes = 0;

while ($row = mysqli_fetch_assoc($result_total_donneurs)) {
    if ($row['sexe'] == 'Homme') {
        $total_hommes = $row['nb_total'];
    } elseif ($row['sexe'] == 'Femme') {
        $total_femmes = $row['nb_total'];
    }
}

// Récupérer les anciens donneurs pour l'événement donné (basé sur idQuestion et réponse non nulle)
$sql_anciens = "SELECT donneur.sexe, COUNT(DISTINCT donneur.idDonneur) as nb_anciens
FROM donneur
INNER JOIN participationdonneur ON donneur.idDonneur = participationdonneur.idDonneur
WHERE participationdonneur.idEvenement = '$idEvenement'
AND participationdonneur.idQuestion = 'question1'
AND participationdonneur.reponse ='OUI'
GROUP BY donneur.sexe";
$result_anciens = mysqli_query($conn, $sql_anciens);

// Initialisation des variables pour anciens donneurs
$anciens_hommes = 0;
$anciens_femmes = 0;

while ($row = mysqli_fetch_assoc($result_anciens)) {
    if ($row['sexe'] == 'Homme') {
        $anciens_hommes = $row['nb_anciens'];
    } elseif ($row['sexe'] == 'Femme') {
        $anciens_femmes = $row['nb_anciens'];
    }
}

// Calculer les nouveaux donneurs en soustrayant les anciens des totaux
$nouveaux_hommes = $total_hommes - $anciens_hommes;
$nouveaux_femmes = $total_femmes - $anciens_femmes;

//statistique tableau 2
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques Donneurs</title>
</head>

<body>
    <div class="container">
        <section class="principale">
            <div class="dashboard-heading">
                <p><strong><span><?php echo $organisme['nomOrganisme']; ?> || <?php echo $organisme['idService']; ?></span></strong></p>
            </div>
            <div class="composition">
                <div class="dashboard-content">


                </div>
            </div>
        </section>
    </div>
</body>

</html>