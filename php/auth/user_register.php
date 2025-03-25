<?php
require('../database/connect_to_datanase.php');
require('../../errors/error_logger.php');

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

log_php_errors('../../errors/php_logs.txt');

$requtest_type = $_SERVER['REQUEST_METHOD'];  // le type de la requete http 

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

$name = $json_data['name'];
$email = $json_data['email'];
$password = $json_data['password'];

// vérification si l'utilisateur exists ou pas
$pdo = get_database_connexion();
$sql = 'SELECT * FROM utilisateur WHERE email = :email';
$statement = $pdo->prepare($sql);
$statement->execute(['email' => $email]);
$user_exist = $statement->fetch(PDO::FETCH_ASSOC);

if ($user_exist) {
    http_response_code(400);
    $respone = [
        'status' => false,
        'message' => 'ce compte éxist déja'
    ];
    echo json_encode($respone);
    exit;
}

// vérification si l'adresse email est valide
$email_validated = filter_var($email, FILTER_VALIDATE_EMAIL);
if (!$email_validated) {
    http_response_code(400);
    $respone = [
        'status' => false,
        'message' => "addresse email n'est pas valide",
    ];
    echo json_encode($respone);
    exit;
}

// hashage de mot de passe // 
$hashed_password = password_hash($password, PASSWORD_ARGON2I);
$validation_code = random_int(100000, 999999);


$pdo = get_database_connexion();
$sql = 'INSERT INTO utilisateur (pseudo, email, mot_de_passe, role, specialite_chef, code_de_validation) VALUES (:pseudo ,:email, :mot_de_passe, :role, :specialite_chef, :code_de_validation)';
$statement = $pdo->prepare($sql);
$statement->execute([
    'pseudo' => $name,
    'email' => $email,
    'mot_de_passe' => $hashed_password,
    'role' => 'USER',
    'specialite_chef' => 'null',
    'code_de_validation' => $validation_code
]);

$mail = new PHPMailer(true);

// Server settings
$mail->SMTPDebug = 0;
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'zerguinenadine@gmail.com';
$mail->Password = 'etvatalquibxwbit';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('zerguinenadine@gmail.com', 'Mailer');
$mail->addAddress($email, $name);
$mail->isHTML(true);
$mail->Subject = 'Code de verification de votre compte sur Inspiration Culinaire  ';
$mail->Body    ="<div> 
                <h1>Bonjour,</h1>
                <p> Voici votre code de vérification  $validation_code  
                Veuiller le compléter sur votre site Inspiration culinaire
                
                Cordialement,</p>
                 </div>";
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

$mail->send();
http_response_code(201);
header('Content-Type: application/json');
$response = [
    "status" => 'success',
    "message" => "un email de véfification $ été envoyé à $email"
];

echo json_encode($response);
exit;
