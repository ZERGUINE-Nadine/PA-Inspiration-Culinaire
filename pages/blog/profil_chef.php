<?php
session_start();

// Vérification de la session si l'utilisateur n'est pas connecté (redirection page login)
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: ../../pages/login.php");
    exit();
}

// Inclure le fichier de fonction de connexion à la base de données
require('../../php/database/connect_to_datanase.php');

$recettes = []; // Initialisation du tableau des recettes
$nom_chef = '';
$specialite_chef = '';

try {
    // Obtenir la connexion à la base de données en utilisant la fonction
    $conn = get_database_connexion();

    if (!$conn) {
        die("Erreur de connexion à la base de données.");
    }

    // Récupérer l'ID du chef depuis l'URL
    $chef_id = isset($_GET['chefs']) ? $_GET['chefs'] : null;

    if ($chef_id) {
        // Requête SQL pour récupérer les informations du chef et ses recettes
        $sql = "SELECT 
                    recette.id_recette,
                    recette.nom AS nom_recette,
                    recette.instruction,
                    recette.temps,
                    recette.nutriscore,
                    recette.date_publication,
                    recette.image_url,
                    chef.nom AS nom_chef,
                    chef.specialite AS specialite_chef
                FROM 
                    recette
                JOIN 
                    chef ON recette.chef_id = chef.id_chef
                WHERE 
                    recette.chef_id = :chef_id
                ORDER BY 
                    recette.nom";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':chef_id', $chef_id, PDO::PARAM_INT);
        $stmt->execute();
        $recettes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer le nom du chef et sa spécialité depuis la première recette (s'il y en a)
        if (!empty($recettes)) {
            $nom_chef = $recettes[0]['nom_chef'];
            $specialite_chef = $recettes[0]['specialite_chef'];
        } else {
            echo "Aucune recette trouvée pour ce chef.";
            exit();
        }
    } 

    $conn = null; // Ferme la connexion à la base de données
} catch (PDOException $e) {
    echo "Erreur PDO : " . $e->getMessage();
}
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Challenge</title>
    <link rel="stylesheet" href="../../assets/styles/pfofil_chef.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../assets/styles/hedaer.css"/>
    <link rel="stylesheet" href="../../assets/styles/mini_header.css"/>
    <link rel="stylesheet" href="../../assets/styles/footer.css"/>
    <link rel="stylesheet" href="../../assets/styles/sombre.css"/>
    <script src="https://kit.fontawesome.com/5732949dd8.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php require("../../pages/includes/header.php") ?>
    <main>
        <h1><?php echo htmlspecialchars($nom_chef); ?> <?php echo htmlspecialchars($specialite_chef); ?></h1>
        <div class="recettes">
            <?php if (!empty($recettes)): ?>
                <?php foreach ($recettes as $recette): ?>
                    <div class="recette" onclick="onRecetteClick('<?php echo htmlspecialchars($recette['id_recette']); ?>')">
                        <img src="<?php echo htmlspecialchars($recette['image_url']); ?>" alt="<?php echo htmlspecialchars($recette['nom_recette']); ?>">
                        <div class="dish"><?php echo htmlspecialchars($recette['nom_recette']); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune recette trouvée pour ce chef.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
<script src="../../js/profil.js"></script>
<script src="../../js/sombre.js"></script>

<?php require("../../pages/includes/footer.php") ?>

</html>
