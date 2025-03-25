<?php
session_start();

// Vérification de la session si l'utilisateur n'est pas connecté
$is_authenticated = false;
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    $is_authenticated = true;
} else {
    header("Location: ../../pages/login.php");
    exit();
}

// Inclure le fichier de fonction de connexion à la base de données
require('../../php/database/connect_to_datanase.php');

$messages = []; // Initialisation de la variable $messages

try {
    $conn = get_database_connexion();
    if (!$conn) {
        die("Erreur de connexion à la base de données.");
    }

    // Requête SQL pour récupérer les messages dans l'ordre croissant
    $sql = "SELECT utilisateur.id AS id_utilisateur, utilisateur.pseudo, canal_de_communication.contenu 
            FROM canal_de_communication 
            JOIN utilisateur ON canal_de_communication.id_utilisateur = utilisateur.id 
            ORDER BY canal_de_communication.id_communication ASC"; // ASC pour ordre croissant
    $result = $conn->query($sql);

    if ($result->rowCount() > 0) {
        foreach ($result as $row) {
            $messages[] = $row;
        }
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
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inspiration Culinaire</title>
    <link rel="stylesheet" href="../../assets/styles/blog.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/styles/hedaer.css"/>
    <link rel="stylesheet" href="../../assets/styles/mini_header.css"/>
    <link rel="stylesheet" href="../../assets/styles/sombre.css"/>
    <script src="https://kit.fontawesome.com/5732949dd8.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php require("../../pages/includes/header.php"); require("../../pages/includes/mini_header.php"); ?>
    <div class="message-container">
        <div class="messages" id="messages">
            <?php foreach ($messages as $message): ?>
                <div class="message <?= $message['id_utilisateur'] == $_SESSION['user']['id'] ? 'sent' : 'received' ?>">
                    <p><strong><?= htmlspecialchars($message['pseudo']) ?>:</strong> <?= htmlspecialchars($message['contenu']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <form class="message-form" action="../../php/envoi_message.php" method="post">
            <textarea name="message" id="message" cols="30" rows="2" placeholder="Votre message ici" required></textarea>
            <button type="submit">Envoyer</button>
        </form>
    </div>
    <script>
        // Fonction pour faire défiler vers le bas
        function scrollToBottom() {
            const messages = document.getElementById('messages');
            messages.scrollTop = messages.scrollHeight;
        }

        // Appeler scrollToBottom au chargement de la page
        window.onload = scrollToBottom;
    </script>
    <script src="../../js/sombre.js"></script>
</body>
</html>
