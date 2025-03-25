<?php
session_start();

// Vérification de la session si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: ../../pages/login.php");
    exit();
}

// Inclure le fichier de fonction de connexion à la base de données
require('../php/database/connect_to_datanase.php');

try {
    $conn = get_database_connexion();
    if (!$conn) {
        die("Erreur de connexion à la base de données.");
    }

    // Préparer et exécuter la requête pour insérer le message
    $stmt = $conn->prepare("INSERT INTO canal_de_communication (id_utilisateur, contenu) VALUES (:id_utilisateur, :contenu)");
    $stmt->bindParam(':id_utilisateur', $_SESSION['user']['id']);
    $stmt->bindParam(':contenu', $_POST['message']);
    $stmt->execute();

    $conn = null; // Ferme la connexion à la base de données
} catch (PDOException $e) {
    echo "Erreur PDO : " . $e->getMessage();
}

// Rediriger vers la page de chat
header("Location: ../pages/blog/blog.php ");

exit();
