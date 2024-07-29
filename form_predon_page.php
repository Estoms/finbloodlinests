<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questionnaire Médical</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h2>Questionnaire Médical</h2>
    <form action="traitement_donneur_reponse.php" method="POST"> 
        <table>
            <tr>
                <th>Question</th>
                <th>Oui</th>
                <th>Non</th>
                <th>Date</th>
            </tr>
            <tr>
                <td>
                    <label for="question1">Avez-vous reçu du sang une fois ?</label>
                </td>
                <td>
                    <div class="options">
                        <input type="radio" id="question1_oui" name="question1" value="oui">
                    </div>
                </td>
                <td>
                    <div class="options">
                        <input type="radio" id="question1_non" name="question1" value="non">

                    </div>
                </td>
                <td>
                    <input type="date" id="date_question1" name="date_question1">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="question2">Avez-vous déjà donné du sang une fois ?</label>
                </td>
                <td>
                    <div class="options">
                        <input type="radio" id="question2_oui" name="question2" value="oui">

                    </div>
                </td>
                <td>
                    <div class="options">
                        <input type="radio" id="question2_non" name="question2" value="non">

                    </div>
                </td>
                <td>
                    <input type="date" id="date_question2" name="date_question2">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="question3">Votre dernier don s'est bien déroulé ?</label>
                </td>
                <td>
                    <div class="options">
                        <input type="radio" id="question3_oui" name="question3" value="oui">

                    </div>
                </td>
                <td>
                    <div class="options">
                        <input type="radio" id="question3_non" name="question3" value="non">

                    </div>
                </td>
                <td>
                    <input type="date" id="date_question3" name="date_question3">
                </td>
            </tr>
        </table>

        <h3>Avez-vous souffert ou souffrez-vous de l'une des maladies/symptômes suivants :</h3>
        <table>
            <tr>
                <td><label for="douleur_a_la_poitrine">Douleur à la poitrine </label></td>
                <th><input id="douleur_a_la_poitrine" type="checkbox"></th>
                <td><label for="Épilepsie"> Épilepsie </label></td>
                <th><input id="Épilepsie" type="checkbox"></th>
                <td><label for="Éruption_cutanee">Éruption cutanée </label></td>
                <th><input id="Éruption_cutanee" type="checkbox"></th>
                <td><label for="Infection_sexuelle">Infection sexuelle </label></td>
                <th><input id="Infection_sexuelle" type="checkbox"></th>
            </tr>
            <tr>
                <td><label for="Douleur_articulaire">Douleur articulaire </label></td>
                <th><input type="checkbox"></th>
                <td><label for="Ictere_jaunisse">Ictère jaunisse </label></td>
                <th><input type="checkbox"></th>
                <td><label for="Asthme">Asthme </label></td>
                <th><input type="checkbox"></th>
                <td><label for="Plaie_dans_la_bouche">Plaie dans la bouche </label></td>
                <th><input type="checkbox"></th>
            </tr>
            <tr>
                <td><label for="Anemie">Anémie </label></td>
                <th><input type="checkbox" id="Anemie"></th>
                <td><label for="Maladie_du_sang">Maladie du sang </label></td>
                <th><input type="checkbox" id="Maladie_du_sang"></th>
                <td><label for="Diabete">Diabète </label></td>
                <th><input type="checkbox" id="Diabete"></th>
                <td><label for="Evanouissement">Évanouissement </label></td>
                <th><input type="checkbox" id="Evanouissement"></th>
            </tr>
            <tr>
                <td><label for="Tremblement_membres">Tremblement des membres </label></td>
                <th><input type="checkbox" id="Tremblement_membres"></th>
                <td><label for="Maladie_coeur">Maladie du cœur </label></td>
                <th><input type="checkbox" id="Maladie_coeur"></th>
                <td><label for="Hyper_tension">Hyper tension </label></td>
                <th><input type="checkbox" id="Hyper_tension"></th>
                <td><label for="Urine_sanglante">Urine sanglante </label></td>
                <th><input type="checkbox" id="Urine_sanglante"></th>
            </tr>
            <tr>
                <td><label for="Ganglion">Ganglion </label></td>
                <th><input type="checkbox" id="Ganglion"></th>
                <td><label for="Fatigue_exageree">Fatigue exagérée </label></td>
                <th><input type="checkbox" id="Fatigue_exageree"></th>
                <td><label for="Hemorroides">Hémorroïdes </label></td>
                <th><input type="checkbox" id="Hemorroides"></th>
                <td><label for="Diarrhee">Diarrhée </label></td>
                <th><input type="checkbox" id="Diarrhee"></th>
            </tr>
            <tr>
                <td><label for="Vertige">Vertige </label></td>
                <th><input type="checkbox" id="Vertige"></th>
                <td><label for="Ulcere">Ulcère </label></td>
                <th><input type="checkbox" id="Ulcere"></th>
                <td><label for="Cancer">Cancer </label></td>
                <th><input type="checkbox" id="Cancer"></th>
                <td><label for="Fievre">Fièvre </label></td>
                <th><input type="checkbox" id="Fievre"></th>
            </tr>
            <tr>
                <td><label for="Toux_plus_un_mois">Toux de plus d'un mois </label></td>
                <th><input type="checkbox" id="Toux_plus_un_mois"></th>
                <td><label for="Drepanocytose">Drépanocytose </label></td>
                <th><input type="checkbox" id="Drepanocytose"></th>
            </tr>
            <tr>
                <td><label for="Autre_maladie">Autre maladie (à préciser): </label></td>
                <td colspan="7"><input type="text" id="Autre_maladie" placeholder="......."></td>
            </tr>
        </table>

        <table>
            <tr>
                <th></th>
                <th>Oui</th>
                <th>Non</th>
                <th>Date</th>
            </tr>
            <tr>
                <td>
                    <label for="question4">Avez-vous déjà été une fois opéré, transfusé, ou greffé ?</label>
                </td>
                <th><input type="checkbox" id="question4_oui" name="question4" value="oui"></th>
                <th><input type="checkbox" id="question4_non" name="question4" value="non"></th>
                <th><input type="date" id="date_question4" name="date_question4"></th>
            </tr>
            <tr>
                <td>
                    <label for="question5">Avez-vous été vacciné depuis moins de 3 mois ?</label>
                </td>
                <th><input type="checkbox" id="question5_oui" name="question5" value="oui"></th>
                <th><input type="checkbox" id="question5_non" name="question5" value="non"></th>
                <th><input type="date" id="date_question2" name="date_question5"></th>
            </tr>
            <tr>
                <td>Si oui, quel vaccin :</td>
                <th colspan="3"><input type="text" placeholder="......." id="vaccine_name"></th>
            </tr>
            <tr>
                <td>
                    <label for="question6">Avez-vous pris ou prenez-vous actuellement un (des) médicament(s) ?</label>
                </td>
                <th><input type="checkbox" id="question6_oui" name="question6" value="oui"></th>
                <th><input type="checkbox" id="question6_non" name="question6" value="non"></th>
                <th><input type="date" id="date_question6" name="date_question6"></th>
            </tr>
            <tr>
                <td>Si oui, quel médicament :</td>
                <th colspan="3"><input type="text" placeholder="......." id="medication_name"></th>
            </tr>
            <tr>
                <td>
                    <label for="question7">Avez-vous reçu des soins dentaires il y a moins de 3 mois ?</label>
                </td>
                <th><input type="checkbox" id="question7_oui" name="question7" value="oui"></th>
                <th><input type="checkbox" id="question7_non" name="question7" value="non"></th>
                <th><input type="date" id="date_question4" name="date_question7"></th>
            </tr>
            <tr>
                <td>
                    <label for="question8">Avez-vous changé de partenaire sexuel(le) ?</label>
                </td>
                <th><input type="checkbox" id="question8_oui" name="question8" value="oui"></th>
                <th><input type="checkbox" id="question8_non" name="question8" value="non"></th>
                <th><input type="date" id="date_question8" name="date_question8"></td>
            </tr>
        </table>

        <h3>Vous trouvez-vous dans l'une des situations suivantes</h3>
        <table>
            <tr>
                <td><label for="drogue">Je me drogue</label></td>
                <th><input type="checkbox" id="drogue"></th>
                <td><label for="partenaire_drogue">Mon partenaire se drogue</label></td>
                <th><input type="checkbox" id="partenaire_drogue"></th>
                <td><label for="partenaire_seropositif">Mon partenaire est séropositif</label></td>
                <th><input type="checkbox" id="partenaire_seropositif"></th>
            </tr>

            <tr>
                <td><label for="blessure">J'ai eu une blessure avec un objet déjà utilisé</label></td>
                <th><input type="checkbox" id="blessure"></th>
                <td><label for="homosexuel">Je suis homosexuel</label></td>
                <th><input type="checkbox" id="homosexuel"></th>
                <td><label for="morsure">J'ai été mordu par quelqu'un</label></td>
                <th><input type="checkbox" id="morsure"></th>
            </tr>

            <tr>
                <td><label for="sejour_etranger">Séjour à l'étranger ces 3 derniers mois ?</label></td>
                <th><input type="checkbox" id="sejour_etranger"></th>
                <td><label for="pays_sejour">Si oui, dans quel pays ?</label></td>
                <th><input type="text" id="pays_sejour"></th>
                <td><label for="date_retour">Date de retour</label></td>
                <th><input type="date" id="date_retour"></th>
            </tr>

            <tr>
                <td><label for="scarification">J'ai subi de scarification, de tatouage, ou de piercing</label></td>
                <th><input type="checkbox" id="scarification"></th>
            </tr>
        </table>

        <div id="for_femme">
            <h3>À compléter par les donneurs de sexe féminin</h3>
            <table>
                <tr>
                    <th>Question</th>
                    <th>Oui</th>
                    <th>Non</th>
                    <th>Date</th>
                </tr>
                <tr>
                    <td>
                        <label for="question10">Avez-vous accouché il y a moins de 6 mois ?</label>
                    </td>
                    <td>
                        <div class="options">
                            <input type="radio" id="question10_oui" name="question10" value="oui">
                        </div>
                    </td>
                    <td>
                        <div class="options">
                            <input type="radio" id="question10_non" name="question10" value="non">
                        </div>
                    </td>
                    <td>
                        <input type="date" id="date_question10" name="question10">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="question11">Avez-vous eu une fausse couche ou subi un avortement ?</label>
                    </td>
                    <td>
                        <div class="options">
                            <input type="radio" id="question11_oui" name="question11" value="oui">
                        </div>
                    </td>
                    <td>
                        <div class="options">
                            <input type="radio" id="question11_non" name="question11" value="non">
                        </div>
                    </td>
                    <td>
                        <input type="date" id="date_question11" name="question11">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="question12">Combien de grossesses avez-vous déjà eues ?</label>
                    </td>
                    <td colspan="7"><input type="text" id="resp_question12" name="question12"></td>
                </tr>
                </tr>
                <tr>
                    <td><label for="question13">À quand remontent vos dernières règles ?</label></td>
                    <td colspan="7"><input type="text" id="resp_question13" name="question13"></td>
                </tr>
            </table>
        </div>
        <div class="cont">
            <div class="form-column">
                <label for="nombre_don_ant">Nombre de dons antérieurs</label>
                <input type="number" id="nombre_don_ant" name="nombre_don_ant" requ>

                <label for="nombre_don_annee">Nombre de dons dans l'année</label>
                <input type="number" id="nombre_don_annee" name="nombre_don_annee" requ>

                <label for="poids">Poids (kg)</label>
                <input type="number" id="poids" name="poids" requ>

                <label for="taille">Taille (m)</label>
                <input type="number" id="taille" name="taille" requ step="0.01">

                <label for="temperature">Température (°C)</label>
                <input type="number" id="temperature" name="temperature" requ step="0.1">

                <label for="pools_muqueuses">Pools Muqueuses</label>
                <input type="text" id="pools_muqueuses" name="pools_muqueuses" requ>

                <label for="pools_paumes">Pools Paumes</label>
                <input type="text" id="pools_paumes" name="pools_paumes" requ>

                <label for="pools_peau">Pools Peau</label>
                <input type="text" id="pools_peau" name="pools_peau" requ>

                <label for="pools_bouche">Pools Bouche</label>
                <input type="text" id="pools_bouche" name="pools_bouche" requ>

                <label for="validation_donneur">Donneurs</label>
                <select id="validation_donneur" name="validation_donneur" requ>
                    <option value="donneurs_acceptes">Donneurs acceptés</option>
                    <option value="donneurs_refuses">Donneurs refusés</option>
                </select>

                <label for="volume_prelever">Volume à prélever (cc)</label>
                <input type="number" id="volume_prelever" name="volume_prelever" requ>

                <label for="motif_refus">Motif et durée de refus</label>
                <textarea id="motif_refus" name="motif_refus" rows="4" requ></textarea>

                <label for="examinateur">Examinateur</label>
                <input type="text" id="examinateur" name="examinateur" requ>
            </div>
            <div class="form-column">
                <label for="n_poche">N° de poche</label>
                <input type="text" id="n_poche" name="n_poche" requ>

                <label for="type_poche">Type de poche</label>
                <select id="type_poche" name="type_poche" requ>
                    <option value="simple">Simple</option>
                    <option value="double">Double</option>
                    <option value="triple">Triple</option>
                    <option value="quadruple">Quadruple</option>
                    <option value="ped">Ped</option>
                </select>

                <label for="deroulement_don">Déroulement du Don</label>
                <select id="deroulement_don" name="deroulement_don" requ>
                    <option value="OK">OK</option>
                    <option value="pi">PI</option>
                    <option value="M">M</option>
                </select>

                <label for="debut">Début :</label>
                <input type="datetime-local" id="debut" name="debut" requ>

                <label for="fin">Fin :</label>
                <input type="datetime-local" id="fin" name="fin" requ>

                <label for="autres_infos">Autres informations</label>
                <textarea id="autres_infos" name="autres_infos" rows="4"></textarea>

                <label for="preleveur">Préleveur</label>
                <input type="text" id="preleveur" name="preleveur" requ>
            </div>
        </div>
        <div class="center">
        <button type="submit" name="btn_quest">Envoyer</button>
    </div>
    </form>
    <script>
    function setDateTimeDefaults() {
        const now = new Date();
        const formattedDateTime = now.toISOString().slice(0, 16); // Format YYYY-MM-DDTHH:MM
        document.getElementById('debut').value = formattedDateTime;
        document.getElementById('fin').value = formattedDateTime;
    }
    window.onload = setDateTimeDefaults;
</script>
</body>
</html>