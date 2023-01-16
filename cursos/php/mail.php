<?php

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

if (!class_exists('PHPMailer\PHPMailer\Exception')){
	require 'PHPMailer/src/Exception.php';
	require 'PHPMailer/src/PHPMailer.php';
	require 'PHPMailer/src/SMTP.php';

}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);
$cliente = $_POST['cliente'];

try {
    //Server settings
    $mail->SMTPDebug = false;                      // Enable verbose debug output --SMTP::DEBUG_SERVER
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'premium152.web-hosting.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'certificados@inteslaeducation.com';                     // SMTP username
    $mail->Password   = 'XKgvKvxKu-&K';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged //ENCRYPTION_SMTPS
    $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('certificados@inteslaeducation.com', 'Intesla Education');
    $mail->addAddress($cliente);     // Add a recipient
    

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
		$mail->CharSet = 'UTF-8';

		$mail->Subject = 'Certificado Intesla: '.$_POST['nombreCurso'];
		$correo = $cliente;
		
		$respuesta = file_get_contents("./plantilla.php" );
		$respuesta = str_replace('nombreCambiante', $_POST['nombre'], $respuesta);
		$respuesta = str_replace('cursoCambiante', $_POST['nombreCurso'], $respuesta);
		if($_POST['codigo']!=''){
			$respuesta = str_replace('codigoCambiante', $_POST['codigo'] , $respuesta);
			$respuesta = str_replace('linkCambiante', 'https://inteslaeducation.com/certificados/certificados/index.php?dni='. base64_encode($_POST['codigo']).'&curso='.base64_encode($_POST['idCurso']), $respuesta);
		}else if( $_POST['pers']!=''){
			$respuesta = str_replace('codigoCambiante', $_POST['pers'] , $respuesta);
			$respuesta = str_replace('linkCambiante', 'https://inteslaeducation.com/certificados/certificados/index.php?pers='. base64_encode($_POST['pers']).'&curso='.base64_encode($_POST['idCurso']), $respuesta);
		}else{
			$respuesta = str_replace('linkCambiante', 'https://inteslaeducation.com/' , $respuesta);
		}
		
		$mail->Body    = $respuesta;
		
    

    $mail->send();
    //echo 'Mensaje Enviado';
} catch (Exception $e) {
    //echo "Correo no pudo ser enviado. Error en: {$mail->ErrorInfo}";
}