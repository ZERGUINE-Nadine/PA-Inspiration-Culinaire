<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Utilisateur non authentifié']);
        exit;
    }

    $user_id = $_SESSION['user']['id'];
    $data = json_decode(file_get_contents('php://input'), true);
    $type = "";
    // Vérifier si c'est une recette ou un article
    if (isset($data['recette_id'])) {
        $recette_id = $data['recette_id'];
        $type = 'recette';
    } elseif (isset($data['article_id'])) {
        $article_id = $data['article_id'];
        $type = 'article';
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Aucun ID de recette ou d\'article fourni']);
        exit;
    }

    require('../php/database/connect_to_datanase.php');

    try {
        $conn = get_database_connexion();

        if ($type === 'recette') {
            $sql = "INSERT INTO favories (user_id, id_recette) VALUES (:user_id, :id_recette)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':id_recette', $recette_id, PDO::PARAM_INT);
        } elseif ($type === 'article') {
            $sql = "INSERT INTO favories_article (user_id, id_article) VALUES (:user_id, :id_article)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':id_article', $article_id, PDO::PARAM_INT);
        }

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de l\'ajout aux favoris']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur PDO : ' . $e->getMessage()]);
    } finally {
        $conn = null;
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Méthode non autorisée']);
}
?>
