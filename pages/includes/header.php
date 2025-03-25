<?php
$user = null;
$is_authenticated = false;
$current_page = basename($_SERVER['REQUEST_URI'], ".php");

if ($_SESSION && !empty($_SESSION)) {
    $is_authenticated = true;
    $user = $_SESSION['user'];
}
?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<header>
    <div class="container">
        <img class="logo" src="../../../inspiration_culinaire/assets/images/Logoo.png" alt="Logo" style="width: 95px; height:auto;">
        <nav class="nav-menu">
            <ul>
                <li><a href="../../../inspiration_culinaire/index.php" class="<?= $current_page == 'index' ? 'active' : '' ?>">Accueil</a></li>
                <li><a href="../../../inspiration_culinaire/pages/recettes/categories.php" class="<?= $current_page == 'categories' ? 'active' : '' ?>">Recette</a></li>
                <li><a href="../../../inspiration_culinaire/pages/blog/blog.php" class="<?= $current_page == 'blog' ? 'active' : '' ?>">Blog</a></li>
                <li><a href="../../../inspiration_culinaire/pages/contact.php" class="<?= $current_page == 'contact' ? 'active' : '' ?>">Contact</a></li>
            </ul>
        </nav>
        <?php if (!$is_authenticated) { ?>
        <div class="ButtonCo">
            <a href="../../../inspiration_culinaire/pages/login.php" class="Inscription Connexion">Se Connecter</a>
            <a href="../../../inspiration_culinaire/pages/includes/captcha.php" class="Inscription">S'enregistrer</a>
        </div>
        <?php } ?>
        <?php if ($is_authenticated) { ?>
    <div class="ButtonCo1">
        <a href="../../../inspiration_culinaire/pages/profil.php" class="Profil Connexion">
            <i class="fas fa-user"></i> <?php echo $user['pseudo'] ?>
        </a>
        <a href="../../../inspiration_culinaire/php/logout.php" class="Deconnexion">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
        </a>
    </div>
<?php } ?>

    </div>
</header>
