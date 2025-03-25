<?php
// Inclure le fichier de connexion à la base de données
require_once('database/connect_to_datanase.php'); // Assurez-vous que ce chemin est correct

try {
    // Établir la connexion à la base de données
    $conn = get_database_connexion(); // Assurez-vous que cette fonction retourne un objet PDO valide

    // Préparer la requête SQL pour obtenir la recette la mieux notée
    $sql = "
        SELECT r.id_recette AS id_recette, r.nom AS nom_recette, r.image_url, AVG(e.value) AS avg_rating
        FROM recette r
        LEFT JOIN evaluation e ON r.id_recette = e.recette_id
        GROUP BY r.id_recette
        ORDER BY avg_rating DESC
        LIMIT 1
    ";

    // Exécuter la requête
    $stmt = $conn->query($sql);

    // Récupérer le résultat
    $best_recipe = $stmt->fetch(PDO::FETCH_ASSOC); // Assurez-vous de spécifier le mode de fetch approprié

    // Si aucune recette n'est trouvée, définir $best_recipe à null
    if (!$best_recipe) {
        $best_recipe = null;
    }

    // Fermer la connexion à la base de données
    $conn = null;
} catch (PDOException $e) {
    // Afficher un message d'erreur si une exception PDO est lancée
    echo "Erreur PDO : " . $e->getMessage();
}
?>
