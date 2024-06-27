<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Participants</title>
 
    <link rel="stylesheet" href=".\Style\form_participant_page.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h2>Participants</h2>
        <p><strong><span>
            <?php 
                if(isset($_GET['nomEvenement'])) {
                    $nomEvenement = $_GET['nomEvenement'];
                    echo $nomEvenement;
                }
            ?>
        </span></strong></p>
        <table id="participants-table">
            <thead>
                <tr>
                    <th>Nom du participant</th>
                    <th>Téléphone</th>
                    <th>Commentaires</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" name="nomParticipant[]" required></td>
                    <td><input type="tel" name="telephoneParticipant[]" required></td>
                    <td><input type="text" name="commentaires[]"></td>
                </tr>
            </tbody>
        </table>
        <button id="add-row-btn">Ajouter un participant</button>
        <button id="submit-btn">Soumettre</button>
    </div>
    
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const addRowBtn = document.getElementById("add-row-btn");
        const submitBtn = document.getElementById("submit-btn");
        const tableBody = document.querySelector("#participants-table tbody");

        // Ajouter une ligne au tableau
        addRowBtn.addEventListener("click", function() {
            const newRow = `
                <tr>
                    <td><input type="text" name="nomParticipant[]" required></td>
                    <td><input type="tel" name="telephoneParticipant[]" required></td>
                    <td><input type="text" name="commentaires[]"></td>
                </tr>
            `;
            tableBody.insertAdjacentHTML("beforeend", newRow);
        });

        // Soumettre le formulaire
        submitBtn.addEventListener("click", function() {
            const participants = tableBody.querySelectorAll("tr");
            const formData = [];
            participants.forEach(function(participant) {
                const nom = participant.querySelector("input[name='nomParticipant[]']").value;
                const telephone = participant.querySelector("input[name='telephoneParticipant[]']").value;
                const commentaire = participant.querySelector("input[name='commentaires[]']").value;
                const idEvenement = <?php echo isset($_GET['idEvenement']) ? json_encode($_GET['idEvenement']) : "null"; ?>;
                formData.push({ nom: nom, telephone: telephone, commentaire: commentaire, idEvenement: idEvenement });
            });
            
            // Envoyer les données au script PHP pour insertion dans la base de données
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "<?php echo $_SERVER['PHP_SELF']; ?>", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Traitement réussi
                        alert("Enregistrement réussi !");
                        window.location.href = "formulaire_evenement.page.php";
                    } else {
                        // Erreur lors du traitement
                        alert("Erreur lors de l'enregistrement. Veuillez réessayer.");
                    }
                }
            };
            xhr.send(JSON.stringify(formData));
        });
    });
    </script>
    <?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coordonnées de la base de données
    include("connexion.php");

    // Récupérer les données envoyées par la requête AJAX
    $data = json_decode(file_get_contents("php://input"), true);

    // Créer une nouvelle connexion à la base de données
    $conn = mysqli_connect($servername, $username, $passwordDB, $dbname);

    // Vérifier la connexion
    if (!$conn) {
        http_response_code(500);
        exit("La connexion à la base de données a échoué. Veuillez réessayer.");
    }

    // Préparation de la requête SQL d'insertion
    $sql = "INSERT INTO participation (participantNom, telephoneParticipant, commentaires, idEvenement) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        http_response_code(500);
        exit("Erreur lors de la préparation de la requête SQL. Veuillez réessayer.");
    }

    // Exécuter l'insertion pour chaque participant
    foreach ($data as $participant) {
        mysqli_stmt_bind_param($stmt, "sssi", $participant['nom'], $participant['telephone'], $participant['commentaire'], $participant['idEvenement']);
        if (!mysqli_stmt_execute($stmt)) {
            http_response_code(500);
            exit("Erreur lors de l'insertion des données. Veuillez réessayer.");
        }
    }

    // Fermer la connexion à la base de données
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    // Réponse réussie
    http_response_code(200);
    exit("success");
}
?>

</body>
</html>
