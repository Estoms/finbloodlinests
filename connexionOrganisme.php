<?php
session_start();

$servername = "localhost";
$username = "4527705_sts";
$password = "GestSTS001@";
$dbname = "4527705_sts";

// Création d'une nouvelle connexion
$conn = mysqli_init();

// Configuration de la connexion
if (!$conn) {
    die("Erreur d'initialisation de la connexion: " . mysqli_connect_error());
}

mysqli_real_connect($conn, $servername, $username, $password, $dbname);

// Vérification de la connexion
if (mysqli_connect_errno()) {
    die("La connexion a échoué: " . mysqli_connect_error());
}

// Vérifier si le formulaire a été soumis
if (isset($_POST['email']) && isset($_POST['password'])) {
    // Récupérer les données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Échapper les valeurs pour éviter les injections SQL
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Requête pour vérifier les informations de connexion
    $sql = "SELECT * FROM service WHERE mailOrganisme = '$email' AND passwordOrganisme = '$password'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Connexion réussie
       
        // Récupérer l'ID de l'organisme de collecte à partir de la base de données
        $row = mysqli_fetch_assoc($result);
        $idService = $row['idService'];

        // Stocker l'ID de l'organisme de collecte dans une variable de session
        $_SESSION['idService'] = $idService;
        $_SESSION['premiere_connexion'] = true;

        // Redirection avec JavaScript
       echo '<script>window.location.href = "index.php";</script>';
        //header("location:creationEvenement.php");
        exit();
    } else {
        // Connexion échouée
        echo '<h1>Connexion échouée</h1>';
        echo '<p>Email ou mot de passe incorrect.</p>';
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Page de connexion</title>
  <!-- Inclure la bibliothèque Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">

  <style>
    body {
      background-color: #841c1c;
      
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .container {
      padding: 70px;
      background-color: white;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    h1 {
      color: #DF3131;
    }
    
    input:focus {
      outline: none;
      border: 1px solid blue;
    }

    .input-container {
      position: relative;
      margin-bottom: 20px;
    }

    label {
      position: absolute;
      left: 10px;
      top: 50%;
      transform: translateY(-50%);
      pointer-events: none;
      transition: 0.3s;
    }

    input:focus + label,
    input:valid + label {
      top: -10px;
      font-size: 12px;
      color: blue;
    }

    input {
      width: 100%;
      padding: 12px;
      font-size: 16px;
    }

    input:focus {
      outline: none;
      border: 1px solid blue;
    }
    .input-container {
        position: relative;
    }

    .input-container input[type="password"] {
        padding-right:10px; /* Ajoutez un espace pour l'icône */

    }

    .input-container .eye-icon {
        position: absolute;
        top: 50%;
        right: 0px;
        transform: translateY(-50%);
        cursor: pointer;
        z-index: 1;
    }
    
    input[type="submit"] {
      background-color: #DF3131;
      color: white;
      padding: 10px 20px;
      border: none;
      width: 130px;
      margin-left: 20px;
      border-radius: 3px;
      cursor: pointer;
    }
    #loginResult {
      margin-top: 20px;
      color: #DF3131;
    }
    
    @media screen and (max-width: 768px) {
      .container {
        width: 90%;
        padding: 30px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Connexion</h1>
    <form action="connexionOrganisme.php" method="post">
      <div class="input-container">
        <input type="text" id="email" name="email" required>
        <label for="email">Email:</label>
      </div>
       
      <div class="input-container">
        <input type="password" id="password" name="password" required>
        <label for="password">Mot de passe:</label>
        <i class="eye-icon fas fa-eye" onclick="togglePassword()"></i>
      </div>
      
      <input type="submit" value="Se connecter">
    </form>
    <div id="loginResult"></div>
  </div>
  <script>
  function togglePassword() {
    var passwordInput = document.getElementById("password");
    var eyeIcon = document.getElementById("eye-icon");

    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      eyeIcon.classList.remove("fa-eye");
      eyeIcon.classList.add("fa-eye-slash");
    } else {
      passwordInput.type = "password";
      eyeIcon.classList.remove("fa-eye-slash");
      eyeIcon.classList.add("fa-eye");
    }
  }
</script>
</body>
</html>
