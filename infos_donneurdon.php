<?php
session_start();
// Coordonnées de la base de données
include("connexion.php");
// Création d'une nouvelle connexion facultatif, je vais le supprimer dans la version suivante
$conn = mysqli_connect($servername, $username, $passwordDB, $dbname);
// Vérification de la connexion
if (!$conn) {
    die("La connexion a échoué: " . mysqli_connect_error());
}
$_SESSION['idEvenement'] = $_GET['id'];
// Récupérer les données de la session et des liens
$idService = $_SESSION['idService'];
$idEvenement = $_GET['id'];
$id_donneur = $_GET['id_donneur'];

$sql = "SELECT * FROM service WHERE idService = '$idService'";
$result = mysqli_query($conn, $sql);
$organisme = mysqli_fetch_assoc($result);
if (!$result) {
    die("Erreur lors de l'exécution de la requête : " . mysqli_error($conn));
}
// Récupérer tous les infos de lévenements
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
// Récupérer les infos sur les donneurs
$sqldonneur = "SELECT * FROM donneur INNER JOIN participationdonneur ON donneur.idDonneur = participationdonneur.idDonneur WHERE participationdonneur.idEvenement = '$idEvenement' AND donneur.idDonneur='$id_donneur' GROUP BY participationdonneur.idDonneur;";
$result_donneur = mysqli_query($conn, $sqldonneur);
if (!$result_donneur) {
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
            <a href="#" class="logo"></a>
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
                <p><strong><span>Donneur</span></strong></p>
            </div>
            <div class="composition">
                <div class="dashboard-content">
                    <table>
                        <?php
                        while ($donneur = mysqli_fetch_assoc($result_donneur)) {
                            echo "
                                    <tr>
                                        <th>Num Carte Don</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Âge</th>
                                        <th>Date de Naissance</th>
                                    </tr>
                                    <tr>
                                    <td>" . $donneur['numCarteDon'] . "</td>
                                    <td>" . $donneur['nomDonneur'] . "</td>
                                    <td>" . $donneur['prenomDonneur'] . "</td>
                                    <td>" . $donneur['ageDonneur'] . "</td>
                                    <td>" . $donneur['naissDonneur'] . "</td>
                                    </tr>

                                    <tr>
                                        <th>Lieux de Naissance</th>
                                        <th>Profession Donneur</th>
                                        <th>Groupe Sanguin</th>
                                        <th>Sexe</th>
                                        <th>Situation Matrimoniale</th>
                                    </tr>
                                    <tr>
                                    <td>" . $donneur['lieuNaissDonneur'] . "</td>
                                    <td>" . $donneur['professionDonneur'] . "</td>
                                    <td>" . $donneur['typeSang'] . "</td>
                                    <td>" . $donneur['sexe'] . "</td>
                                    <td>" . $donneur['situationMatrimoniale'] . "</td>
                                    </tr>

                                    <tr>
                                        <th>Quartier</th>
                                        <th>Maison</th>
                                        <th>Ethnie</th>
                                        <th>Niveau d'Etude</th>
                                        <th>Téléphone</th>
                                    </tr>
                                    <tr>
                                    <td>" . $donneur['adresseQuartier'] . "</td>
                                    <td>" . $donneur['adresseMaison'] . "</td>
                                    <td>" . $donneur['ethnie'] . "</td>
                                    <td>" . $donneur['niveauEtude'] . "</td>
                                    <td>" . $donneur['telPersoDonneur'] . "</td>
                                    </tr>

                                    <tr>
                                        <th>Mail</th>
                                        <th>Lieu de Travail</th>
                                        <th>Téléphone Travail</th>
                                        <th>Contact d'Urgence</th>
                                        <th>Teléphone Personne</th>
                                    </tr>
                                    <tr>
                                    <td>" . $donneur['emailDonneur'] . "</td>
                                    <td>" . $donneur['lieuTravail'] . "</td>
                                    <td>" . $donneur['telTravail'] . "</td>
                                    <td>" . $donneur['nomContactUrgence'] . "</td>
                                    <td>" . $donneur['telContactUrgence'] . "</td>
                                    </tr>
                            ";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </section>
        <section class="principale2">
            <div class="composition">
                <div class="dashboard-content">
                    <div>
                        <h3>Dons</h3>
                    </div>
                    <table>
                        <tr>
                            <td><?php echo $evenements['nomEvenement']; ?></td>
                            <td><?php echo $evenements['dateEvenement']; ?></td>
                            <td><?php echo $evenements['heureDebut']; ?></td>
                            <td><?php echo $evenements['heureFin']; ?></td>
                            <td><?php echo $evenements['Description']; ?></td>
                            <td><?php echo $lieu['lieu']; ?></td>
                        </tr>
                    </table> <br>
                    <div>
                        <?php
                        include("connexion.php");
                        $conn = mysqli_connect($servername, $username, $passwordDB, $dbname);
                        if (!$conn) {
                            die("La connexion a échoué: " . mysqli_connect_error());
                        }
                        $idEvenement = $_GET['id'];
                        $id_donneur = $_GET['id_donneur'];
                        // Tableau associatif pour mapper les idQuestion aux questions du formulaire
                        $questions_map = [
                            'question1' => 'Avez-vous reçu du sang une fois ?',
                            'question2' => 'Avez-vous déjà donné du sang une fois ?',
                            'question3' => 'Votre dernier don s\'est bien déroulé ?',
                            'douleur_a_la_poitrine' => 'Douleur à la poitrine',
                            'Épilepsie' => 'Épilepsie',
                            'Éruption_cutanee' => 'Éruption cutanée',
                            'Infection_sexuelle' => 'Infection sexuelle',
                            'Douleur_articulaire' => 'Douleur articulaire',
                            'Ictere_jaunisse' => 'Ictère jaunisse',
                            'Asthme' => 'Asthme',
                            'Plaie_dans_la_bouche' => 'Plaie dans la bouche',
                            'Anemie' => 'Anémie',
                            'Maladie_du_sang' => 'Maladie du sang',
                            'Diabete' => 'Diabète',
                            'Evanouissement' => 'Évanouissement',
                            'Tremblement_membres' => 'Tremblement des membres',
                            'Maladie_coeur' => 'Maladie du cœur',
                            'Hyper_tension' => 'Hypertension',
                            'Urine_sanglante' => 'Urine sanglante',
                            'Ganglion' => 'Ganglion',
                            'Fatigue_exageree' => 'Fatigue exagérée',
                            'Hemorroides' => 'Hémorroïdes',
                            'Diarrhee' => 'Diarrhée',
                            'Vertige' => 'Vertige',
                            'Ulcere' => 'Ulcère',
                            'Cancer' => 'Cancer',
                            'Fievre' => 'Fièvre',
                            'Toux_plus_un_mois' => 'Toux de plus d\'un mois',
                            'Drepanocytose' => 'Drépanocytose',
                            'Autre_maladie' => 'Autre maladie (à préciser)',
                            'question4' => 'Avez-vous déjà été une fois opéré, transfusé, ou greffé ?',
                            'question5' => 'Avez-vous été vacciné depuis moins de 3 mois ?',
                            'vaccine_name' => 'Si oui, quel vaccin :',
                            'question6' => 'Avez-vous pris ou prenez-vous actuellement un (des) médicament(s) ?',
                            'medication_name' => 'Si oui, quel médicament :',
                            'question7' => 'Avez-vous reçu des soins dentaires il y a moins de 3 mois ?',
                            'question8' => 'Avez-vous changé de partenaire  sexuel(le) ?',
                            'drogue' => 'Je me drogue',
                            'partenaire_drogue' => 'Mon partenaire se drogue',
                            'partenaire_seropositif' => 'Mon partenaire est séropositif',
                            'blessure' => 'J\'ai eu une blessure avec un objet déjà utilisé',
                            'homosexuel' => 'Je suis homosexuel',
                            'morsure' => 'J\'ai été mordu par quelqu\'un',
                            'sejour_etranger' => 'Séjour à l\'étranger ces 3 derniers mois ?',
                            'pays_sejour' => 'Si oui, dans quel pays ?',
                            'date_retour' => 'Date de retour',
                            'scarification' => 'J\'ai subi de scarification, de tatouage, ou de piercing',
                            'question10' => 'Avez-vous accouché il y a moins de 6 mois ?',
                            'question11' => 'Avez-vous eu une fausse couche ou subi un avortement ?',
                            'question12' => 'Combien de grossesses avez-vous déjà eues ?',
                            'question13' => 'À quand remontent vos dernières règles ?',
                            'nombre_don_ant' => 'Nombre de dons antérieurs',
                            'nombre_don_annee' => 'Nombre de dons dans l\'année',
                            'poids' => 'Poids (kg)',
                            'taille' => 'Taille (m)',
                            'temperature' => 'Température (°C)',
                            'pools_muqueuses' => 'Pools Muqueuses',
                            'pools_paumes' => 'Pools Paumes',
                            'pools_peau' => 'Pools Peau',
                            'pools_bouche' => 'Pools Bouche',
                            'validation_donneur' => 'validation du Donneur',
                            'volume_prelever' => 'Volume à prélever (cc)',
                            'motif_refus' => 'Motif et durée de refus',
                            'examinateur' => 'Examinateur',
                            'n_poche' => 'N° de poche',
                            'type_poche' => 'Type de Poche',
                            'deroulement_don' => 'Déroulement du Don',
                            'debut' => 'Début :',
                            'fin' => 'Fin :',
                            'autres_infos' => 'Autres informations',
                            'preleveur' => 'Préleveur',
                        ];
                        // Requête pour récupérer les réponses de la base de données
                        $sql = "SELECT idQuestion, reponse, date_reponse FROM participationdonneur WHERE idDonneur = '$id_donneur' AND idEvenement = '$idEvenement'";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            echo "<table border='2'>";
                            echo "<tr><th>Question</th><th>Réponse</th><th>Date</th></tr>";
                            while ($row = $result->fetch_assoc()) {
                                $idQuestion = $row['idQuestion'];
                                $reponse = $row['reponse'];
                                $date_reponse = $row['date_reponse'];
                                // Ne pas afficher les réponses vides ou les dates invalides
                                if ($reponse === '') {
                                    continue;
                                }
                                // Récupérer la question correspondante
                                $question = isset($questions_map[$idQuestion]) ? $questions_map[$idQuestion] : $idQuestion;
                                echo "<tr>";
                                echo "<td>$question</td>";
                                echo "<td>$reponse</td>";
                                if ($date_reponse === '0000-00-00') {
                                    echo "<td></td>";
                                } else {
                                    echo "<td>$date_reponse</td>";
                                }
                                echo "</tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "Aucune réponse trouvée pour ce donneur et cet événement.";
                        }
                        mysqli_close($conn);
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>