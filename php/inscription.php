<?php
session_start();

// Vérification de la session si l'utilisateur n'est pas connecté (redirection page login)
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

// Inclure le fichier de fonction de connexion à la base de données
require('../php/database/connect_to_datanase.php');

// Récupérer l'ID du challenge depuis l'URL
$challenge_id = isset($_POST['id']) ? intval($_POST['id']) : null;

if ($challenge_id === null) {
    echo json_encode(['success' => false, 'message' => 'No challenge ID provided.']);
    exit();
}

try {
    // Obtenir la connexion à la base de données en utilisant la fonction
    $conn = get_database_connexion();

    if (!$conn) {
        echo json_encode(['success' => false, 'message' => 'Database connection error.']);
        exit();
    }

    // Requête SQL pour incrémenter nb_inscrit
    $sql = "UPDATE challenge SET nb_inscrit = nb_inscrit + 1 WHERE id_challenge = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $challenge_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Successfully registered.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Update failed: ' . $stmt->errorInfo()[2]]);
    }

    $conn = null; // Ferme la connexion à la base de données

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'PDO error: ' . $e->getMessage()]);
}
?>
