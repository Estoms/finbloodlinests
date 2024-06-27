<?php
session_start();
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
    'type_poche' => 'Donneurs',
    'volume_prelever' => 'Volume à prélever (cc)',
    'motif_refus' => 'Motif et durée de refus',
    'examinateur' => 'Examinateur',
    'n_poche' => 'N° de poche',
    'deroulement_don' => 'Déroulement du Don',
    'debut' => 'Début :',
    'fin' => 'Fin :',
    'autres_infos' => 'Autres informations',
    'preleveur' => 'Préleveur',
];

// Exemples de récupération des données du formulaire et insertion dans la base de données
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
     // Coordonnées de la base de données
     include("connexion.php");
     // Créer une nouvelle connexion à la base de données
    $conn = mysqli_connect($servername, $username, $passwordDB, $dbname);

    if ($conn->connect_error) {
        die("Connexion échouée: " . $conn->connect_error);
    }

    foreach ($questions_map as $key => $question) {
        $response = isset($_POST[$key]) ? $_POST[$key] : '';
        $date = isset($_POST["date_$key"]) ? $_POST["date_$key"] : null;

        
        // Récupérer les données 
        $idDonneur = $_SESSION['idDonneur'];
        $idEvenement = $_SESSION['idEvenement'];
        

        $sql = "INSERT INTO participationdonneur (idQuestion, reponse, date_reponse,idDonneur,idEvenement) VALUES ('$key', '$response', '$date','$idDonneur','$idEvenement')";
        if ($conn->query($sql) === TRUE) {
            echo "Réponse pour $key insérée avec succès.<br>";
        } else {
            echo "Erreur: " . $sql . "<br>" . $conn->error . "<br>";
        }
         // Détruire la variable de session idDonneur
        unset($_SESSION['idDonneur']);
    
        // Rediriger vers form_don_page.php
        header("Location: form_don_page.php?id=$idEvenement");
        exit();
    }

    // Fermer la connexion
    $conn->close();
}
?>
