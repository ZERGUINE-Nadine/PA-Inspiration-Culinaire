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

    if (isset($data['chef_id']) && isset($data['action'])) {
        $chef_id = $data['chef_id'];
        $action = $data['action'];
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'ID de chef ou action non fournie']);
        exit;
    }

    require('../php/database/connect_to_datanase.php');

    try {
        $conn = get_database_connexion();

        if ($action === 'follow') {
            // Ajouter un suivi
            $stmt = $conn->prepare("INSERT INTO suivi (user_id, chef_id) VALUES (:user_id, :chef_id);");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':chef_id', $chef_id, PDO::PARAM_INT);
            $stmt->execute();
            $response['success'] = true;
            $response['message'] = 'Vous suivez maintenant ce chef.';
        } elseif ($action === 'unfollow') {
            // Supprimer un suivi
            $stmt = $conn->prepare("DELETE FROM suivi WHERE user_id = :user_id AND chef_id = :chef_id;");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':chef_id', $chef_id, PDO::PARAM_INT);
            $stmt->execute();
            $response['success'] = true;
            $response['message'] = 'Vous ne suivez plus ce chef.';
        } else {
            $response['message'] = 'Action non valide.';
        }

        echo json_encode($response);

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
