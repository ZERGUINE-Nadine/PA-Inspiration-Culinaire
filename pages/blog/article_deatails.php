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

$article_id = null;
$user_id = $_SESSION['user']['id'];

// Vérifier si un ID d'article est passé dans l'URL
if (isset($_GET['article'])) {
    $article_id = $_GET['article'];
} else {
    // Rediriger ou gérer l'absence de paramètre article
    header("Location: ../../pages/erreur.php");
    exit();
}

$article = []; // Pour stocker les détails de l'article
$commentaires = []; // Pour stocker les commentaires
$avg_rating = null; // Pour stocker la note moyenne

try {
    // Obtenir la connexion à la base de données en utilisant la fonction
    $conn = get_database_connexion();

    if (!$conn) {
        die("Erreur de connexion à la base de données.");
    }

    // Requête SQL pour récupérer les détails de l'article spécifique
    $sql = "SELECT 
                article.id_article AS id_article,
                article.titre AS titre_article,
                article.description,
                article.date_publication,
                article.image_url
            FROM 
                article
            WHERE
                article.id_article = :article_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
    $stmt->execute();

    // Vérifie si des résultats sont retournés
    if ($stmt->rowCount() > 0) {
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        // Gérer le cas où aucun article n'est trouvé avec cet ID
        header("Location: ../../pages/erreur.php");
        exit();
    }

    // Requête pour récupérer les commentaires de l'article avec le nom d'utilisateur
    $sql_comments = "SELECT commentaires.contenue, utilisateur.id, utilisateur.pseudo AS utilisateur_pseudo
                     FROM commentaires
                     INNER JOIN utilisateur ON commentaires.user_id = utilisateur.id
                     WHERE commentaires.article_id = :article_id
                     ORDER BY commentaires.date_commentaire DESC";

    $stmt_comments = $conn->prepare($sql_comments);
    $stmt_comments->bindParam(':article_id', $article_id, PDO::PARAM_INT);
    $stmt_comments->execute();

    $commentaires = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);

    // Requête pour vérifier si l'article est dans les favoris
    $sql_is_favoris = 'SELECT * FROM favories_article WHERE id_article = :article_id and user_id = :user_id';
    $stm_is_favoris = $conn->prepare($sql_is_favoris);
    $stm_is_favoris->bindParam(':article_id', $article_id, PDO::PARAM_INT);
    $stm_is_favoris->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stm_is_favoris->execute();

    $is_favoris = $stm_is_favoris->rowCount() > 0;

    // Requête pour obtenir la note moyenne de l'article
    $sql_avg_rating = 'SELECT AVG(value) AS avg_rating FROM evaluation_article WHERE article_id = :article_id';
    $stm_avg_rating = $conn->prepare($sql_avg_rating);
    $stm_avg_rating->bindParam(':article_id', $article_id, PDO::PARAM_INT);
    $stm_avg_rating->execute();

    $avg_rating_row = $stm_avg_rating->fetch(PDO::FETCH_ASSOC);
    if ($avg_rating_row && $avg_rating_row['avg_rating'] !== null) {
        $avg_rating = round($avg_rating_row['avg_rating'], 1); // Arrondir à une décimale pour affichage
    } else {
        $avg_rating = 'Aucune évaluation';
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
    <title><?php echo htmlspecialchars($article['titre_article']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../assets/styles/hedaer.css">
    <link rel="stylesheet" href="../../assets/styles/sombre.css">
    <link rel="stylesheet" href="../../assets/styles/article_details.css">

    <script src="https://kit.fontawesome.com/5732949dd8.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php require("../../pages/includes/header.php") ?>
    <div class="container">
        <div class="top-section">
            <div class="fav">
                <div class="rectangle yellow">
                    <div class="dish"><?php echo htmlspecialchars($article['titre_article']); ?></div>
                    <img src="<?php echo htmlspecialchars($article['image_url']); ?>" alt="<?php echo htmlspecialchars($article['titre_article']); ?>">
                </div>
                
                <div class="rating-wrap">
                    <div class="center">
                        <fieldset class="rating">
                            <p>Evaluez cet article </p>
                            <input type="radio" id="star5" name="rating" value="5"/><label for="star5" class="full" title="Awesome"></label>
                            <input type="radio" id="star4.5" name="rating" value="4.5"/><label for="star4.5" class="half"></label>
                            <input type="radio" id="star4" name="rating" value="4"/><label for="star4" class="full"></label>
                            <input type="radio" id="star3.5" name="rating" value="3.5"/><label for="star3.5" class="half"></label>
                            <input type="radio" id="star3" name="rating" value="3"/><label for="star3" class="full"></label>
                            <input type="radio" id="star2.5" name="rating" value="2.5"/><label for="star2.5" class="half"></label>
                            <input type="radio" id="star2" name="rating" value="2"/><label for="star2" class="full"></label>
                            <input type="radio" id="star1.5" name="rating" value="1.5"/><label for="star1.5" class="half"></label>
                            <input type="radio" id="star1" name="rating" value="1"/><label for="star1" class="full"></label>
                            <input type="radio" id="star0.5" name="rating" value="0.5"/><label for="star0.5" class="half"></label>
                        </fieldset>
                    </div>
                    <button id="rating1" onclick="onRatingSubmit()">Evaluer</button>
                    <h4 id="rating-value"><?php echo htmlspecialchars(number_format($avg_rating, 2)) . " / 5"; ?></h4>
                </div>

                <div class="favorite-button">
                    <button onclick="onAddClick()" id="add-to-favorites"><?php echo $is_favoris ? 'Retirer des favoris' : 'Ajouter aux favoris'; ?></button>
                </div>

            </div>
            <div class="rectangle white">
                <p><?php echo nl2br(htmlspecialchars($article['description'])); ?></p>
                <p>Publié le : <?php echo htmlspecialchars($article['date_publication']); ?></p>
            </div>
        </div>
        <div class="bottom-section">
            <div class="rectangle grey">
                <div class="comments">
                    <h3>Commentaires</h3>
                    <section id="commentaires" class='comments'>
                        <?php if (!empty($commentaires)) : ?>
                            <ul>
                                <?php foreach ($commentaires as $comment) : ?>
                                    <li>
                                        <strong><?php echo htmlspecialchars($comment['utilisateur_pseudo']); ?></strong>
                                        <p><?php echo htmlspecialchars($comment['contenue']); ?></p>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <p>Aucun commentaire pour le moment.</p>
                        <?php endif; ?>
                    </section>
                    <form id="comment-form">
                        <textarea id="comment" name="comment" rows="4" placeholder="Ajouter un commentaire"></textarea><br>
                        <input type="hidden" id="article_id" value="<?php echo htmlspecialchars($article_id); ?>">
                        <button type="button" onclick="onAddCommentClick()" id="add-comment">Ajouter Commentaire</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="../../js/article_details.js"></script>
    <script src="../../js/sombre.js"></script>

</body>

</html>
