<?php
session_start();

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inspiration Culinaire</title>
    <link rel="stylesheet" href="../assets/styles/register.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/styles/hedaer.css"/>
    <link rel="stylesheet" href="../assets/styles/footer.css">
    <link rel="stylesheet" href="../assets/styles/sombre.css">


    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
</head> 
<body>
    <?php require("./includes/header.php");?>
    <div class="container-fluid full-height d-flex justify-content-center align-items-center image-container">
        <img src="../assets/images/back6.jpg" alt="Background Image">
        <div class="position-relative text-center text-white w-100 overflow-auto h-100" style="left: 30%; margin-top: 50px;">
            <div class="form-container text-center">
                <h1 class="mb-4">Créer votre compte</h1>
                <form id="registerForm" class="h-100">
                    <div class="form-group">
                        <label for="name"></label>
                        <input id="name" type="text" class="form-control" placeholder="Pseudo nom">
                        <div id="nameError" class="error-message"></div>
                    </div>
                    <div class="form-group">
                        <label for="email"></label>
                        <input id="email" type="email" class="form-control" placeholder="Adresse email">
                        <div id="emailError" class="error-message"></div>
                    </div>
                    <div class="form-group">
                        <label for="password"></label>
                        <input id="password" type="password" class="form-control" placeholder="Mot de passe">
                        <div id="passwordError" class="error-message"></div>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword"></label>
                        <input id="confirmPassword" type="password" class="form-control" placeholder="Confirmez votre mot de passe">
                        <div id="confirmPasswordError" class="error-message"></div>
                    </div>
                    <button type="button" class="btn btn-orange btn-block w-100" onclick="onRegisterClick()">Register</button>
                    <div>
                        <p>Vous avez déjà un compte ? <a href="../../../inspiration_culinaire/pages/login.php">Se Connecter</a></p>
                    </div>
                </form>
            </div>
        </div>
        <div id="toastBox"></div>
    </div>
    <script src="../js/register.js"></script>
    <script src="../js/sombre.js"></script>

</body>
</html>
