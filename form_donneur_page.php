<?php
session_start(); // Démarrer la session

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure le fichier de connexion à la base de données
    include("connexion.php");

    // Créer une nouvelle connexion à la base de données
    $conn = mysqli_connect($servername, $username, $passwordDB, $dbname);

    // Vérifier la connexion
    if (!$conn) {
        echo "<script>alert('La connexion à la base de données a échoué. Veuillez réessayer.');</script>";
    } else {
        // Récupérer les données du formulaire
        $numCarteDon = $_POST['numCarteDon'];
        $nomDonneur = $_POST['nomDonneur'];
        $prenomDonneur = $_POST['prenomDonneur'];
        $ageDonneur = $_POST['ageDonneur'];
        $naissDonneur = $_POST['naissDonneur'];
        $lieuNaissDonneur = $_POST['lieuNaissDonneur'];
        $professionDonneur = $_POST['professionDonneur'];
        $typeSang = $_POST['typeSang'];
        $sexe = $_POST['sexe'];
        $situationMatrimoniale = $_POST['situationMatrimoniale'];
        $adresseQuartier = $_POST['adresseQuartier'];
        $adresseMaison = $_POST['adresseMaison'];
        $ethnie = $_POST['ethnie'];
        $niveauEtude = $_POST['niveauEtude'];
        $telPersoDonneur = $_POST['telPersoDonneur'];
        $emailDonneur = $_POST['emailDonneur'];
        $lieuTravail = $_POST['lieuTravail'];
        $telTravail = $_POST['telTravail'];
        $nomContactUrgence = $_POST['nomContactUrgence'];
        $telContactUrgence = $_POST['telContactUrgence'];

        // Échapper les caractères spéciaux pour éviter les injections SQL
        $numCarteDon = mysqli_real_escape_string($conn, $numCarteDon);
        $nomDonneur = mysqli_real_escape_string($conn, $nomDonneur);
        $prenomDonneur = mysqli_real_escape_string($conn, $prenomDonneur);
        $ageDonneur = mysqli_real_escape_string($conn, $ageDonneur);
        $naissDonneur = mysqli_real_escape_string($conn, $naissDonneur);
        $lieuNaissDonneur = mysqli_real_escape_string($conn, $lieuNaissDonneur);
        $professionDonneur = mysqli_real_escape_string($conn, $professionDonneur);
        $typeSang = mysqli_real_escape_string($conn, $typeSang);
        $sexe = mysqli_real_escape_string($conn, $sexe);
        $situationMatrimoniale = mysqli_real_escape_string($conn, $situationMatrimoniale);
        $adresseQuartier = mysqli_real_escape_string($conn, $adresseQuartier);
        $adresseMaison = mysqli_real_escape_string($conn, $adresseMaison);
        $ethnie = mysqli_real_escape_string($conn, $ethnie);
        $niveauEtude = mysqli_real_escape_string($conn, $niveauEtude);
        $telPersoDonneur = mysqli_real_escape_string($conn, $telPersoDonneur);
        $emailDonneur = mysqli_real_escape_string($conn, $emailDonneur);
        $lieuTravail = mysqli_real_escape_string($conn, $lieuTravail);
        $telTravail = mysqli_real_escape_string($conn, $telTravail);
        $nomContactUrgence = mysqli_real_escape_string($conn, $nomContactUrgence);
        $telContactUrgence = mysqli_real_escape_string($conn, $telContactUrgence);

        // Rechercher si le donneur existe déjà
        $query = "SELECT idDonneur FROM donneur WHERE numCarteDon='$numCarteDon' OR (nomDonneur='$nomDonneur' AND prenomDonneur='$prenomDonneur' AND sexe='$sexe' AND naissDonneur='$naissDonneur')";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            // Le donneur existe, mise à jour des informations
            $row = mysqli_fetch_assoc($result);
            $idDonneur = $row['idDonneur'];
            $update_sql = "UPDATE donneur SET 
                ageDonneur='$ageDonneur', 
                lieuNaissDonneur='$lieuNaissDonneur', 
                professionDonneur='$professionDonneur', 
                typeSang='$typeSang', 
                situationMatrimoniale='$situationMatrimoniale', 
                adresseQuartier='$adresseQuartier', 
                adresseMaison='$adresseMaison', 
                ethnie='$ethnie', 
                niveauEtude='$niveauEtude', 
                telPersoDonneur='$telPersoDonneur', 
                emailDonneur='$emailDonneur', 
                lieuTravail='$lieuTravail', 
                telTravail='$telTravail', 
                nomContactUrgence='$nomContactUrgence', 
                telContactUrgence='$telContactUrgence' 
                WHERE idDonneur='$idDonneur'";
            if (mysqli_query($conn, $update_sql)) {
                $_SESSION['idDonneur'] = $idDonneur;
                echo "<script>alert('Mise à jour réussie.'); window.location.href='form_predon_page.php';</script>";
            } else {
                echo "<script>alert('Erreur lors de la mise à jour. Veuillez réessayer.');</script>";
            }
        } else {
            // Le donneur n'existe pas, insertion d'un nouvel enregistrement
            $insert_sql = "INSERT INTO donneur (numCarteDon, nomDonneur, prenomDonneur, ageDonneur, naissDonneur, lieuNaissDonneur, professionDonneur, typeSang, sexe, situationMatrimoniale, adresseQuartier, adresseMaison, ethnie, niveauEtude, telPersoDonneur, emailDonneur, lieuTravail, telTravail, nomContactUrgence, telContactUrgence) 
                VALUES ('$numCarteDon', '$nomDonneur', '$prenomDonneur', '$ageDonneur', '$naissDonneur', '$lieuNaissDonneur', '$professionDonneur', '$typeSang', '$sexe', '$situationMatrimoniale', '$adresseQuartier', '$adresseMaison', '$ethnie', '$niveauEtude', '$telPersoDonneur', '$emailDonneur', '$lieuTravail', '$telTravail', '$nomContactUrgence', '$telContactUrgence')";
            if (mysqli_query($conn, $insert_sql)) {
                $idDonneur = mysqli_insert_id($conn);
                $_SESSION['idDonneur'] = $idDonneur;
                echo "<script>alert('Enregistrement réussi.'); window.location.href='form_predon_page.php';</script>";
            } else {
                echo "<script>alert('Erreur lors de l'enregistrement. Veuillez réessayer.');</script>";
            }
        }

        // Fermer la connexion à la base de données
        mysqli_close($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Donneur</title>
 
    <link rel="stylesheet" href=".\Style\form_donneur_page.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body style="background-color: #DF3131;">
    <div class="container">
        <div class="header">
            <img src=".\Images\person-icon.png" alt="Person icon">
            <h2>Identité du donneur</h2>
        </div>
        <form id="myForm" method="post" action="form_donneur_page.php">

        <div class="form-column">
            <label for="numCarteDon">Numéro de la carte de donneur:</label>
            <input type="text" id="numCarteDon" name="numCarteDon">

            <label for="nomDonneur">Nom:</label>
            <input type="text" id="nomDonneur" name="nomDonneur"required>

            <label for="prenomDonneur">Prénoms:</label>
            <input type="text" id="prenomDonneur" name="prenomDonneur"required>

            <label for="ageDonneur">Age:</label>
            <input type="number" id="ageDonneur" name="ageDonneur"required>

            <label for="naissDonneur">Né(e) le:</label>
            <input type="date" id="naissDonneur" name="naissDonneur"required>

            <label for="lieuNaissDonneur">Lieu de naissance:</label>
            <input type="text" id="lieuNaissDonneur" name="lieuNaissDonneur"required>

            <label for="professionDonneur">Profession:</label>
            <input type="text" id="professionDonneur" name="professionDonneur" required>

            <label for="typeSang">Groupe sanguin:</label>
            <select id="typeSang" name="typeSang">
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>


            <label for="sexe">Sexe:</label>
            <select id="sexe" name="sexe">
                <option value="homme">Homme</option>
                <option value="femme">Femme</option>
            </select>

            <label for="situationMatrimoniale">Situation matrimoniale:</label>
            <select id="situationMatrimoniale" name="situationMatrimoniale">
                <option value="Marie(e)">Marie(e)</option>
                <option value="Célibataire">Célibataire</option>
                <option value="Veuf(ve)">Veuf(ve)</option>
            </select>
        </div>
        <div class="form-column">

            <label for="adresseQuartier">Adresse - Quartier:</label>
            <input type="text" id="adresseQuartier" name="adresseQuartier"required>

            <label for="adresseMaison">Adresse - Maison:</label>
            <input type="text" id="adresseMaison" name="adresseMaison"required>

            <label for="ethnie">Ethnie:</label>
            <input type="text" id="ethnie" name="ethnie"required>

            <label for="niveauEtude">Niveau d'étude:</label>
            <input type="text" id="niveauEtude" name="niveauEtude"required>

            <label for="telPersoDonneur">Téléphone personnel:</label>
            <input type="tel" id="telPersoDonneur" name="telPersoDonneur"required>

            <label for="emailDonneur">Email:</label>
            <input type="email" id="emailDonneur" name="emailDonneur"required>

            <label for="lieuTravail">Lieu de travail:</label>
            <input type="text" id="lieuTravail" name="lieuTravail"required>

            <label for="telTravail">Téléphone travail:</label>
            <input type="tel" id="telTravail" name="telTravail"required>

            <label for="nomContactUrgence">Nom contact d'urgence:</label>
            <input type="text" id="nomContactUrgence" name="nomContactUrgence"required>

            <label for="telContactUrgence">Téléphone contact d'urgence:</label>
            <input type="tel" id="telContactUrgence" name="telContactUrgence"required>
        </div>

            <button type="submit">Soumettre</button>
        </form>
    </div>

    <script src=".\JS\form_donneur_page_script.js"></script>
</body>
</html>
