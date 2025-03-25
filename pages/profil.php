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
require('../php/database/connect_to_datanase.php');

try {
    // Obtenir la connexion à la base de données en utilisant la fonction
    $conn = get_database_connexion();

    if (!$conn) {
        die("Erreur de connexion à la base de données.");
    }

    // Récupérer les informations de l'utilisateur actuellement connecté
    $user_id = $_SESSION['user']['id'];

    // Récupérer les favoris de l'utilisateur
    $sql_favorites = "
    SELECT r.* 
    FROM favories f
    JOIN recette r ON f.id_recette = r.id_recette
    WHERE f.user_id = :user_id";

    $stmt_favorites = $conn->prepare($sql_favorites);
    $stmt_favorites->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_favorites->execute();
    $favorites = $stmt_favorites->fetchAll(PDO::FETCH_ASSOC);

    $sql_favorites_article = "
    SELECT fa.*, a.*
    FROM favories_article fa
    JOIN article a ON fa.id_article = a.id_article
    WHERE fa.user_id = :user_id
    ";

    $stmt_favorites_article = $conn->prepare($sql_favorites_article);
    $stmt_favorites_article->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_favorites_article->execute();
    $favorites_articles = $stmt_favorites_article->fetchAll(PDO::FETCH_ASSOC);
    

    // Récupérer les suivis de l'utilisateur
    $sql_suivi = "SELECT suivi.chef_id, chefs.nom, chefs.specialite 
                  FROM suivi 
                  JOIN chefs ON suivi.chef_id = chefs.id_chefs 
                  WHERE suivi.user_id = :user_id";

    $stmt_suivi = $conn->prepare($sql_suivi);
    $stmt_suivi->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_suivi->execute();
    $chefs_suivis = $stmt_suivi->fetchAll(PDO::FETCH_ASSOC);

    // Debug: print the followed chefs
    // echo '<pre>' . print_r($chefs_suivis, true) . '</pre>';

    // Fermer la connexion à la base de données
    $conn = null;

} catch (PDOException $e) {
    echo "Erreur PDO : " . $e->getMessage();
    exit();
}

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil utilisateur</title>
    <link rel="stylesheet" href="../assets/styles/profil.css">
    <link rel="stylesheet" href="../assets/styles/hedaer.css">
    <link rel="stylesheet" href="../assets/styles/footer.css">
    <link rel="stylesheet" href="../assets/styles/sombre.css">
    <script>
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.profile-section').forEach(section => {
                section.style.display = 'none';
            });
            // Show the selected section
            document.getElementById(sectionId).style.display = 'block';
        }

        // Show the default section
        document.addEventListener('DOMContentLoaded', () => {
            showSection('modifier-mon-profil');
        });
    </script>
</head>
<body>
    <?php require("../pages/includes/header.php"); ?>

    <main class="profile-container">
        <nav class="profile-nav">
            <ul>
                <li><a href="#" onclick="showSection('modifier-mon-profil')">Modifier mon profil</a></li>
                <li><a href="#" onclick="showSection('suivi')">Suivi(e)s</a></li>
                <li><a href="#" onclick="showSection('mes-favorites')">Mes favorites</a></li>
                <li><a href="../pages/contact.php" onclick="showSection('contacter-nous')">Contacter nous</a></li>
                <li><button id="dark-mode-toggle">Mode Sombre </button></li>

                <li><a href="../php/logout.php" style="color: red; font-weight: bold; border-bottom: 2px solid red; padding-bottom: 2px; text-decoration: none;">Déconnexion</a></li>
            </ul>
        </nav>

        <section id="modifier-mon-profil" class="profile-section">
            <h2>Modifier mon profil</h2>
            <!-- Form to update profile information -->
            <form>
                <label for="pseudo-nom">Pseudo Nom</label>
                <input type="text" id="pseudo-nom" name="pseudo-nom">
                <label for="adresse-mail">Adresse mail</label>
                <input type="email" id="adresse-mail" name="adresse-mail">
                <label for="mot-de-passe">Mot de passe</label>
                <input type="password" id="mot-de-passe" name="mot-de-passe">
                <button type="submit">Enregistrer</button>
            </form>
        </section>

        <section id="suivi" class="profile-section">
            <h2>Suivi(e)s</h2>
            <div class="row">
                <div class="col-md-12">
                    <?php if (!empty($chefs_suivis)) { ?>
                        <ul class="list-group">
                            <?php foreach ($chefs_suivis as $chef) { ?>
                                <li class="list-group-item">
                                    <strong><?php echo htmlspecialchars($chef['nom']); ?></strong> - <?php echo htmlspecialchars($chef['specialite']); ?>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } else { ?>
                        <p>Aucun chef suivi.</p>
                    <?php } ?>
                </div>
            </div>
        </section>

        <section id="mes-favorites" class="profile-section">
            <h2>Mes favorites</h2>
            <?php if (!empty($favorites)) : ?>
                <ul>
                    <?php foreach ($favorites as $favorite) : ?>
                        <li>
                            <a href="<?php echo './recettes/details_recette.php?recette=' . $favorite['id_recette']; ?>"><?php echo $favorite['nom']; ?></a>
                        </li>
                    <?php  endforeach; ?>
                    <?php foreach ($favorites_articles as $favorite) : ?>
                        <li>
                            <a href="<?php echo '../pages/blog/article_deatails.php?article=' . $favorite['id_article']; ?>"><?php echo $favorite['titre']; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>Aucun favori trouvé.</p>
            <?php endif; ?>
        </section>

    </main>

    <?php require("../pages/includes/footer.php"); ?>
    <script src="../js/sombre.js"></script>


</body>
</html>
