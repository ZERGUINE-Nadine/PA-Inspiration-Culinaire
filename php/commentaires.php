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

    // Vérification des données reçues
    if (!isset($data['comment'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Données invalides']);
        exit;
    }

    $comment = $data['comment'];

    require('../php/database/connect_to_datanase.php');

    try {
        $conn = get_database_connexion();

        // Déterminer si c'est un commentaire pour une recette ou un article
        if (isset($data['recette_id'])) {
            $recette_id = $data['recette_id'];
            $sql = "INSERT INTO commentaires (user_id, recette_id, contenue, date_commentaire) VALUES (:user_id, :recette_id, :contenue, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':recette_id', $recette_id, PDO::PARAM_INT);
        } elseif (isset($data['article_id'])) {
            $article_id = $data['article_id'];
            $sql = "INSERT INTO commentaires (user_id, article_id, contenue, date_commentaire) VALUES (:user_id, :article_id, :contenue, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Données invalides']);
            exit;
        }

        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':contenue', $comment, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de l\'ajout du commentaire']);
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
