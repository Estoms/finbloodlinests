<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="style/styleadd_user.css">
</head>
<body>
    <header>
        <h1>Gestion des Utilisateurs</h1>
    </header>
    <main>
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
                    <th>Action</th>
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
    </main>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Charger les utilisateurs existants
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

            const addRowBtn = document.getElementById("add-row-btn");
            const submitBtn = document.getElementById("submit-btn");
            const tableBody = document.querySelector("#participants-table tbody");

            // Ajouter une ligne au tableau
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

            // Supprimer une ligne du tableau
            tableBody.addEventListener("click", function(event) {
                if (event.target.classList.contains("remove-row-btn")) {
                    const row = event.target.closest("tr");
                    row.remove();
                }
            });

            // Soumettre le formulaire
            submitBtn.addEventListener("click", function() {
                const participants = tableBody.querySelectorAll("tr");
                const formData = [];
                participants.forEach(function(participant) {
                    const email = participant.querySelector("input[name='emailUtilisateur[]']").value;
                    const motDePasse = participant.querySelector("input[name='motDePasseUtilisateur[]']").value;
                    const badge = participant.querySelector("select[name='badgeUtilisateur[]']").value;
                    formData.push({ email: email, motDePasse: motDePasse, badge: badge });
                });

                // Envoyer les données au script PHP pour insertion dans la base de données
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
        });
    </script>
</body>
</html>
