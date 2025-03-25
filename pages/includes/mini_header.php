<?php
$user = null;
$is_authenticated = false;
$current_page = basename($_SERVER['REQUEST_URI'], ".php");

if ($_SESSION && !empty($_SESSION)) {
    $is_authenticated = true;
    $user = $_SESSION['user'];
}
?>

<header>
    <div class="container">
        <nav class="mini-menu">
            <ul class="mini">
                <li class="m"><a href="../../../inspiration_culinaire/pages/blog/blog.php" class="<?= $current_page == 'blog' ? 'active' : '' ?>">Chat</a></li>
                <li class="m"><a href="../../../inspiration_culinaire/pages/blog/articles.php" class="<?= $current_page == 'articles' ? 'active' : '' ?>">Articles</a></li>
                <li class="m"><a href="../../../inspiration_culinaire/pages/blog/evenement.php" class="<?= $current_page == 'evenement' ? 'active' : '' ?>">Evénements et Challenges</a></li>
                <li class="m"><a href="../../../inspiration_culinaire/pages/blog/chefs.php" class="<?= $current_page == 'chefs' ? 'active' : '' ?>">Chefs</a></li>
            </ul>
        </nav>
    </div>
</header>
