<?php
require('../database/connect_to_datanase.php');
require('../../errors/error_logger.php');

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';


log_php_errors('../../errors/php_logs.txt');



if($_SERVER['REQUEST_METHOD'] == 'POST') {


    $json_data = json_decode(file_get_contents('php://input'), true);
    
    $code = $json_data['code'];
    $email = $json_data['email'];

    $pdo = get_database_connexion();

    $sql = 'SELECT * FROM utilisateur WHERE email = :email';
    $stm = $pdo->prepare($sql);
    $stm->execute(["email" => $email]);
    
    $result = $stm->fetch(PDO::FETCH_ASSOC);

    if ($result['code_de_validation'] != $code) {
        http_response_code(400);
        $response = [
            'status' => false,
            'message' => 'code de vérification non valide'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    $sql = 'UPDATE utilisateur SET validation = true WHERE email = :email';
    $stm = $pdo->prepare($sql);
    $stm->execute(["email" => $email]);

    http_response_code(200);
    header('Content-Type: application/json');
    $response = [
        'status' => true,
        'message' => 'utilisateur vérifier'
    ];

    echo json_encode($response);
}

?>