<?php
session_start();
// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure le fichier de connexion à la base de données
    include("connexion.php");

    // Créer une nouvelle connexion à la base de données et vérifier
    $conn = mysqli_connect($servername, $username, $passwordDB, $dbname);
    if (!$conn) {
        echo "<script>alert('La connexion à la base de données a échoué. Veuillez réessayer.');</script>";
    } else {
        // Récupérer les données du formulaire
        $matricule = $_POST['matricule'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $sexe = $_POST['sexe'];
        $adresse = $_POST['adresse'];
        $tel = $_POST['tel'];
        $email = $_POST['email'];
        $poste = $_POST['poste'];
        $idService = $_SESSION['idService'];
        
        // Échapper les caractères spéciaux pour éviter les injections SQL
        $matricule = mysqli_real_escape_string($conn, $matricule);
        $nom = mysqli_real_escape_string($conn, $nom);
        $prenom = mysqli_real_escape_string($conn, $prenom);
        $sexe = mysqli_real_escape_string($conn, $sexe);
        $adresse = mysqli_real_escape_string($conn, $adresse);
        $tel = mysqli_real_escape_string($conn, $tel);
        $email = mysqli_real_escape_string($conn, $email);
        $poste = mysqli_real_escape_string($conn, $poste);
        $idService = mysqli_real_escape_string($conn, $idService);

        // Requête SQL pour insérer les données
        $insert_sql = "INSERT INTO employer (Matricule, nom, prenom, sexe, adresse, tel, email, Poste, idService) 
        VALUES ('$matricule', '$nom', '$prenom', '$sexe', '$adresse', '$tel', '$email', '$poste', '$idService')";
        if (mysqli_query($conn, $insert_sql)) {
            echo "<script>alert('Employé ajouté avec succès.');</script>";
        } else {
            echo "<script>alert('Erreur lors de l\'insertion des données.');</script>";
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
    <link rel="stylesheet" href=".\Style\form_add_employer.css">
    <title>Formulaire Moderne</title>
</head>
<body>
    <div class="container">
        <h2>Ajouter un employé</h2>
        <form id="myForm" method="post" action="form_add_employer.php">
            <div class="form-column">
                <label for="matricule">Matricule</label>
                <input type="text" id="matricule" name="matricule" required>

                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required>

                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" required>

                <label for="sexe">Sexe</label>
                <input type="text" id="sexe" name="sexe" required>
            </div>
            <div class="form-column">
                <label for="adresse">Adresse</label>
                <input type="text" id="adresse" name="adresse" required>

                <label for="tel">Téléphone</label>
                <input type="tel" id="tel" name="tel" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="poste">Poste</label>
                <input type="text" id="poste" name="poste" required>

                <input type="hidden" id="idService" name="idService" value="<?php echo $_SESSION['idService']; ?>">
            </div>
            <input type="submit" value="Envoyer">
        </form>
    </div>
</body>
</html>
