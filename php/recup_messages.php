<?php
require('../php/database/connect_to_datanase.php');

try {
    $conn = get_database_connexion();
    if (!$conn) {
        die("Erreur de connexion à la base de données.");
    }

    $sql = "SELECT utilisateur.pseudo, canal_de_communication.contenu 
            FROM canal_de_communication 
            JOIN utilisateur ON canal_de_communication.id_utilisateur = utilisateur.id 
            ORDER BY canal_de_communication.id_communication DESC";
    $result = $conn->query($sql);

    if ($result->rowCount() > 0) {
        foreach ($result as $row) {
            echo "<div class='message'><p><strong>" . htmlspecialchars($row['pseudo']) . ":</strong> " . htmlspecialchars_decode($row['contenu']) . "</p></div>";
        }
    } else {
        echo "Aucun message trouvé.";
    }

    $conn = null;
} catch (PDOException $e) {
    echo "Erreur PDO : " . $e->getMessage();
}
?>
