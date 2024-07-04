<?php
session_start();

// Vérifier si l'utilisateur est connecté, sinon le rediriger vers la page de connexion
if (!isset($_SESSION['idService'])) {
    // Rediriger vers la page de connexion s'il n'est pas connecté
    header("Location: connexionOrganisme.php");
    exit();
}

// Vérifier si l'utilisateur a cliqué sur le lien de déconnexion
if (isset($_GET['logout'])) {
    // Détruire la session et rediriger vers la page de connexion
    session_destroy();
    header("Location: connexionOrganisme.php");
    exit();
}

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
$sql = "SELECT * FROM service WHERE idService = '$idService'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Erreur lors de l'exécution de la requête : " . mysqli_error($conn));
}

$organisme = mysqli_fetch_assoc($result);

// Vérifier si une recherche a été effectuée
if (isset($_POST['search'])) {
    $search = $_POST['search'];

    // Requête pour rechercher les événements par date ou par nom
    $sqlEvenements = "SELECT * FROM evenement WHERE idService = '$idService' AND (nomEvenement LIKE '%$search%' OR dateEvenement = '$search') AND isActive = 1";
} else {
    // Récupérer tous les événements de l'organisme
    $sqlEvenements = "SELECT * FROM evenement WHERE idService = 'STS-DDS-ATL' AND isActive = 1";
}

$resultEvenements = mysqli_query($conn, $sqlEvenements);

if (!$resultEvenements) {
    die("Erreur lors de l'exécution de la requête : " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Agenda des Evénements</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href=".\Style\all_evenement.page.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body style="background-color: #DF3131;">
<div class="container">
    

    <div class="dashboard-heading">
    <strong><h2>Agenda des Evénements</h2></strong>
    </div>

    <div class="menu" >
        <ul style="color: white;
            font-size: 1.2rem;">
            <li><a href="formulaire_evenement.page.php">Créer évènement</a></li>
            <li><a href="javascript:history.go(-1);">Retour</a></li>
        </ul>
    </div>

    <p><strong><span class="organisme-name"><?php echo $organisme['nomOrganisme']; ?></span> !</strong></p>
    <div class="dashboard-content">

        <form class="search-form" method="POST" action="">
            <input type="text" name="search" placeholder="Rechercher par nom ou date">
            <input type="submit" value="Rechercher">
        </form>

        <table>
            <tr>
                <th>Nom</th>
                <th>Date</th>
                <th>Heure de début</th>
                <th>Heure de fin</th>
                <th>Description</th>
                <th>Lieu</th>
                <th style="text-align: center;">Actions</th>
            </tr>
            <?php
            while ($evenement = mysqli_fetch_assoc($resultEvenements)) {
                echo "<tr>";
                echo "<td>".$evenement['nomEvenement']."</td>";
                echo "<td>".$evenement['dateEvenement']."</td>";
                echo "<td>".$evenement['heureDebut']."</td>";
                echo "<td>".$evenement['heureFin']."</td>";
                echo "<td>".$evenement['Description']."</td>";

                // Récupérer le nom du lieu de la table "Lieu" en utilisant l'idLieu
                $idLieu = $evenement['idLieu'];
                $sqlLieu = "SELECT lieu FROM lieu WHERE idLieu = '$idLieu'";
                $resultLieu = mysqli_query($conn, $sqlLieu);
                if ($resultLieu && mysqli_num_rows($resultLieu) > 0) {
                    $lieu = mysqli_fetch_assoc($resultLieu);
                    echo "<td>".$lieu['lieu']."</td>";
                } else {
                    echo "<td>N/A</td>";
                }

                echo "<td>";
                echo "<div class='lien'>";
                echo "<a href='info_event.php?id=".$evenement['idEvenement']."'><button>!</button></a>";
                echo "<a href='gestE_S.php?id=".$evenement['idEvenement']."'><button><strong>PF</strong></button></a>";
                echo "<a href='form_don_page.php?id=".$evenement['idEvenement']."'><button><strong>></strong></button></a>";
                echo "</div>";
                echo "</td>";
                echo "</tr>"; 
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>
