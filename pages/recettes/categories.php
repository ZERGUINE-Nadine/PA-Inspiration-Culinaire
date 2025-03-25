<?php
session_start();

// Vérification de la session si l'utilisateur n'est pas connecté (redirection page login)
$is_authenticated = false;



// Inclure le fichier de fonction de connexion à la base de données
require('../../php/database/connect_to_datanase.php');

try {
    // Obtenir la connexion à la base de données en utilisant la fonction
    $conn = get_database_connexion();

    if (!$conn) {
        die("Erreur de connexion à la base de données.");
    }

    // Requête SQL pour récupérer toutes les catégories et leurs recettes associées
    $sql = "SELECT 
                categorie.id_categorie AS id_categorie, 
                categorie.nom AS nom_categorie, 
                recette.id_recette AS id_recette,
                recette.nom AS nom_recette,
                recette.instruction,
                recette.temps,
                recette.nutriscore,
                recette.date_publication,
                recette.image_url
            FROM 
                categorie
            LEFT JOIN 
                recette ON categorie.id_categorie = recette.id_categorie
            ORDER BY 
                nom_categorie, nom_recette";

    $result = $conn->query($sql);
    $categories = [];

    if ($result->rowCount() > 0) {
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id_categorie = $row['id_categorie'];
            $categorie_nom = $row['nom_categorie'];
            $id_recette = $row['id_recette'];
            $recette_nom = $row['nom_recette'];
            $recette_instruction = $row['instruction'];
            $recette_temps = $row['temps'];
            $recette_nutriscore = $row['nutriscore'];
            $recette_date_publication = $row['date_publication'];
            $recette_image_url = $row['image_url'];
            
            if (!isset($categories[$id_categorie])) {
                $categories[$id_categorie] = [
                    'id_categorie' => $id_categorie,
                    'nom' => $categorie_nom,
                    'recettes' => []
                ];
            }
            
            if ($id_recette) {
                $categories[$id_categorie]['recettes'][] = [
                    'id' => $id_recette,
                    'nom' => $recette_nom,
                    'instruction' => $recette_instruction,
                    'temps' => $recette_temps,
                    'nutriscore' => $recette_nutriscore,
                    'date_publication' => $recette_date_publication,
                    'image_url' => $recette_image_url,
                ];
            }
        }        
    } else {
        echo "Aucune catégorie trouvée.";
    }

    

    $conn = null;
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
  <link rel="stylesheet" href="../../assets/styles/categories.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../../assets/styles/hedaer.css" />
  <link rel="stylesheet" href="../../assets/styles/footer.css" />
  <link rel="stylesheet" href="../../assets/styles/sombre.css" />
  <script src="https://kit.fontawesome.com/5732949dd8.js" crossorigin="anonymous"></script>
</head>

<body>
  <?php require("../../pages/includes/header.php") ?>
  <button class="surprise-button" onclick="surpriseMe()">Surprenez moi !</button>

  <main>
    <div class="categories">
      <?php foreach($categories as $category) { ?>
        <div class="flex-grid" onclick="onCategorieClick('<?php echo $category['id_categorie']; ?>', '<?php echo $category['nom']; ?>' )">
          <div class="nom_categorie">
            <img src="../../assets/images/Logoo.png">
            <div class="dish"><?php echo $category['nom'] ?></div>
          </div>
          <?php foreach($category['recettes'] as $recette) { ?>
            <div class="flex-item" >
              <img src="<?php echo $recette['image_url'] ?>">
              <div class="dish"><?php echo $recette['nom'] ?></div>
            </div>
          <?php } ?>
        </div>
      <?php } ?>
    </div>
  </main>
  
  <script>
    var categories = <?php echo json_encode(array_values($categories)); ?>;
  </script>
  <script src="../../js/categories.js"></script>
  <script src="../../js/sombre.js"></script>


  <?php require("../../pages/includes/footer.php") ?>
  
</body>

</html>
