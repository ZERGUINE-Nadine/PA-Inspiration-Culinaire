<?php
session_start();

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'User not authenticated']);
    exit();
}

require('../php/database/connect_to_datanase.php');


$data = json_decode(file_get_contents("php://input"), true);


try {
    $conn = get_database_connexion();

    if (!$conn) {
        echo json_encode(['success' => false, 'error' => 'Database connection failed']);
        exit();
    }

    if ($data['type'] === 'article') {
        $rating = $data['rating'];
        $user_id = $_SESSION['user']['id'];
        $article_id = $data['article_id'];


        $sql = "INSERT INTO evaluation_article (article_id, user_id, value) 
        VALUES (:article_id, :user_id, :value)
        ON DUPLICATE KEY UPDATE value = :value";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':value', $rating, PDO::PARAM_INT);

        $stmt->execute();
    } else {
        $recette_id = $data['recette_id'];
        $rating = $data['rating'];
        $user_id = $_SESSION['user']['id'];

        $sql = "INSERT INTO evaluation (recette_id, user_id, value) 
        VALUES (:recette_id, :user_id, :value)
        ON DUPLICATE KEY UPDATE value = :value";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':recette_id', $recette_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':value', $rating, PDO::PARAM_INT);

        $stmt->execute();
    }
    


    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
