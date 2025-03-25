<?php
session_start();

$is_liked = false;
$is_authenticated = false;



require('../../php/database/connect_to_datanase.php');

$recette_id = null;

if (isset($_GET['recette'])) {
    $recette_id = $_GET['recette'];
} else {
    header("Location: ../../pages/erreur.php");
    exit();
}

$recette = []; 
$avg_rating = 0;

try {
    $conn = get_database_connexion();

    if (!$conn) {
        die("Erreur de connexion à la base de données.");
    }

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
                recette.id_recette = :recette_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':recette_id', $recette_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $recette = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        header("Location: ../../pages/erreur.php");
        exit();
    }

    $sql_comments = "SELECT commentaires.contenue, utilisateur.id, utilisateur.pseudo AS utilisateur_pseudo
                     FROM commentaires
                     INNER JOIN utilisateur ON commentaires.user_id = utilisateur.id
                     WHERE commentaires.recette_id = :recette_id
                     ORDER BY commentaires.date_commentaire DESC";

    $stmt_comments = $conn->prepare($sql_comments);
    $stmt_comments->bindParam(':recette_id', $recette_id, PDO::PARAM_INT);
    $stmt_comments->execute();

    $commentaires = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);

    $sql_is_favoris = 'SELECT * FROM favories WHERE id_recette = :recette_id and user_id = :user_id';
    $stm_is_favoris = $conn->prepare($sql_is_favoris);
    $stm_is_favoris->bindParam(':recette_id', $recette_id, PDO::PARAM_INT);
    $stm_is_favoris->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stm_is_favoris->execute();

    if ($stm_is_favoris->rowCount() > 0) {
        $is_liked = true;
    }

    $sql_avg_rating = 'SELECT AVG(value) AS avg_rating FROM evaluation WHERE recette_id = :recette_id';
    $stm_avg_rating = $conn->prepare($sql_avg_rating);
    $stm_avg_rating->bindParam(':recette_id', $recette_id, PDO::PARAM_INT);
    $stm_avg_rating->execute();

    $avg_rating_row = $stm_avg_rating->fetch(PDO::FETCH_ASSOC);
    if ($avg_rating_row && $avg_rating_row['avg_rating'] !== null) {
        $avg_rating = $avg_rating_row['avg_rating'];
    }

    $conn = null; 
} catch (PDOException $e) {
    echo "Erreur PDO : " . $e->getMessage();
}
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($recette['nom_recette']); ?></title>
    <link rel="stylesheet" href="../../assets/styles/details_recette.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../assets/styles/hedaer.css">
    <link rel="stylesheet" href="../../assets/styles/sombre.css">

    <script src="https://kit.fontawesome.com/5732949dd8.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php require("../../pages/includes/header.php") ?>
    <div class="container">
        <div class="top-section">
            <div class="fav">
                <div class="rectangle yellow">
                    <div class="dish"><?php echo htmlspecialchars($recette['nom_recette']); ?></div>
                    <img src="<?php echo htmlspecialchars($recette['image_url']); ?>" alt="<?php echo htmlspecialchars($recette['nom_recette']); ?>">
                </div>

                <div class="rating-wrap">
                    <div class="center">
                        <form id="rating-form">
                            <fieldset class="rating">
                                <p>Evaluez cette recette </p>
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
                            <button type="button" onclick="onRatingSubmit()">Submit Rating</button>
                        </form>
                    </div>
                    <h4 id="rating-value"><?php echo htmlspecialchars(number_format($avg_rating, 2)) . " / 5"; ?></h4>
                </div>

                <div class="favorite-button">
                    <button onclick="onAddClick()" id="add-to-favorites">Ajouter aux favoris</a>

                </div>
            </div>
            <div class="rectangle white">
                <p><?php echo nl2br(htmlspecialchars($recette['instruction'])); ?></p>
                <p> Temps : <?php echo htmlspecialchars($recette['temps']); ?></p>
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
                        <input type="hidden" id="recette_id" value="<?php echo htmlspecialchars($recette_id); ?>">
                        <button type="button" onclick="onAddcommentClick()" id="add-to-comments">Envoyer</button>
                    </form>
                </div>
            </div>
        </div>


    </div>
</body>
<script src="../../js/details_recette.js"></script>
<script src="../../js/sombre.js"></script>


</html>