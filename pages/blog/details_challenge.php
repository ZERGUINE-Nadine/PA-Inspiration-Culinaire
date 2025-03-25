<?php
session_start();

// Vérification de la session si l'utilisateur n'est pas connecté (redirection page login)
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: ../../pages/login.php");
    exit();
}

// Inclure le fichier de fonction de connexion à la base de données
require('../../php/database/connect_to_datanase.php');

$challenge_id = null;

if (isset($_GET['challenge'])) {
    $challenge_id = $_GET['challenge'];
} else {
    // Rediriger ou gérer l'absence de paramètre challenge
    header("Location: ../../pages/erreur.php");
    exit();
}

$challenge = []; // Pour stocker les détails du challenge

try {
    // Obtenir la connexion à la base de données en utilisant la fonction
    $conn = get_database_connexion();

    if (!$conn) {
        die("Erreur de connexion à la base de données.");
    }

    // Requête SQL pour récupérer les détails du challenge
    $sql = "SELECT 
                id_challenge, 
                image_url, 
                nom, 
                date_debut, 
                date_fin, 
                nb_inscrit, 
                date_creation, 
                descriptions 
            FROM 
                challenge 
            WHERE 
                id_challenge = :id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $challenge_id, PDO::PARAM_INT);
    $stmt->execute();

    // Vérifie si des résultats sont retournés
    if ($stmt->rowCount() > 0) {
        $challenge = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        // Gérer le cas où aucun challenge n'est trouvé avec cet ID
        header("Location: ../../pages/erreur.php");
        exit();
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
    <link rel="stylesheet" href="../../assets/styles/details_challenge.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../assets/styles/hedaer.css"/>
    <link rel="stylesheet" href="../../assets/styles/mini_header.css"/>
    <link rel="stylesheet" href="../../assets/styles/footer.css"/>
    <link rel="stylesheet" href="../../assets/styles/sombre.css"/>

    <script src="https://kit.fontawesome.com/5732949dd8.js" crossorigin="anonymous"></script>
</head>
<body>
<?php require("../../pages/includes/header.php"); ?>
<?php require("../../pages/includes/mini_header.php"); ?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <img src="<?php echo htmlspecialchars($challenge['image_url']); ?>" alt="<?php echo htmlspecialchars($challenge['nom']); ?>" class="img-fluid rounded mb-3">
            <div class="dates">
                <p><strong>Date de début:</strong> <?php echo date('d-m-Y', strtotime($challenge['date_debut'])); ?></p>
                <p><strong>Date de fin:</strong> <?php echo date('d-m-Y', strtotime($challenge['date_fin'])); ?></p>
                <p><strong>Date de création:</strong> <?php echo date('d-m-Y', strtotime($challenge['date_creation'])); ?></p>
            </div>
        </div>
        <div class="col-md-6">
            <h2><?php echo htmlspecialchars($challenge['nom']); ?></h2>
            <p><strong>Nombre d'inscrits:</strong> <?php echo htmlspecialchars($challenge['nb_inscrit']); ?></p>
            <p><strong>Descriptions:</strong></p>
            <p><?php echo nl2br(htmlspecialchars($challenge['descriptions'])); ?></p>
            <div class="text-center mt-4">
                <button onclick="inscrireAuChallenge(<?php echo htmlspecialchars($challenge['id_challenge']); ?>)" class="btn btn-primary">S'inscrire au challenge</button>
            </div>
        </div>
    </div>
</div>

<?php require("../../pages/includes/footer.php"); ?>
<script src="../../js/evenement_challenges.js"></script>
<script src="../../js/sombre.js"></script>

</body>
</html>
