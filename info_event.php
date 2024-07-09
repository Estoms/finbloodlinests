<?php
session_start();

// Coordonnées de la base de données
include("connexion.php");
// Création d'une nouvelle connexion
$conn = mysqli_connect($servername, $username, $passwordDB, $dbname);
// Vérification de la connexion
if (!$conn) {
    die("La connexion a échoué: " . mysqli_connect_error());
}


// Récupérer les informations de l'organisme de collecte de sang à partir de la base de données
$idService = $_SESSION['idService'];
$idEvenement = $_GET['id'];

$sql = "SELECT * FROM service WHERE idService = '$idService'";
$result = mysqli_query($conn, $sql);
$organisme = mysqli_fetch_assoc($result);
if (!$result) {
    die("Erreur lors de l'exécution de la requête : " . mysqli_error($conn));
}

// Récupérer tous les infos de léevenements
$sqlEvenements = "SELECT * FROM evenement WHERE idService = '$idService' AND idEvenement = '$idEvenement'";
$result_evenements = mysqli_query($conn,$sqlEvenements) ;
$evenements = mysqli_fetch_assoc($result_evenements);
if (!$result_evenements) {
    die("Erreur lors de l'exécution de la requête : " . mysqli_error($conn));
}

// Récupérer les infos sur le lieu
$idLieu = $evenements['idLieu'];
$sqlLieu = "SELECT * FROM lieu WHERE idLieu = '$idLieu'";
$resultLieu = mysqli_query($conn, $sqlLieu);
if ($resultLieu && mysqli_num_rows($resultLieu) > 0) {
    $lieu = mysqli_fetch_assoc($resultLieu);
} else {
    echo "<td>N/A</td>";
}

// Récupérer les infos sur les participants
$sqlParticipation = "SELECT * FROM participation WHERE  idEvenement = '$idEvenement'";
$result_participation = mysqli_query($conn,$sqlParticipation) ;
if (!$result_participation) {
    die("Erreur lors de l'exécution de la requête : " . mysqli_error($conn));
}


mysqli_close($conn);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href=".\Style\styles.css" />
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
        <p><strong><span ><?php echo $organisme['nomOrganisme'];?> || <?php echo $organisme['idService'];?></span></strong></p>
        </div>
        <div class="composition">
            <div class="dashboard-content">
                <h3>Evenement :</h3> <br>
                <table>
                        <tr>
                            <th>Nom</th>
                            <th>Date</th>
                            <th>Heure de début</th>
                            <th>Heure de fin</th>
                            <th>Description</th>
                            <th>Lieu</th>
                        </tr>
                    <tr>
                        <td><?php echo $evenements['nomEvenement']; ?></td>
                        <td><?php echo $evenements['dateEvenement']; ?></td>
                        <td><?php echo $evenements['heureDebut']; ?></td>
                        <td><?php echo $evenements['heureFin']; ?></td>
                        <td><?php echo $evenements['Description']; ?></td>
                        <td><?php echo $lieu['lieu']; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="composition">
        <div class="dashboard-content">
                <h3>Participant :</h3> <br>
                <table>
                        <tr>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>Commentaire</th>
                        </tr>
                        <?php
                            while ($participation = mysqli_fetch_assoc($result_participation)) {
                                echo "<tr>";
                                echo "<td>".$participation['participantNom']."</td>";
                                echo "<td>".$participation['telephoneParticipant']."</td>";
                                echo "<td>".$participation['commentaires']."</td>";
                                echo "</tr>";
                            }
                        ?>
                        
                </table>
            </div>
        </div>
        <div class="composition">
            <div class="dashboard-content">
                <h3>Lieu :</h3> <br>
                <table>
                        <tr>
                            <th>Lieu</th>
                            <th>coordonnée Geographique</th>
                        </tr>
                    <tr>
                        <td><?php echo $lieu['lieu']; ?></td>
                        <td><?php echo $lieu['coordonneeGeographique']; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
    </section>
</div>
</body>
</html>