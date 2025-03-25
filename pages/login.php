<?php
session_start();

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inspiration Culinaire</title>
    <link rel="stylesheet" href="../assets/styles/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/styles/hedaer.css"/>
    <link rel="stylesheet" href="../assets/styles/sombre.css"/>

    <script src="https://kit.fontawesome.com/5732949dd8.js" crossorigin="anonymous"></script>
</head>
<body>
  <?php require("./includes/header.php") ?>
  <div class="container-fluid full-height d-flex justify-content-center align-items-center image-container">
        <img src="../assets/images/back9.jpg" alt="Background Image">
        <div class="position-relative text-center text-white w-100 overflow-auto h-100" style="left: 35%; margin-top: 250px;">
        <div class="form-container text-center ">
            <h1 class="mb-4">Connexion</h1>
            <form class="h-100">
               
                <div class="form-group">
                    <label for="email">  </label>
                    <input type="email" class="form-control" id="email" placeholder="Adresse mail ">
                    <div id="emailError" class="error-message"></div>
                </div>
                <div class="form-group">
                    <label for="password"> </label>
                    <input type="password" class="form-control" id="password" placeholder="Mot de passe">
                    <div id="passwordError" class="error-message"></div>
                </div>
                
                <button type="button" onclick="onLoginBtnClick()" class="btn btn-orange btn-block w-100">Se Connecter</button>
                <div>
                    <p>vous n'avez pas un compte ?<a href="../../../inspiration_culinaire/pages/register.php">S'enregistrer</a></p>
                </div>
            </form>
        </div>
        </div>
    </div>
    <div id="toastBox"></div>
  </body>
  <script src="../js/login.js"></script>
  <script src="../js/sombre.js"></script>

</html>

