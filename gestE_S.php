<?php
session_start();
// Coordonnées de la base de données
include("connexion.php");
// Vérification de la connexion
if (!$conn) {
    die("La connexion a échoué: " . mysqli_connect_error());
}
$_SESSION['idEvenement'] = $_GET['id'];
$idService = $_SESSION['idService'];
$idEvenement = $_GET['id'];
// Récupérer les données de l'organisme
$sql = "SELECT * FROM service WHERE idService = '$idService'";
$result = mysqli_query($conn, $sql);
$organisme = mysqli_fetch_assoc($result);
if (!$result) {
    die("Erreur lors de l'exécution de la requête : " . mysqli_error($conn));
}
// récupérer les infos sur l'évenement
$sqlEvenements = "SELECT * FROM evenement WHERE idService = '$idService' AND idEvenement = '$idEvenement'";
$result_evenements = mysqli_query($conn, $sqlEvenements);
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
// Récupérer les données de gestPF pour l'événement
$sqlGestPF = "SELECT * FROM gestPF WHERE idEvenement = '$idEvenement'";
$resultGestPF = mysqli_query($conn, $sqlGestPF);
$gestPF = mysqli_fetch_assoc($resultGestPF);
// Fermer la connexion
mysqli_close($conn);
// Extraire les données de Sorties et Retour
$sorties_boissons = "";
$sorties_biscuits = "";
$sorties_usager = "";
$retour_boissons = "";
$retour_biscuits = "";
$retour_usager = "";
$sorties_disabled = "";
$retour_disabled = "";
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
    // Désactiver les champs de Sorties si des données existent
    if (!empty($sorties_boissons) || !empty($sorties_biscuits) || !empty($sorties_usager)) {
        $sorties_disabled = "disabled";
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
    // Désactiver les champs de Retour si des données existent
    if (!empty($retour_boissons) || !empty($retour_biscuits) || !empty($retour_usager)) {
        $retour_disabled = "disabled";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
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
                </table>
            </div>
        </div>
    </section>
    <section class="principale2">
        <div class="composition">
            <div class="dashboard-content">
                <section class="infos_Sorties">
                    <div class="ent">
                        <div><h3>Sorties</h3></div>
                    </div><br>
                    <form id="sortiesForm" method="POST" action="get_gestPF.php">
                        <h4>Collation</h4>
                        <table>
                            <tr>
                                <th>Boissons</th>
                                <th><input id="sorties_boissons" name="sorties_boissons" type="number" value="<?php echo htmlspecialchars($sorties_boissons); ?>" <?php echo $sorties_disabled; ?>></th>
                            </tr>
                            <tr>
                                <th>Biscuits</th>
                                <th><input id="sorties_biscuits" name="sorties_biscuits" type="number" value="<?php echo htmlspecialchars($sorties_biscuits); ?>" <?php echo $sorties_disabled; ?>></th>
                            </tr>
                        </table>
                        <h4>Usager</h4>
                        <table>
                            <th><textarea id="sorties_usager" name="sorties_usager" <?php echo $sorties_disabled; ?>><?php echo htmlspecialchars($sorties_usager); ?></textarea></th>
                        </table>
                        <input type="hidden" name="idEvenement" value="<?php echo htmlspecialchars($_GET['id']); ?>">
                        <br>
                        <div class="ent">
                            <div class="div">
                                <input id="sendSorties" class="submit" type="submit" name="sendSorties" value="Enrégistrer" <?php echo $sorties_disabled; ?>>
                            </div>
                        </div>
                    </form>
                </section>
                <br>
                <section class="infos_Sorties">
                    <div class="ent">
                        <div><h3>Retour</h3></div>
                    </div><br>
                    <form id="retourForm" method="POST" action="get_gestPF.php">
                        <h4>Collation</h4>
                        <table>
                            <tr>
                                <th>Boissons</th>
                                <th><input id="retour_boissons" name="retour_boissons" type="number" value="<?php echo htmlspecialchars($retour_boissons); ?>" <?php echo $retour_disabled; ?>></th>
                            </tr>
                            <tr>
                                <th>Biscuits</th>
                                <th><input id="retour_biscuits" name="retour_biscuits" type="number" value="<?php echo htmlspecialchars($retour_biscuits); ?>" <?php echo $retour_disabled; ?>></th>
                            </tr>
                        </table>
                        <h4>Usager</h4>
                        <table>
                            <th><textarea id="retour_usager" name="retour_usager" <?php echo $retour_disabled; ?>><?php echo htmlspecialchars($retour_usager); ?></textarea></th>
                        </table>
                        <input type="hidden" name="idEvenement" value="<?php echo htmlspecialchars($_GET['id']); ?>">
                        <br>
                        <div class="ent">
                            <div class="div">
                                <input id="sendRetour" class="submit" type="submit" name="sendRetour" value="Enrégistrer" <?php echo $retour_disabled; ?>>
                            </div>
                        </div>
                    </form>
                </section>
                <br>
            </div>
        </div>
    </section>
</div>
</body>
</html>
