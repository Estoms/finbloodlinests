<?php
if (!isset($_GET['acces'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <title>Employer</title>
    <style>
        body {
            background-color: #841c1c;
            color: white;
        }
        .container_employer {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
        }
        h2 {
            color: #841c1c;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        table {
            color: black;
        }
    </style>
</head>
<body>
    <div class="container_employer mt-5">
        <h2>Employer</h2>
        <a class="btn btn-success mt-5" href="./form_add_employer.php">New</a>
        <a class="btn btn-success mt-5" href="javascript:history.go(-1);">Retour</a>
        <?php
        include('connexion.php');
        $req = $conn->query("select * from employer");
        echo '
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
            ';
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
    </div>
</body>
</html>