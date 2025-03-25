<?php

//kemeltih <3  bravo a moi 

require './PHPMailer-master/src/Exception.php';
require './PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $json_data = json_decode(file_get_contents('php://input'), true);

    $name = $json_data['name'];
    $email = $json_data['email'];
    $message =$json_data['message'];
    

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

    $mail->setFrom($email, 'Client service');
    $mail->addAddress('zerguinenadine@gmail.com', $na);
    $mail->isHTML(true);
    $mail->Subject = 'Message ';
    $mail->Body    ="<div> 
                    $message
                    </div>";
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();



    http_response_code(200);
    header('Content-Type: application/json');
    $response = [
        'status' => true,
        'message' => 'Message bien envoyé'
    ];

    echo json_encode($response);
}

?>