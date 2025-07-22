<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = getenv("LOCKMIND_EMAIL_SENDER");
    $mail->Password = getenv("LOCKMIND_EMAIL_PASS");
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Remetente e destinatário
    $mail->setFrom(getenv("LOCKMIND_EMAIL_SENDER"), "Lockmind - Administrador");
    $mail->addAddress("email@gmail.com", 'Destinatário');

    // Conteúdo
    $mail->isHTML(true);
    $mail->Subject = 'Assunto do Email';
    $mail->Body = '<strong>Este é o corpo em HTML!</strong>';
    $mail->AltBody = 'Este é o corpo em texto puro.';

    $mail->send();
    echo 'Mensagem enviada com sucesso';

    header("Location: homepage.php");

} catch (Exception $e) {
    echo "Erro ao enviar: {$mail->ErrorInfo}";
}
?>