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

// Récupérer le nombre total de donneurs par sexe pour l'événement
$sql_total_donneurs = "SELECT donneur.sexe, COUNT(DISTINCT donneur.idDonneur) as nb_total
FROM donneur
INNER JOIN participationdonneur ON donneur.idDonneur = participationdonneur.idDonneur
WHERE participationdonneur.idEvenement = '$idEvenement'
GROUP BY donneur.sexe";
$result_total_donneurs = mysqli_query($conn, $sql_total_donneurs);

$total_donneurs = [];
while ($row = mysqli_fetch_assoc($result_total_donneurs)) {
    $total_donneurs[$row['sexe']] = $row['nb_total'];
}

// Récupérer les anciens donneurs pour l'événement donné (basé sur idQuestion et réponse non nulle)
$sql_anciens = "SELECT donneur.sexe, COUNT(DISTINCT donneur.idDonneur) as nb_anciens
FROM donneur
INNER JOIN participationdonneur ON donneur.idDonneur = participationdonneur.idDonneur
WHERE participationdonneur.idEvenement = '$idEvenement'
AND participationdonneur.idQuestion = 'question1'
AND participationdonneur.reponse IS NOT NULL
GROUP BY donneur.sexe";
$result_anciens = mysqli_query($conn, $sql_anciens);

$anciens_donneurs = [];
while ($row = mysqli_fetch_assoc($result_anciens)) {
    $anciens_donneurs[$row['sexe']] = $row['nb_anciens'];
}

// Calculer les nouveaux donneurs en soustrayant les anciens des totaux
$nouveaux_donneurs = [];
foreach ($total_donneurs as $sexe => $nb_total) {
    $nb_anciens = isset($anciens_donneurs[$sexe]) ? $anciens_donneurs[$sexe] : 0;
    $nouveaux_donneurs[$sexe] = $nb_total - $nb_anciens;
}

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
                    <div>
                        <!-- Tableau des donneurs -->
                        <table border="1">
                            <thead>
                                <tr>
                                    <th>Sexe</th>
                                    <th>Nombre total de donneurs</th>
                                    <th>Nombre d'anciens donneurs</th>
                                    <th>Nombre de nouveaux donneurs</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($total_donneurs as $sexe => $nb_total) : ?>
                                    <tr>
                                        <td><?php echo $sexe; ?></td>
                                        <td><?php echo $nb_total; ?></td>
                                        <td><?php echo isset($anciens_donneurs[$sexe]) ? $anciens_donneurs[$sexe] : 0; ?></td>
                                        <td><?php echo isset($nouveaux_donneurs[$sexe]) ? $nouveaux_donneurs[$sexe] : 0; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>

</html>
