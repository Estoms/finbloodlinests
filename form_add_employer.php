<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Moderne</title>
    <style>
        body {
            background-color: #841c1c;
            font-family: Arial, sans-serif;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff20;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        input[type="text"], input[type="email"], input[type="tel"] {
            padding: 10px;
            border: none;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Formulaire d'inscription</h2>
    <form action="/submit" method="post">
        <label for="matricule">Matricule:</label>
        <input type="text" id="matricule" name="matricule" required>

        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" required>

        <label for="prenom">Prénom:</label>
        <input type="text" id="prenom" name="prenom" required>

        <label for="sexe">Sexe:</label>
        <input type="text" id="sexe" name="sexe" required>

        <label for="adresse">Adresse:</label>
        <input type="text" id="adresse" name="adresse" required>

        <label for="tel">Téléphone:</label>
        <input type="tel" id="tel" name="tel" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="poste">Poste:</label>
        <input type="text" id="poste" name="poste" required>

        <input type="hidden" id="idService" name="idService" value="">

        <input type="submit" value="Envoyer">
    </form>
</div>

</body>
</html>
