<?php

require './database/connect_to_datanase.php';

$pdo = get_database_connexion();

$sql = "SELECT * FROM recette";
$stm = $pdo->prepare($sql);

$stm->execute();
$data = $stm->fetchAll();


$query = isset($_GET['query']) ? strtolower($_GET['query']) : '';

$results = array_filter($data, function($item) use ($query) {
    return strpos(strtolower($item['nom']), $query) !== false;
});

echo json_encode(array_values($results));