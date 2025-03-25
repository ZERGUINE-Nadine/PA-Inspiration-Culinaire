<?php

function get_database_connexion()
{

    $host = "localhost";
    $dbname = "inspiration_culinaire";
    $username = "root";
    $password = "root";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        return $pdo;
    } catch (PDOException $e) {
        echo "Échec de la connexion : " . $e->getMessage();
    }
}


?>
