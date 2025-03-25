<?php
session_start();

// Vérification de la session si l'utilisateur n'est pas connecté (redirection page login)
$is_authenticated = false;

if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    $is_authenticated = true;
} else {
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

    // Initialiser la variable articles comme un tableau vide
    $articles = [];

    // Requête SQL pour récupérer tous les articles
    $sql = "SELECT 
                id_article, 
                titre AS titre_article, 
                description,
                evaluation,
                date_publication,
                image_url
            FROM 
                article
            ORDER BY 
                titre_article";

    $result = $conn->query($sql);

    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $articles[] = [
                'id_article' => $row['id_article'],
                'titre_article' => $row['titre_article'],
                'description' => $row['description'],
                'evaluation' => $row['evaluation'],
                'date_publication' => $row['date_publication'],
                'image_url' => $row['image_url']
            ];
        }
    } else {
        echo "Aucun article trouvé.";
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
    <link rel="stylesheet" href="../../assets/styles/articles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../assets/styles/hedaer.css"/>
    <link rel="stylesheet" href="../../assets/styles/mini_header.css"/>
    <link rel="stylesheet" href="../../assets/styles/sombre.css"/>

    <script src="https://kit.fontawesome.com/5732949dd8.js" crossorigin="anonymous"></script>
</head>
<body>
  <?php 
  require("../../pages/includes/header.php");
  require("../../pages/includes/mini_header.php"); 
  ?>
  <main>
    <div class="articles">
      <?php if (!empty($articles)) { ?>
        <?php foreach ($articles as $article) { ?>
          <div class="article" onclick="OnarticleClick('<?php echo $article['id_article']; ?>')">

            <div class="article">
              <img src="<?php echo $article['image_url']; ?>" alt="Article Image">
              <div class="titre"><?php echo $article['titre_article']; ?></div>
            </div>
          </div>
        <?php } ?>
      <?php } else { ?>
        <p>Aucun article trouvé.</p>
      <?php } ?>
    </div>
  </main>
  <script src="../../js/article.js"></script>
  <script src="../../js/sombre.js"></script>

</body>
</html>
