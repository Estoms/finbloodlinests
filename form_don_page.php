<?php
session_start();
// Coordonnées de la base de données
include("connexion.php");
// Création d'une nouvelle connexion faculatatif à retirer dans la prochaine version
$conn = mysqli_connect($servername, $username, $passwordDB, $dbname);
// Vérification de la connexion
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
$result_evenements = mysqli_query($conn,$sqlEvenements) ;
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
$result_donneur = mysqli_query($conn,$sqldonneur) ;
if (!$result_donneur) {
    die("Erreur lors de l'exécution de la requête : " . mysqli_error($conn));
}

$sql_nb_donneur = "SELECT COUNT(DISTINCT donneur.idDonneur) as nb_donneur
FROM donneur
INNER JOIN participationdonneur ON donneur.idDonneur = participationdonneur.idDonneur
WHERE participationdonneur.idEvenement = '$idEvenement';
"; 
$result_nb_donneur = mysqli_query($conn,$sql_nb_donneur) ;
$nb_donneur = mysqli_fetch_assoc($result_nb_donneur);
if (!$result_nb_donneur) {
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
        <p><strong><span ><?php echo $organisme['nomOrganisme'];?> || <?php echo $organisme['idService'];?></span></strong></p>
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
                </table>
            </div>
        </div>
    </section>
    <section class="principale2">
    <div class="composition">
        <div class="dashboard-content">
            <div class="ent">
                <div>
                    <h3><Strong>Dons :</Strong> <?php echo $nb_donneur['nb_donneur']; ?> donneurs</h3>
                </div>
                <div class="div">
                    <a href="form_donneur_page.php">
                        <button>+ Nouveau don</button>
                    </a>
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
                        echo "<td>".$donneur['nomDonneur']."</td>";
                        echo "<td>".$donneur['prenomDonneur']."</td>";
                        echo "<td>".$donneur['sexe']."</td>";
                        echo "<td>".$donneur['telPersoDonneur']."</td>";
                        echo "<td>".$donneur['professionDonneur']."</td>";
                        echo "<td class='center-content'><a href='infos_donneurdon.php?id_donneur=".$donneur['idDonneur']."&id=".$idEvenement."'class='no-style'>!</a></td>";
                        echo "</tr>";
                    }
                    ?>
            </table>
        </div>
    </div>
</section>
</div>
</body>
</html>