<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style/styleadd_user.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body>
    <header>
        <h1>Utilisateurs</h1>
    </header>
    <a class="btn btn-success btn-bottom-right" href="javascript:history.go(-1);">
        <i class='bx bx-arrow-back'></i> Retour
    </a>
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
    </main>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
                    formData.push({
                        email: email,
                        motDePasse: motDePasse,
                        badge: badge
                    });
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
        });
    </script>
</body>

</html>