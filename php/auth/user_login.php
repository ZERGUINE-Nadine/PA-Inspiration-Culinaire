<?php 
session_start();

// les includes (error, database)
require('../database/connect_to_datanase.php');
require('../../errors/error_logger.php');
log_php_errors('../../errors/php_logs.txt');


// vérification du types de la requetes 
$requtest_type = $_SERVER['REQUEST_METHOD']; 
if ($requtest_type !== 'POST') {
    error_log('Unauthorized access attempt');
    header('Location: ../../../pages/includes/unauthoriezed.html');
    exit;
}

$json_data = json_decode(file_get_contents('php://input'), true);

if (!$json_data) {
    error_log('Bad request: Invalid JSON');
    header('Location: ../../../pages/includes/bad_request.html');
    exit;
}

$email = $json_data['email'];
$password = $json_data['password'];

// envoie de la requetes sql ( SELECT )
$pdo = get_database_connexion();

$sql = 'SELECT * FROM utilisateur WHERE email = :email';
$statement = $pdo->prepare($sql);
$statement->execute(['email' => $email]);
$user = $statement->fetch(PDO::FETCH_ASSOC);  

// vérification si l'utilisateur exists //
if (!$user) {
    $response = [
        'success' => false,
        'message' => "Adresse email n'éxiste pas"
    ];
    http_response_code(404);            // not found // 
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// vérification du mot de passe //
$verified = password_verify($password, $user['mot_de_passe']);
if (!$verified) {
    $response = [
        'success' => false,
        'message' => "Adreese mail ou mot de passe non valide",
    ];
    http_response_code(400);        // bad request //
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

$_SESSION['user'] = $user;      // stockage de session utilisateur 
$response = [
    'success' => true,
    'message' => 'utilisateur connecter',
    'user' => $user,
];
http_response_code(200);
header('Content-Type: application/json');
echo json_encode($response);
