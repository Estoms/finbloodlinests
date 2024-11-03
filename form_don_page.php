<?php
session_start();
include("connexion.php");
$conn = mysqli_connect($servername, $username, $passwordDB, $dbname);
if (!$conn) {
    die("La connexion a échoué: " . mysqli_connect_error());
}
$_SESSION['idEvenement'] = $_GET['id'];
// Récupérer les données 
$idService = $_SESSION['idService'];
$idEvenement = $_GET['id'];
$sql = "SELECT * FROM service WHERE idService = '$idService'";
$result = mysqli_query($conn, $sql);
$organisme = mysqli_fetch_assoc($result);
if (!$result) {
    die("Erreur lors de l'exécution de la requête : " . mysqli_error($conn));
}

// Récupérer les données evenements
$sqlEvenements = "SELECT * FROM evenement WHERE idService = '$idService' AND idEvenement = '$idEvenement'";
$result_evenements = mysqli_query($conn, $sqlEvenements);
$evenements = mysqli_fetch_assoc($result_evenements);
if (!$result_evenements) {
    die("Erreur lors de l'exécution de la requête : " . mysqli_error($conn));
}
// Récupérer données lieu 
$idLieu = $evenements['idLieu'];
$sqlLieu = "SELECT * FROM lieu WHERE idLieu = '$idLieu'";
$resultLieu = mysqli_query($conn, $sqlLieu);
if ($resultLieu && mysqli_num_rows($resultLieu) > 0) {
    $lieu = mysqli_fetch_assoc($resultLieu);
} else {
    echo "<td>N/A</td>";
}

// Récupérer les données donneurs 
$sqldonneur = "SELECT donneur.*
FROM donneur
INNER JOIN participationdonneur ON donneur.idDonneur = participationdonneur.idDonneur
WHERE participationdonneur.idEvenement = '$idEvenement'
GROUP BY donneur.idDonneur;
";
$result_donneur = mysqli_query($conn, $sqldonneur);
if (!$result_donneur) {
    die("Erreur lors de l'exécution de la requête : " . mysqli_error($conn));
}

//statistique tableau 1
$sql_nb_donneur = "SELECT donneur.*
FROM donneur
INNER JOIN participationdonneur ON donneur.idDonneur = participationdonneur.idDonneur
WHERE participationdonneur.idEvenement = '$idEvenement'
GROUP BY donneur.idDonneur;
";
$result_nb_donneur = mysqli_query($conn, $sql_nb_donneur);
if (!$result_nb_donneur) {
    die("Erreur lors de l'exécution de la requête : " . mysqli_error($conn));
}

// Initialiser les variables pour les tranches d'âge
$tranche_18_24 = 0;
$tranche_25_30 = 0;
$tranche_31_44 = 0;
$tranche_45_plus = 0;

// Parcourir les résultats et compter les donneurs par tranche d'âge
while ($row = mysqli_fetch_assoc($result_nb_donneur)) {
    $age = $row['ageDonneur'];

    if ($age >= 18 && $age <= 24) {
        $tranche_18_24++;
    } elseif ($age >= 25 && $age <= 30) {
        $tranche_25_30++;
    } elseif ($age >= 31 && $age <= 44) {
        $tranche_31_44++;
    } elseif ($age >= 45) {
        $tranche_45_plus++;
    }
}

// Calculer le total
$total_poches = $tranche_18_24 + $tranche_25_30 + $tranche_31_44 + $tranche_45_plus;
//statistique tableau 1

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
    <title>Tableau de bord</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href=".\Style\form_don_page_styles.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <header class="header" id="header">
            <a href="index.php" class="logo">STS/DDS-ATL.</a>
            <div id="menu-icon"><span class="animate" style="--i:2;"></span></div>
            <nav class="navbar">
                <a class="active" href="#"></a>
                <a class="active" href="#"></a>
                <a href="javascript:history.go(-1);">Retour</a>
            </nav>
            <div class="men">
            </div>
        </header>
        <section class="principale">
            <div class="dashboard-heading">
                <p><strong><span><?php echo $organisme['nomOrganisme']; ?> || <?php echo $organisme['idService']; ?></span></strong></p>
            </div>
            <div class="composition">
                <div class="dashboard-content">
                    <h3>Evenement :</h3>
                    <table>
                        <tr>
                            <td><?php echo $evenements['nomEvenement']; ?></td>
                            <td><?php echo $evenements['dateEvenement']; ?></td>
                            <td><?php echo $evenements['heureDebut']; ?></td>
                            <td><?php echo $evenements['heureFin']; ?></td>
                            <td><?php echo $evenements['Description']; ?></td>
                            <td><?php echo $lieu['lieu']; ?></td>
                        </tr>
                    </table> <br>
                    <div class="div">
                        <button id="showStatsBtn">Statistique</button>
                    </div> <br>
                    <div id="statistique" style="display: none;">
                        <div>
                            <!-- affichage du tableau des tranches d'âches -->
                            <table border="1">
                                <thead>
                                    <tr>
                                        <th>Tranche d'Âge</th>
                                        <th>Nombre de Poche</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>18 - 24 Ans</td>
                                        <td><?php echo $tranche_18_24; ?></td>
                                    </tr>
                                    <tr>
                                        <td>25 - 30 Ans</td>
                                        <td><?php echo $tranche_25_30; ?></td>
                                    </tr>
                                    <tr>
                                        <td>31 - 44 Ans</td>
                                        <td><?php echo $tranche_31_44; ?></td>
                                    </tr>
                                    <tr>
                                        <td>45 Ans et plus</td>
                                        <td><?php echo $tranche_45_plus; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td><strong><?php echo $total_poches; ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <div>
                            <table border="1" cellspacing="0" cellpadding="5">
                                <thead>
                                    <tr>
                                        <th>PRELEVEMENT</th>
                                        <th>SEXE</th>
                                        <th>Ancien Donneur</th>
                                        <th>Nouveau Donneur</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td rowspan="3">Nombre de donneur reçus</td>
                                        <td>Masculin</td>
                                        <td><?php echo $anciens_hommes; ?></td>
                                        <td><?php echo $nouveaux_hommes; ?></td>
                                        <td><?php echo $total_hommes; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Féminin</td>
                                        <td><?php echo $anciens_femmes; ?></td>
                                        <td><?php echo $nouveaux_femmes; ?></td>
                                        <td><?php echo $total_femmes; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td><?php echo $anciens_hommes + $anciens_femmes; ?></td>
                                        <td><?php echo $nouveaux_hommes + $nouveaux_femmes; ?></td>
                                        <td><?php echo $total_hommes + $total_femmes; ?></td>
                                    </tr>
                                    <tr>
                                        <td rowspan="3">Nombre de donneur Refusés</td>
                                        <td>Masculin</td>
                                        <td><?php echo "0"; ?></td>
                                        <td><?php echo "0"; ?></td>
                                        <td><?php echo "0"; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Féminin</td>
                                        <td><?php echo "0"; ?></td>
                                        <td><?php echo "0"; ?></td>
                                        <td><?php echo "0"; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td><?php echo "0"; ?></td>
                                        <td><?php echo "0"; ?></td>
                                        <td><?php echo "0"; ?></td>
                                    </tr>
                                    <tr>
                                        <td rowspan="3">Nombre de donneur prelevés</td>
                                        <td>Masculin</td>
                                        <td><?php echo $anciens_hommes; ?></td>
                                        <td><?php echo $nouveaux_hommes; ?></td>
                                        <td><?php echo $total_hommes; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Féminin</td>
                                        <td><?php echo $anciens_femmes; ?></td>
                                        <td><?php echo $nouveaux_femmes; ?></td>
                                        <td><?php echo $total_femmes; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td><?php echo $anciens_hommes + $anciens_femmes; ?></td>
                                        <td><?php echo $nouveaux_hommes + $nouveaux_femmes; ?></td>
                                        <td><?php echo $total_hommes + $total_femmes; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="principale2">
            <div class="composition">
                <div class="dashboard-content">
                    <div class="ent">
                        <div>
                        </div>
                        <div class="div">
                            <?php
                            // Vérifiez si l'utilisateur vient de la page all_dons.php
                            if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], 'all_dons.php') === false): ?>
                                <a href="form_donneur_page.php">
                                    <button>+ Nouveau don</button>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <br>
                    <table>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Sexe</th>
                            <th>Téléphone</th>
                            <th>Profession</th>
                        </tr>
                        <?php
                        while ($donneur = mysqli_fetch_assoc($result_donneur)) {
                            echo "<tr>";
                            echo "<td>" . $donneur['nomDonneur'] . "</td>";
                            echo "<td>" . $donneur['prenomDonneur'] . "</td>";
                            echo "<td>" . $donneur['sexe'] . "</td>";
                            echo "<td>" . $donneur['telPersoDonneur'] . "</td>";
                            echo "<td>" . $donneur['professionDonneur'] . "</td>";
                            echo "<td class='center-content'><a href='infos_donneurdon.php?id_donneur=" . $donneur['idDonneur'] . "&id=" . $idEvenement . "'class='no-style'>!</a></td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </section>
    </div>
</body>
<script>
    document.getElementById("showStatsBtn").addEventListener("click", function() {
        var statsDiv = document.getElementById("statistique");
        if (statsDiv.style.display === "none") {
            statsDiv.style.display = "block";
        } else {
            statsDiv.style.display = "none";
        }
    });
</script>

</html>