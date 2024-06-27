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

// Vérifier si c'est la première connexion de l'utilisateur
if (isset($_SESSION['premiere_connexion'])) {
    // Requête de mise à jour de la colonne isActive pour les événements passés
    $sqlUpdate = "UPDATE evenement SET isActive = 0 WHERE dateEvenement < CURDATE() - INTERVAL 1 DAY";
    if (!mysqli_query($conn, $sqlUpdate)) {
        die("Erreur lors de la mise à jour des événements passés : " . mysqli_error($conn));
    }


    // Détruire la variable de session pour éviter de répéter la mise à jour à chaque chargement de page
    unset($_SESSION['premiere_connexion']);
}

// Récupérer les informations de l'organisme de collecte de sang à partir de la base de données
$idService = $_SESSION['idService'];
$sql = "SELECT * FROM service WHERE idService = '$idService'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Erreur lors de l'exécution de la requête : " . mysqli_error($conn));
}

$organisme = mysqli_fetch_assoc($result);

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
        <a href="#" class="logo">STS/DDS-ATL.</a>
        <div id="menu-icon"><span class="animate" style="--i:2;"></span></div>
        <nav class="navbar">
            <a class="active" href="formulaire_evenement.page.php">Add Evenements</a>
            <a class="active" href="all_evenement.page.php">Evenements</a>
            <a href="#employer"></a>
        </nav>
        <div class="men">
            <p><a href="?logout=true">Déconnexion</a></p>
        </div>
    </header>
    <section class="principale">
        <div class="dashboard-heading">
        <p><strong><span ><?php echo $organisme['nomOrganisme']; ?></span></strong> !</p>
        </div>

<div class="dashboard-content">
    <h3>Informations du Service :</h3>

    <table>
        <tr>
            <th>Service</th>
            <th>Adresse</th>
            <th>Ville</th>
            <th>Téléphone</th>
            <th>Email</th>
        </tr>
        <tr>
            <td><?php echo $organisme['nomOrganisme']; ?></td>
            <td><?php echo $organisme['addOrganisme']; ?></td>
            <td><?php echo $organisme['ville']; ?></td>
            <td><?php echo $organisme['telOrganisme']; ?></td>
            <td><?php echo $organisme['mailOrganisme']; ?></td>
        </tr>
    </table>
</div>
    </section>
</div>
</body>
</html>