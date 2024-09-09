CREATE TABLE IF NOT EXISTS `lieu` (
  `idLieu` int NOT NULL AUTO_INCREMENT,
  `lieu` varchar(50) NOT NULL,
  `coordonneeGeographique` varchar(50)  NOT NULL,
  PRIMARY KEY (`idLieu`)
)



CREATE TABLE questions (
    idQuestion INT AUTO_INCREMENT PRIMARY KEY,
    question_text TEXT NOT NULL,
    response_type ENUM('text', 'checkbox', 'date', 'select', 'textarea') NOT NULL,
);

CREATE TABLE IF NOT EXISTS `service` (
  `idService` varchar(150)  NOT NULL ,
  `nomOrganisme` varchar(150)  NOT NULL,
  `addOrganisme` varchar(50)  NOT NULL,
  `ville` varchar(50)  NOT NULL,
  `telOrganisme` varchar(50)  NOT NULL,
  `mailOrganisme` varchar(50)  NOT NULL,
  `passwordOrganisme` varchar(50) NOT NULL,
  PRIMARY KEY (`idService`)
) 


CREATE TABLE IF NOT EXISTS `utilisateur` (
  `idUtilisateur` int  COLLATE utf8mb4_unicode_ci NOT NULL AUTO_INCREMENT,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `motDePasse` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Badge` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idUtilisateur`)
) ; 


CREATE TABLE IF NOT EXISTS `employer` (
  `idEmployer` int COLLATE utf8mb4_unicode_ci NOT NULL AUTO_INCREMENT,
  `Matricule` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sexe` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tel` int DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Poste` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`idEmployer`),
  UNIQUE KEY `Matricule` (`Matricule`)
)



CREATE TABLE IF NOT EXISTS `evenement` (
  `idEvenement` int NOT NULL AUTO_INCREMENT,
  `nomEvenement` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isActive` tinyint(1) NOT NULL,
  `dateEvenement` date NOT NULL,
  `heureDebut` time NOT NULL,
  `heureFin` time DEFAULT NULL,
  `Description` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idLieu` int NOT NULL,
  `idService` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`idEvenement`),
  KEY `idLieu` (`idLieu`),
  KEY `idService` (`idService`)
) 



CREATE TABLE `donneur` (
  `idDonneur` int NOT NULL AUTO_INCREMENT,
  `numCarteDon` varchar(255)  DEFAULT NULL UNIQUE,
  `nomDonneur` varchar(255)  NOT NULL,
  `prenomDonneur` varchar(255)  NOT NULL,
  `ageDonneur` int NOT NULL,
  `naissDonneur` date NOT NULL,
  `lieuNaissDonneur` varchar(255)  NOT NULL,
  `professionDonneur` varchar(255)  NOT NULL,
  `typeSang` varchar(5)  NOT NULL,
  `sexe` varchar(10)  NOT NULL,
  `situationMatrimoniale` varchar(20)  NOT NULL,
  `adresseQuartier` varchar(255)  NOT NULL,
  `adresseMaison` varchar(255)  NOT NULL,
  `ethnie` varchar(20)  NOT NULL,
  `niveauEtude` varchar(30)  NOT NULL,
  `telPersoDonneur` varchar(20)  NOT NULL,
  `emailDonneur` varchar(255)  NOT NULL UNIQUE,
  `lieuTravail` varchar(255)  NOT NULL,
  `telTravail` varchar(20)  NOT NULL,
  `nomContactUrgence` varchar(255)  NOT NULL,
  `telContactUrgence` varchar(20)  NOT NULL,
  PRIMARY KEY (`idDonneur`)
)


-- Créez un déclencheur BEFORE INSERT
DELIMITER //
CREATE TRIGGER before_insert_donneur
BEFORE INSERT ON donneur
FOR EACH ROW
BEGIN
    IF NEW.numCarteDon IS NOT NULL THEN
        IF EXISTS (SELECT 1 FROM donneur WHERE numCarteDon = NEW.numCarteDon AND idDonneur <> NEW.idDonneur) THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Une valeur non nulle en conflit pour numCarteDon';
        END IF;
    END IF;
END//
DELIMITER ;

-- Créez un déclencheur BEFORE UPDATE
DELIMITER //
CREATE TRIGGER before_update_donneur
BEFORE UPDATE ON donneur
FOR EACH ROW
BEGIN
    IF NEW.numCarteDon IS NOT NULL THEN
        IF EXISTS (SELECT 1 FROM donneur WHERE numCarteDon = NEW.numCarteDon AND idDonneur <> NEW.idDonneur) THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Une valeur non nulle en conflit pour numCarteDon';
        END IF;
    END IF;
END//
DELIMITER ;



CREATE TABLE IF NOT EXISTS `participation` (
  `idParticipation` int NOT NULL,
  `participantNom` varchar(255)  DEFAULT NULL,
  `commentaires` text ,
  `telephoneParticipant` varchar(20)  DEFAULT NULL,
  `idEvenement` int DEFAULT NULL,
  PRIMARY KEY (`idParticipation`),
  KEY `idEvenement` (`idEvenement`)
)

CREATE TABLE IF NOT EXISTS `participationdonneur` (
  `idDonneur` int NOT NULL,
  `idEvenement` int NOT NULL,
  question,
  response,
  date 
  PRIMARY KEY (`idDonneur`,`idEvenement`),
  KEY `idEvenement` (`idEvenement`)
  KEY `idDonneur` (`idDonneur`)
) 

CREATE TABLE IF NOT EXISTS `reponsedonneur` (
  `idReponse` int NOT NULL AUTO_INCREMENT,
  `idDonneur` int DEFAULT NULL,
  `idEvenement` int DEFAULT NULL,
  `idQuestion` int DEFAULT NULL,
  `reponse` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`idReponse`),
  KEY `idDonneur` (`idDonneur`,`idEvenement`),
  KEY `idQuestion` (`idQuestion`)
)





ALTER TABLE `evenement`
  ADD CONSTRAINT `Evenement_ibfk_1` FOREIGN KEY (`idLieu`) REFERENCES `lieu` (`idLieu`),
  ADD CONSTRAINT `Evenement_ibfk_2` FOREIGN KEY (`idService`) REFERENCES `service` (`idService`);


ALTER TABLE `participation`
  ADD CONSTRAINT `Participation_ibfk_1` FOREIGN KEY (`idEvenement`) REFERENCES `evenement` (`idEvenement`);

ALTER TABLE `reponsedonneur`
  ADD CONSTRAINT `reponsedonneur_ibfk_1` FOREIGN KEY (`idDonneur`,`idEvenement`) REFERENCES `participationdonneur` (`idDonneur`, `idEvenement`),
  ADD CONSTRAINT `reponsedonneur_ibfk_2` FOREIGN KEY (`idQuestion`) REFERENCES `questions` (`idQuestion`);


ALTER TABLE `participationdonneur`
  ADD CONSTRAINT `ParticipationDonneur_ibfk_1` FOREIGN KEY (`idDonneur`) REFERENCES `donneur` (`idDonneur`),
  ADD CONSTRAINT `ParticipationDonneur_ibfk_2` FOREIGN KEY (`idEvenement`) REFERENCES `evenement` (`idEvenement`);



-- Insertion de dix lieux fictifs dans le département de l'Atlantique
INSERT INTO Lieu (lieu, coordonneeGeographique) VALUES 
('Allada', '6.6652° N, 2.1522° E'),
('Tori-Bossito', '6.5517° N, 2.1725° E'),
('Ouidah', '6.3639° N, 2.0853° E'),
('Cococodji', '6.3955° N, 2.1831° E'),
('Togba', '6.6820° N, 2.1994° E'),
('Zè', '6.6605° N, 2.1873° E'),
('Abomey-Calavi', '6.4545° N, 2.3551° E'),
('Akassato', '6.4720° N, 2.3228° E'),
('Godomey', '6.4224° N, 2.3030° E'),
('Sô-Ava', '6.2629° N, 2.1973° E');


INSERT INTO Lieu (lieu, coordonneeGeographique) VALUES 
('Cotonou', '6.3654° N, 2.4183° E'),
('Porto-Novo', '6.4967° N, 2.6036° E'),
('Parakou', '9.3373° N, 2.6303° E'),
('Abomey-Calavi', '6.4545° N, 2.3551° E'),
('Djougou', '9.7085° N, 1.6654° E');

INSERT INTO Lieu (lieu, coordonneeGeographique) VALUES 
('Ouidah', '6.3639° N, 2.0853° E'),
('Natitingou', '10.3069° N, 1.3784° E'),
('Lokossa', '6.6181° N, 1.7157° E'),
('Malanville', '11.8615° N, 3.3864° E'),
('Bohicon', '7.1785° N, 2.0661° E');


INSERT INTO `service` (`idService`, `nomOrganisme`, `addOrganisme`, `ville`, `telOrganisme`, `mailOrganisme`, `passwordOrganisme`) VALUES
('service1', 'Service de transfusion sanguine', 'Direction Départementale de la Santé', 'Calavi', '123456789', 'STS@mail.com', '12345'),
('service2', 'Organisme B', 'Adresse B', 'Porto-Novo', '987654321', 'service2@example.com', 'password2');

INSERT INTO Lieu (lieu, coordonneeGeographique) VALUES 
('Sakété', '6.7374° N, 2.6586° E'),
('Ifangni', '6.6904° N, 2.8864° E'),
('Adjarra', '6.6705° N, 2.9482° E'),
('Agblangandan', '6.6697° N, 2.9785° E'),
('Tori', '6.7081° N, 2.8981° E'),
('Adogbé', '6.6359° N, 2.7728° E'),
('Azowlissé', '6.5798° N, 2.8692° E'),
('Zoungbome', '6.5602° N, 2.8674° E'),
('Avlékété', '6.5066° N, 2.8503° E'),
('Avagbé', '6.4709° N, 2.8231° E');


INSERT INTO evenement (nomEvenement, isActive, dateEvenement, heureDebut, heureFin, Description, idLieu, idService)
VALUES
    ('Don de Sang Allada', 1, '2024-07-15', '08:00:00', '12:00:00', 'Don de sang à Allada', 1, 'STS-DDS-ATL'),
    ('Don de Sang Tori-Bossito', 1, '2024-07-16', '08:00:00', '12:00:00', 'Don de sang à Tori-Bossito', 2, 'STS-DDS-ATL'),
    ('Don de Sang Ouidah', 1, '2024-07-17', '08:00:00', '12:00:00', 'Don de sang à Ouidah', 3, 'STS-DDS-ATL'),
    ('Don de Sang Cococodji', 1, '2024-07-18', '08:00:00', '12:00:00', 'Don de sang à Cococodji', 4, 'STS-DDS-ATL'),
    ('Don de Sang Togba', 1, '2024-07-19', '08:00:00', '12:00:00', 'Don de sang à Togba', 5, 'STS-DDS-ATL'),
    ('Don de Sang Zè', 1, '2024-07-20', '08:00:00', '12:00:00', 'Don de sang à Zè', 6, 'STS-DDS-ATL'),
    ('Don de Sang Abomey-Calavi', 1, '2024-07-21', '08:00:00', '12:00:00', 'Don de sang à Abomey-Calavi', 7, 'STS-DDS-ATL'),
    ('Don de Sang Akassato', 1, '2024-07-22', '08:00:00', '12:00:00', 'Don de sang à Akassato', 8, 'STS-DDS-ATL'),
    ('Don de Sang Godomey', 1, '2024-07-23', '08:00:00', '12:00:00', 'Don de sang à Godomey', 9, 'STS-DDS-ATL'),
    ('Don de Sang Sô-Ava', 1, '2024-07-24', '08:00:00', '12:00:00', 'Don de sang à Sô-Ava', 10, 'STS-DDS-ATL');


INSERT INTO evenement (nomEvenement, isActive, dateEvenement, heureDebut, heureFin, Description, idLieu, idService)
VALUES
    ('Collecte de Sang Allada', 1, '2024-07-15', '08:00:00', '12:00:00', 'Collecte de sang à Allada', 1, 'STS-DDS-ATL'),
    ('Collecte de Sang Tori-Bossito', 1, '2024-07-16', '08:00:00', '12:00:00', 'Collecte de sang à Tori-Bossito', 2, 'STS-DDS-ATL'),
    ('Collecte de Sang Ouidah', 1, '2024-07-17', '08:00:00', '12:00:00', 'Collecte de sang à Ouidah', 3, 'STS-DDS-ATL'),
    ('Collecte de Sang Cococodji', 1, '2024-07-18', '08:00:00', '12:00:00', 'Collecte de sang à Cococodji', 4, 'STS-DDS-ATL'),
    ('Collecte de Sang Togba', 1, '2024-07-19', '08:00:00', '12:00:00', 'Collecte de sang à Togba', 5, 'STS-DDS-ATL'),
    ('Collecte de Sang Zè', 1, '2024-07-20', '08:00:00', '12:00:00', 'Collecte de sang à Zè', 6, 'STS-DDS-ATL'),
    ('Collecte de Sang Abomey-Calavi', 1, '2024-07-21', '08:00:00', '12:00:00', 'Collecte de sang à Abomey-Calavi', 7, 'STS-DDS-ATL'),
    ('Collecte de Sang Akassato', 1, '2024-07-22', '08:00:00', '12:00:00', 'Collecte de sang à Akassato', 8, 'STS-DDS-ATL'),
    ('Collecte de Sang Godomey', 1, '2024-07-23', '08:00:00', '12:00:00', 'Collecte de sang à Godomey', 9, 'STS-DDS-ATL'),
    ('Collecte de Sang Sô-Ava', 1, '2024-07-24', '08:00:00', '12:00:00', 'Collecte de sang à Sô-Ava', 10, 'STS-DDS-ATL'),
    ('Sensibilisation Allada', 1, '2024-07-25', '09:00:00', '11:00:00', 'Sensibilisation sur le don de sang à Allada', 1, 'STS-DDS-ATL'),
    ('Sensibilisation Tori-Bossito', 1, '2024-07-26', '09:00:00', '11:00:00', 'Sensibilisation sur le don de sang à Tori-Bossito', 2, 'STS-DDS-ATL');

INSERT INTO evenement (nomEvenement, isActive, dateEvenement, heureDebut, heureFin, Description, idLieu, idService)
VALUES
    ('Sensibilisation Ouidah', 1, '2024-07-27', '09:00:00', '11:00:00', 'Sensibilisation sur le don de sang à Ouidah', 3, 'STS-DDS-ATL'),
    ('Sensibilisation Cococodji', 1, '2024-07-28', '09:00:00', '11:00:00', 'Sensibilisation sur le don de sang à Cococodji', 4, 'STS-DDS-ATL'),
    ('Sensibilisation Togba', 1, '2024-07-29', '09:00:00', '11:00:00', 'Sensibilisation sur le don de sang à Togba', 5, 'STS-DDS-ATL'),
    ('Sensibilisation Zè', 1, '2024-07-30', '09:00:00', '11:00:00', 'Sensibilisation sur le don de sang à Zè', 6, 'STS-DDS-ATL'),
    ('Sensibilisation Abomey-Calavi', 1, '2024-07-31', '09:00:00', '11:00:00', 'Sensibilisation sur le don de sang à Abomey-Calavi', 7, 'STS-DDS-ATL'),
    ('Sensibilisation Akassato', 1, '2024-08-01', '09:00:00', '11:00:00', 'Sensibilisation sur le don de sang à Akassato', 8, 'STS-DDS-ATL'),
    ('Sensibilisation Godomey', 1, '2024-08-02', '09:00:00', '11:00:00', 'Sensibilisation sur le don de sang à Godomey', 9, 'STS-DDS-ATL'),
    ('Sensibilisation Sô-Ava', 1, '2024-08-03', '09:00:00', '11:00:00', 'Sensibilisation sur le don de sang à Sô-Ava', 10, 'STS-DDS-ATL'),
    ('Sensibilisation Cotonou', 1, '2024-08-04', '09:00:00', '11:00:00', 'Sensibilisation sur le don de sang à Cotonou', 11, 'STS-DDS-ATL'),
    ('Sensibilisation Porto-Novo', 1, '2024-08-05', '09:00:00', '11:00:00', 'Sensibilisation sur le don de sang à Porto-Novo', 12, 'STS-DDS-ATL');


INSERT INTO `employer` (`idEmployer`, `Matricule`, `nom`, `prenom`, `sexe`, `adresse`, `tel`, `email`, `Poste`) VALUES (NULL, 'STS001', 'AHISSOU', 'Gbosse', 'masculin', 'Abomey Calavi', '65777747', 'AHISSOUGbosse@gmail.com', 'C-STS-DDS-ATL');
INSERT INTO employer (idEmployer, Matricule, nom, prenom, sexe, adresse, tel, email, Poste) 
VALUES 
(NULL, 'STS002', 'Kossi', 'Amivi', 'féminin', 'Allada', '67778888', 'amivikossi@gmail.com', 'C-STS-DDS-ATL'),
(NULL, 'STS003', 'Houessou', 'Adjovi', 'féminin', 'Ouidah', '62223344', 'adjovihouessou@gmail.com', 'C-STS-DDS-ATL'),
(NULL, 'STS005', 'Gbedessi', 'Afi', 'féminin', 'Abomey-Calavi', '68889900', 'afigbedessi@gmail.com', 'C-STS-DDS-ATL'),
(NULL, 'STS004', 'Agbo', 'Koffi', 'masculin', 'Godomey', '65554477', 'koffiagbo@gmail.com', 'C-STS-DDS-ATL'),
(NULL, 'STS006', 'Hounkpatin', 'Rodrigue', 'masculin', 'Akassato', '64445588', 'rodriguehounkpatin@gmail.com', 'C-STS-DDS-ATL'),
(NULL, 'STS007', 'Adjovi', 'Koffi', 'masculin', 'Sô-Ava', '64445588', 'koffiadjovi@gmail.com', 'C-STS-DDS-ATL');


INSERT INTO questions (idQuestion, question_text, response_type) VALUES
('question1', 'Avez-vous reçu du sang une fois ?', 'checkbox'),
('question2', 'Avez-vous déjà donné du sang une fois ?', 'checkbox'),
('question3', 'Votre dernier don s''est bien déroulé ?', 'checkbox'),
('douleur_a_la_poitrine', 'Douleur à la poitrine', 'checkbox'),
('Épilepsie', 'Épilepsie', 'checkbox'),
('Éruption_cutanee', 'Éruption cutanée', 'checkbox'),
('Infection_sexuelle', 'Infection sexuelle', 'checkbox'),
('Douleur_articulaire', 'Douleur articulaire', 'checkbox'),
('Ictere_jaunisse', 'Ictère jaunisse', 'checkbox'),
('Asthme', 'Asthme', 'checkbox'),
('Plaie_dans_la_bouche', 'Plaie dans la bouche', 'checkbox'),
('Anemie', 'Anémie', 'checkbox'),
('Maladie_du_sang', 'Maladie du sang', 'checkbox'),
('Diabete', 'Diabète', 'checkbox'),
('Evanouissement', 'Évanouissement', 'checkbox'),
('Tremblement_membres', 'Tremblement des membres', 'checkbox'),
('Maladie_coeur', 'Maladie du cœur', 'checkbox'),
('Hyper_tension', 'Hypertension', 'checkbox'),
('Urine_sanglante', 'Urine sanglante', 'checkbox'),
('Ganglion', 'Ganglion', 'checkbox'),
('Fatigue_exageree', 'Fatigue exagérée', 'checkbox'),
('Hemorroides', 'Hémorroïdes', 'checkbox'),
('Diarrhee', 'Diarrhée', 'checkbox'),
('Vertige', 'Vertige', 'checkbox'),
('Ulcere', 'Ulcère', 'checkbox'),
('Cancer', 'Cancer', 'checkbox'),
('Fievre', 'Fièvre', 'checkbox'),
('Toux_plus_un_mois', 'Toux de plus d''un mois', 'checkbox'),
('Drepanocytose', 'Drépanocytose', 'checkbox'),
('Autre_maladie', 'Autre maladie (à préciser)', 'text'),
('question4', 'Avez-vous déjà été une fois opéré, transfusé, ou greffé ?', 'checkbox'),
('question5', 'Avez-vous été vacciné depuis moins de 3 mois ?', 'checkbox'),
('vaccine_name', 'Si oui, quel vaccin :', 'text'),
('question6', 'Avez-vous pris ou prenez-vous actuellement un (des) médicament(s) ?', 'checkbox'),
('medication_name', 'Si oui, quel médicament :', 'text'),
('question7', 'Avez-vous reçu des soins dentaires il y a moins de 3 mois ?', 'checkbox'),
('question8', 'Avez-vous changé de partenaire sexuel(le) ?', 'checkbox'),
('drogue', 'Je me drogue', 'checkbox'),
('partenaire_drogue', 'Mon partenaire se drogue', 'checkbox'),
('partenaire_seropositif', 'Mon partenaire est séropositif', 'checkbox'),
('blessure', 'J''ai eu une blessure avec un objet déjà utilisé', 'checkbox'),
('homosexuel', 'Je suis homosexuel', 'checkbox'),
('morsure', 'J''ai été mordu par quelqu''un', 'checkbox'),
('sejour_etranger', 'Séjour à l''étranger ces 3 derniers mois ?', 'checkbox'),
('pays_sejour', 'Si oui, dans quel pays ?', 'text'),
('date_retour', 'Date de retour', 'date'),
('scarification', 'J''ai subi de scarification, de tatouage, ou de piercing', 'checkbox'),
('question10', 'Avez-vous accouché il y a moins de 6 mois ?', 'checkbox'),
('question11', 'Avez-vous eu une fausse couche ou subi un avortement ?', 'checkbox'),
('question12', 'Combien de grossesses avez-vous déjà eues ?', 'text'),
('question13', 'À quand remontent vos dernières règles ?', 'text'),
('nombre_don_ant', 'Nombre de dons antérieurs', 'text'),
('nombre_don_annee', 'Nombre de dons dans l''année', 'text'),
('poids', 'Poids (kg)', 'text'),
('taille', 'Taille (m)', 'text'),
('temperature', 'Température (°C)', 'text'),
('pools_muqueuses', 'Pools Muqueuses', 'text'),
('pools_paumes', 'Pools Paumes', 'text'),
('pools_peau', 'Pools Peau', 'text'),
('pools_bouche', 'Pools Bouche', 'text'),
('type_poche', 'Donneurs', 'select'),
('volume_prelever', 'Volume à prélever (cc)', 'text'),
('motif_refus', 'Motif et durée de refus', 'textarea'),
('examinateur', 'Examinateur', 'text'),
('n_poche', 'N° de poche', 'text'),
('deroulement_don', 'Déroulement du Don', 'select'),
('debut', 'Début :', 'date'),
('fin', 'Fin :', 'date'),
('autres_infos', 'Autres informations', 'textarea'),
('preleveur', 'Préleveur', 'text');


 // Création d'une nouvelle connexion
    $conn = mysqli_connect($servername, $username, $passwordDB, $dbname);

    $servername = "localhost";
    $username = "id20719702_gestiondon_1";
    $password = "Ea42380@gmail.com";
    $dbname = "id20719702_gestiondon";



all_evenement.page.php
connexion.php
connexionOrganisme.php
dashboard.php
essaie.php
form_don_page.php
form_donneur_page.php
form_participant_page.php
form_predon_page.php
formulaire_evenement.page.php
info_event.php
session.php
styles.css
traitement_donneur_reponse.php



all_evenement.page.css
form_don_page_styles.css
form_donneur_page.css
form_participant_page.css
forms_event_page.css
styles.css
