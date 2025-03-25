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
  <link rel="stylesheet" href="../assets/styles/contact.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../assets/styles/hedaer.css" />
  <link rel="stylesheet" href="../assets/styles/footer.Css">
  <link rel="stylesheet" href="../assets/styles/sombre.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>

<body>
  <?php require("./includes/header.php") ?>
  <div class="container-fluid full-height d-flex justify-content-center align-items-center image-container">
    <img src="../assets/images/back1.jpg" alt="Background Image">
    <div class="position-relative text-center text-white w-100 overflow-auto h-100" style="left: 35%; margin-top: 100px;">
      <div class="form-container text-center ">
        <h1 class="mb-4">Contactez Nous !</h1>
        <form class="h-100" method="post" action="sendEmail.php">
          <form class="h-100">

          <div class="form-group">
            <label for="name">Pseudo Nom </label>
            <input id="name" type="text" class="form-control">
            <div id="nameError" class="error-message"></div>

          </div>
          <div class="form-group">
            <label for="email">Adresse mail</label>
            <input id="email" type="email" class="form-control" ">
            <div id=" emailError" class="error-message">
          </div>
      </div>

      <div class="form-group">
        <label for="message"> Entrez votre message </label>
        <textarea id="message" name="message" class="message"></textarea>
        <div id=" emailError" class="error-message">
        </div>
      </div>
      <button type="button" onclick="sendEmail()" class="btn btn-orange btn-block w-100">Envoyer</button>

      </form>
    </div>
  </div>
  </div>
  </div>
  <div id="toastBox"></div>
  </div>
  <?php require("./includes/footer.php") ?>
  <script src="../js/contact.js"></script>
  <script src="../js/sombre.js"></script>


  

</body>

</html>