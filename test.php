<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaires avec Onglets</title>
    <style>
        /* style/style.css */
body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
}

header {
    background-color: #841c1c;
    color: white;
    text-align: center;
    padding: 10px 0;
}

.tab-container {
    text-align: center;
    margin-top: 20px;
}

.tab-button {
    padding: 10px 20px;
    margin: 0 10px;
    cursor: pointer;
    border: none;
    background-color: #841c1c;
    color: white;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.tab-button:hover {
    background-color: #0056b3;
}

.form-container {
    margin: 20px auto;
    width: 80%;
    max-width: 800px;
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.form-content {
    display: none;
}

.form-content.show {
    display: block;
    animation: fadeIn 0.5s;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

    </style>
</head>
<body>
    <header>
        <h1>ENREGISTREMENT</h1>
    </header>
    <div class="tab-container">
        <button class="tab-button" data-target="form1">Utilisateur</button>
        <button class="tab-button" data-target="form2">Employer</button>
    </div>
    <div class="form-container">
        <section id="form1" class="form-content" style="display: none;">
            <!-- Contenu de form_add_user.php -->
            <h2>Gestion des Utilisateurs</h2>
            <section id="members-section">
                <h2>Membres</h2>
                <ul id="members-list"></ul>
            </section>
            <section id="administrators-section">
                <h2>Administrateurs</h2>
                <ul id="administrators-list"></ul>
            </section>
            <h2>Ajouter un Utilisateur</h2>
            <table id="participants-table">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Mot de Passe</th>
                        <th>Badge</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="email" name="emailUtilisateur[]" required></td>
                        <td><input type="password" name="motDePasseUtilisateur[]" required></td>
                        <td>
                            <select name="badgeUtilisateur[]" required>
                                <option value="Membre">Membre</option>
                                <option value="Administrateur">Administrateur</option>
                            </select>
                        </td>
                        <td><button class="remove-row-btn">X</button></td>
                    </tr>
                </tbody>
            </table>
            <button id="add-row-btn">Ajouter un utilisateur</button>
            <button id="submit-btn">Soumettre</button>
        </section>
        <section id="form2" class="form-content" style="display: none;">
            <!-- Contenu de form_employer.php -->
            <h2>Employer</h2>
            <a class="btn btn-success mt-5" href="./form_add_employer.php">New</a>
            <a class="btn btn-success mt-5" href="javascript:history.go(-1);">Retour</a>
            <table class="table table-striped mt-5">
                <thead>
                    <tr>
                        <th>Matricule</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Sexe</th>
                        <th>Adresse</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Poste</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Les données des employés -->
                    <?php
                    include('connexion.php');
                    $req = $conn->query("select * from employer");
                    while ($donnees = mysqli_fetch_assoc($req)) {
                        $id = $donnees["idEmployer"];
                        echo '<tr>';
                        echo "<td>" . $donnees["Matricule"] . "</td>";
                        echo "<td>" . $donnees["nom"] . " " . $donnees["prenom"] . "</td>";
                        echo "<td>" . $donnees["prenom"] . "</td>";
                        echo "<td>" . $donnees["sexe"] . "</td>";
                        echo "<td>" . $donnees["adresse"] . "</td>";
                        echo "<td>" . $donnees["tel"] . "</td>";
                        echo "<td>" . $donnees["email"] . "</td>";
                        echo "<td>" . $donnees["Poste"] . "</td>";
                        echo "<td>
                                <form action='form_add_employer.php?id=" . $id . "' method='post'>
                                    <input class='btn btn-danger' type='submit' value='Edit' name='modifier'/>
                                </form>
                              </td>";
                        echo "<td>
                                <form action='delete.php?id=" . $id . "' method='post'>
                                    <input class='btn btn-primary' type='submit' value='Delete' name='supprimer'/>
                                </form>
                              </td>";
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
    <script>
        // script.js
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.tab-button');
    const forms = document.querySelectorAll('.form-content');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const targetFormId = this.getAttribute('data-target');
            forms.forEach(form => {
                if (form.id === targetFormId) {
                    form.classList.add('show');
                } else {
                    form.classList.remove('show');
                }
            });
        });
    });

    // Code JavaScript de form_add_user.php ici...
    const addRowBtn = document.getElementById("add-row-btn");
    const submitBtn = document.getElementById("submit-btn");
    const tableBody = document.querySelector("#participants-table tbody");
    
    if (addRowBtn && submitBtn && tableBody) {
        addRowBtn.addEventListener("click", function() {
            const newRow = `
                <tr>
                    <td><input type="email" name="emailUtilisateur[]" required></td>
                    <td><input type="password" name="motDePasseUtilisateur[]" required></td>
                    <td>
                        <select name="badgeUtilisateur[]" required>
                            <option value="Membre">Membre</option>
                            <option value="Administrateur">Administrateur</option>
                        </select>
                    </td>
                    <td><button class="remove-row-btn">X</button></td>
                </tr>
            `;
            tableBody.insertAdjacentHTML("beforeend", newRow);
        });

        tableBody.addEventListener("click", function(event) {
            if (event.target.classList.contains("remove-row-btn")) {
                const row = event.target.closest("tr");
                row.remove();
            }
        });

        submitBtn.addEventListener("click", function() {
            const participants = tableBody.querySelectorAll("tr");
            const formData = [];
            participants.forEach(function(participant) {
                const email = participant.querySelector("input[name='emailUtilisateur[]']").value;
                const motDePasse = participant.querySelector("input[name='motDePasseUtilisateur[]']").value;
                const badge = participant.querySelector("select[name='badgeUtilisateur[]']").value;
                formData.push({ email: email, motDePasse: motDePasse, badge: badge });
            });
            fetch("add_users.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (response.ok) {
                    alert("Enregistrement réussi !");
                    location.reload();
                } else {
                    alert("Erreur lors de l'enregistrement. Veuillez réessayer.");
                }
            })
            .catch(error => {
                console.error("Erreur:", error);
                alert("Erreur lors de l'enregistrement. Veuillez réessayer.");
            });
        });

        fetch("get_users.php")
            .then(response => response.json())
            .then(users => {
                const membersList = document.getElementById("members-list");
                const administratorsList = document.getElementById("administrators-list");

                users.forEach(user => {
                    const listItem = document.createElement("li");
                    listItem.textContent = user.email;

                    if (user.Badge === "Membre") {
                        membersList.appendChild(listItem);
                    } else if (user.Badge === "Administrateur") {
                        administratorsList.appendChild(listItem);
                    }
                });
            })
            .catch(error => {
                console.error("Erreur:", error);
            });
    }
});
    </script>
</body>
</html>
