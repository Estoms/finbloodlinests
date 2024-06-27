<?php
session_start();
// Coordonnées de la base de données
include("connexion.php");
// Création d'une nouvelle connexion


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
// Récupérer tous les infos de lévenements
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


mysqli_close($conn);
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
        <a href="#" class="logo"></a>
        <div id="menu-icon"><span class="animate" style="--i:2;"></span></div>
        <nav class="navbar">
            <a class="active" href="#"></a>
            <a class="active" href="#"></a>
            <a href="javascript:history.go(-1);">Retour</a>
        </nav>
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
            <section class="infos_Sorties">
                <div class="ent">
                    <div><h3>Sorties</h3></div>
                </div><br>
                <form id="sortiesForm" method="POST" action="get_gestPF.php">
                    <h4>Collation</h4>
                    <table>
                        <tr>
                            <th>Boissons</th> <th><input id="sorties_boissons" name="sorties_boissons" type="number"></th>
                        </tr>
                        <tr>
                            <th>Biscuits</th> <th><input id="sorties_biscuits" name="sorties_biscuits" type="number"></th>
                        </tr>
                    </table>
                    <h4>Usager</h4>
                    <table>
                        <th><textarea id="sorties_usager" name="sorties_usager"></textarea></th>
                    </table>
                    <input type="hidden" name="idEvenement" value ="<?php echo htmlspecialchars($_GET['id']); ?>">
                    <br>
                    <div class="ent">
                        <div class="div">
                            <input id="sendSorties" class="submit" type="submit" name="sendSorties" value="Enrégistrer">
                        </div>
                    </div>
                </form>
            </section >
            <br>
            <section class="infos_Sorties">
                <div class="ent">
                    <div><h3>Retour</h3></div>
                </div><br>
                <form id="retourForm" method="POST" action="get_gestPF.php">
                    <h4>Collation</h4>
                    <table>
                        <tr>
                            <th>Boissons</th> <th><input id="retour_boissons" name="retour_boissons" type="number"></th>
                        </tr>
                        <tr>
                            <th>Biscuits</th> <th><input id="retour_biscuits" name="retour_biscuits" type="number"></th>
                        </tr>
                    </table>
                    <h4>Usager</h4>
                    <table>
                        <th><textarea id="retour_usager" name="retour_usager"></textarea></th>
                    </table>
                    <input type="hidden" name="idEvenement" value ="<?php echo htmlspecialchars($_GET['id']); ?>">
                    <br>
                    <div class="ent">
                        <div class="div">
                            <input id="sendRetour" class="submit" type="submit" name="sendRetour" value="Enrégistrer">
                        </div>
                    </div>
                </form>
            </section>
            <br>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
    const idEvenement = "<?php echo htmlspecialchars($_GET['id']); ?>";

    fetch(`get_gestPF.php?id=${idEvenement}`)
        .then(response => response.json())
        .then(data => {
            if (data && Object.keys(data).length > 0) {
                if (data.Sorties) {
                    const sorties = data.Sorties.match(/Boisson\((\d+)\)\|Biscuits\((\d+)\)\]Usager\[(\w+)\]/);
                    if (sorties) {
                        document.getElementById('sorties_boissons').value = sorties[1];
                        document.getElementById('sorties_biscuits').value = sorties[2];
                        document.getElementById('sorties_usager').value = sorties[3];
                        document.getElementById('sorties_boissons').disabled = true;
                        document.getElementById('sorties_biscuits').disabled = true;
                        document.getElementById('sorties_usager').disabled = true;
                        document.getElementById('sendSorties').disabled = true;
                    }
                }
                if (data.Retour) {
                    const retour = data.Retour.match(/Boisson\((\d+)\)\|Biscuits\((\d+)\)\]Usager\[(\w+)\]/);
                    if (retour) {
                        document.getElementById('retour_boissons').value = retour[1];
                        document.getElementById('retour_biscuits').value = retour[2];
                        document.getElementById('retour_usager').value = retour[3];
                        document.getElementById('retour_boissons').disabled = true;
                        document.getElementById('retour_biscuits').disabled = true;
                        document.getElementById('retour_usager').disabled = true;
                        document.getElementById('sendRetour').disabled = true;
                    }
                }
            }
        });
});
</script>
</div>
</body>
</html>