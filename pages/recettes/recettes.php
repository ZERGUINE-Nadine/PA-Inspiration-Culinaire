<?php
session_start();



// Inclure le fichier de fonction de connexion à la base de données
require('../../php/database/connect_to_datanase.php');

$recettes = []; // Initialisation du tableau des recettes

try {
    // Obtenir la connexion à la base de données en utilisant la fonction
    $conn = get_database_connexion();

    if (!$conn) {
        die("Erreur de connexion à la base de données.");
    }

    // Récupérer l'ID de la catégorie depuis l'URL
    $category_id = isset($_GET['categorie']) ? $_GET['categorie'] : null;
     // Récupérer  et le nom de la catégorie depuis l'URL
    $category_name = isset($_GET['nom']) ? $_GET['nom'] : null;

    if ($category_id) {
        // Requête SQL pour récupérer les recettes de la catégorie spécifiée
        $sql = "SELECT 
                    recette.id_recette AS id_recette,
                    recette.nom AS nom_recette,
                    recette.instruction,
                    recette.temps,
                    recette.nutriscore,
                    recette.date_publication,
                    recette.image_url
                FROM 
                    recette
                WHERE 
                    recette.id_categorie = :category_id
                ORDER BY 
                    recette.nom";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        $recettes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "Aucune catégorie spécifiée.";
        exit();
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recettes de la catégorie <?php echo htmlspecialchars($category_name); ?></title>
    <link rel="stylesheet" href="../../assets/styles/recettes.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../assets/styles/hedaer.css" />
    <link rel="stylesheet" href="../../assets/styles/footer.Css" />
    <link rel="stylesheet" href="../../assets/styles/sombre.css" />


    <script src="https://kit.fontawesome.com/5732949dd8.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php require("../../pages/includes/header.php") ?>
    <main>
    <h1><?php  echo $category_name ?></h1>
        <div class="recettes">
            <?php if (!empty($recettes)): ?>
                <?php foreach ($recettes as $recette): ?>
                    <div class="recette" onclick="onrecetteClick('<?php echo $recette['id_recette']; ?>')">
                        <img src="<?php echo $recette['image_url']; ?>" alt="<?php echo $recette['nom_recette']; ?>">
                        <div class="dish"><?php echo $recette['nom_recette']; ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune recette trouvée pour cette catégorie.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
<script src="../../js/recettes.js"></script>
<script src="../../js/sombre.js"></script>

<?php require("../../pages/includes/footer.php") ?>

</html>
