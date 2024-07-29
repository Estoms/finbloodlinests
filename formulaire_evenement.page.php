<?php
session_start();
// Vérifier si l'utilisateur est connecté, sinon le rediriger vers la page de connexion
if (!isset($_SESSION['idService'])) {
    header("Location: connexionOrganisme.php");
    exit();
}
// Vérifier si l'utilisateur a cliqué sur le lien de déconnexion assure la déconnexion de l'utilisateur
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: connexionOrganisme.php");
    exit();
}
if (isset($_POST["btn_add_events"])) {
    // Coordonnées de la base de données
    include("connexion.php");
    //creation d'une nouvelle connexion facultatif, je vais supprimer dans la prochaine version
    $conn = mysqli_connect($servername, $username, $passwordDB, $dbname);
    if (!$conn) {
        die("La connexion a échoué: " . mysqli_connect_error());
    }
    // Récupérer les données du formulaire
    $nomEvenement = $_POST["nomEvenement"];
    $dateEvenement = $_POST["dateEvenement"];
    $heureDebut = $_POST["heureDebut"];
    $heureFin = $_POST["heureFin"];
    $description = $_POST["description"];
    $adresse = $_POST["adresse"];
    $idService = $_SESSION["idService"];
    $coordonneeGeographique = $_POST["coordonneeGeographique"];
    $dateCreation = date("Y-m-d");

    // Échapper les valeurs pour éviter les injections SQL
    $nomEvenement = mysqli_real_escape_string($conn, $nomEvenement);
    $dateEvenement = mysqli_real_escape_string($conn, $dateEvenement);
    $heureDebut = mysqli_real_escape_string($conn, $heureDebut);
    $heureFin = mysqli_real_escape_string($conn, $heureFin);
    $description = mysqli_real_escape_string($conn, $description);
    $adresse = mysqli_real_escape_string($conn, $adresse);

    if ($dateEvenement < $dateCreation) {
        echo "La date de l'événement ne peut pas être antérieure à la date de création.";
        exit();
    }

    // Insérer le lieu dans la table Lieu
    $insertLieuQuery = "INSERT INTO lieu (lieu, coordonneeGeographique) VALUES ('$adresse', '$coordonneeGeographique')";
    if (mysqli_query($conn, $insertLieuQuery)) {
        // Récupérer l'ID du lieu inséré
        $idLieu = mysqli_insert_id($conn);
        // Insérer l'événement dans la table Evenement
        $insertEventQuery = "INSERT INTO evenement (nomEvenement, dateEvenement, heureDebut, heureFin, Description, idLieu, idService, isActive) VALUES ('$nomEvenement', '$dateEvenement', '$heureDebut', '$heureFin', '$description', '$idLieu', '$idService', 1)";
        if (mysqli_query($conn, $insertEventQuery)) {
            // Récupérer l'ID de l'événement nouvellement créé
            $idEvenement = mysqli_insert_id($conn);
            // Rediriger vers la page de création de participant avec l'ID de l'événement comme paramètre
            header("Location: form_participant_page.php?idEvenement=$idEvenement&nomEvenement=$nomEvenement");
            exit();
        } else {
            echo "Erreur lors de l'insertion de l'événement: " . mysqli_error($conn);
        }
    } else {
        echo "Erreur lors de l'insertion du lieu: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Création d'événement</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href=".\Style\forms_events_page.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="">
    </script>
</head>

<body style="background-color: #841c1c;">
    <div class="container">
        <ul>
            <li><a href="javascript:history.go(-1);">Retour</a></li>
        </ul>
        <div class="envent_info">
            <div class="confirmation-heading">
                <h2>Création d'événement</h2>
            </div>

            <form action="formulaire_evenement.page.php" method="POST">
                <label for="nomEvenement">Nom de l'événement:</label>
                <input type="text" id="nomEvenement" name="nomEvenement" required><br><br>

                <label for="dateEvenement">Date de l'événement:</label>
                <input type="date" id="dateEvenement" name="dateEvenement" required><br><br>

                <label for="heureDebut">Heure de début:</label>
                <input type="time" id="heureDebut" name="heureDebut" required><br><br>

                <label for="heureFin">Heure de fin:</label>
                <input type="time" id="heureFin" name="heureFin" required><br><br>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" cols="50" required></textarea><br><br>

                <label for="adresse">Adresse :</label> <br>
                <input type="text" id="adresse" name="adresse" required>
                <input type="hidden" id="coordonneeGeographique" name="coordonneeGeographique" required>

                <input type="submit" name="btn_add_events" value="Créer l'événement">
            </form>
        </div>
        <div class="eventparticipant">
        </div>
    </div>

    <div id="map">
        <div id="search-form">
            <input type="text" id="search-input" placeholder="Entrez une ville">
            <button type="button" onclick="searchCity()">Rechercher</button>
        </div>
    </div>

    <script>
        function updateMapWithPosition(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            map.setView([latitude, longitude], 13);
            L.marker([latitude, longitude]).addTo(map);
            updateAddress(L.latLng(latitude, longitude));
            document.getElementById('coordonneeGeographique').value = latitude + ',' + longitude;
        }

        function geocodeLatLng(latlng) {
            var url = 'https://nominatim.openstreetmap.org/reverse?format=json&lat=' + latlng.lat + '&lon=' + latlng.lng;

            return fetch(url)
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('La requête a échoué. Code de réponse : ' + response.status);
                    }
                    return response.json();
                })
                .then(function(data) {
                    return data.display_name;
                })
                .catch(function(error) {
                    console.error('Erreur lors du géocodage :', error);
                    return '';
                });
        }

        function updateAddress(address) {
            document.getElementById('adresse').value = address;
        }


        var map = L.map('map');
        map.setView([51.505, -0.09], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Événement de clic sur la carte pour obtenir la localisation
        map.on('click', function(e) {
            var latlng = e.latlng;
            geocodeLatLng(latlng)
                .then(function(address) {
                    updateAddress(address);
                    document.getElementById('coordonneeGeographique').value = latlng.lat + ',' + latlng.lng;
                })
                .catch(function(error) {
                    console.error('Erreur lors du géocodage :', error);
                });
        });

        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(updateMapWithPosition);
        } else {
            console.log("La géolocalisation n'est pas disponible sur ce navigateur.");
        }

        function searchCity() {
            var searchInput = document.getElementById('search-input').value;
            if (searchInput.trim() !== '') {
                var searchUrl = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(searchInput);

                fetch(searchUrl)
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error('La recherche a échoué. Code de réponse : ' + response.status);
                        }
                        return response.json();
                    })
                    .then(function(data) {
                        if (data.length > 0) {
                            var city = data[0];
                            var latitude = parseFloat(city.lat);
                            var longitude = parseFloat(city.lon);

                            map.setView([latitude, longitude], 13);
                            L.marker([latitude, longitude]).addTo(map);
                        } else {
                            alert('Aucun résultat trouvé pour la ville spécifiée.');
                        }
                    })
                    .catch(function(error) {
                        console.error('Erreur lors de la recherche de ville :', error);
                    });
            }
        }
    </script>
</body>

</html>