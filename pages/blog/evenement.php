<?php
session_start();

// Vérification de la session si l'utilisateur n'est pas connecté (redirection page login)
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: ../../pages/login.php");
    exit();
}

// Inclure le fichier de fonction de connexion à la base de données
require('../../php/database/connect_to_datanase.php');

try {
    // Obtenir la connexion à la base de données en utilisant la fonction
    $conn = get_database_connexion();

    if (!$conn) {
        die("Erreur de connexion à la base de données.");
    }

    // Initialiser les variables events et challenges comme des tableaux vides
    $events = [];
    $challenges = [];

    // Requête SQL pour récupérer tous les événements
    $sql_events = "SELECT 
                        nom, 
                        image_url, 
                        url
                   FROM 
                        evenement
                   ORDER BY 
                        nom";

    $result_events = $conn->query($sql_events);

    if ($result_events->rowCount() > 0) {
        while ($row = $result_events->fetch(PDO::FETCH_ASSOC)) {
            $events[] = [
                'nom' => $row['nom'],
                'image_url' => $row['image_url'],
                'url' => $row['url']
            ];
        }
    } else {
        echo "Aucun événement trouvé.";
    }

    // Requête SQL pour récupérer tous les challenges
    $sql_challenges = "SELECT 
                           nom, 
                           image_url, 
                           id_challenge 
                       FROM 
                           challenge
                       ORDER BY 
                           nom";

    $result_challenges = $conn->query($sql_challenges);

    if ($result_challenges->rowCount() > 0) {
        while ($row = $result_challenges->fetch(PDO::FETCH_ASSOC)) {
            $challenges[] = [
                'nom' => $row['nom'],
                'image_url' => $row['image_url'],
                'id_challenge' => $row['id_challenge'] // Assurez-vous d'utiliser 'id_challenge' ici
            ];
        }
    } else {
        echo "Aucun challenge trouvé.";
    }

    $conn = null; // Ferme la connexion à la base de données
} catch (PDOException $e) {
    echo "Erreur PDO : " . $e->getMessage();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inspiration Culinaire</title>
    <link rel="stylesheet" href="../../assets/styles/evenement.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../assets/styles/hedaer.css"/>
    <link rel="stylesheet" href="../../assets/styles/mini_header.css"/>
    <link rel="stylesheet" href="../../assets/styles/footer.Css"/>
    <link rel="stylesheet" href="../../assets/styles/sombre.css"/>

    <script src="https://kit.fontawesome.com/5732949dd8.js" crossorigin="anonymous"></script>
</head>
<body>
  <?php require("../../pages/includes/header.php");
       require("../../pages/includes/mini_header.php"); 
    ?>
   <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="section-title">Événements</div>
        <?php if (!empty($events)) { ?>
          <?php foreach ($events as $event) { ?>
            <div class="event" onclick="OnEventClick('<?php echo $event['url']; ?>')">
              <img src="<?php echo $event['image_url']; ?>" alt="Event Image">
              <div class="nom"><?php echo $event['nom']; ?></div>
            </div>
          <?php } ?>
        <?php } else { ?>
          <p>Aucun événement trouvé.</p>
        <?php } ?>
      </div>
      <div class="col-md-6">
        <div class="section-title">Challenges</div>
        <?php if (!empty($challenges)) { ?>
          <?php foreach ($challenges as $challenge) { ?>
            <div class="challenge" onclick="OnChallengeClick('<?php echo $challenge['id_challenge']; ?>')">
              <img src="<?php echo $challenge['image_url']; ?>" alt="Challenge Image">
              <div class="nom"><?php echo $challenge['nom']; ?></div>
            </div>
          <?php } ?>
        <?php } else { ?>
          <p>Aucun challenge trouvé.</p>
        <?php } ?>        
      </div>
    </div>
  </div>
  
  </body>
  <?php require("../../pages/includes/footer.php") ?>
  <script src="../../js/evenement_challenges.js"></script>
  <script src="../../js/sombre.js"></script>

</html>
