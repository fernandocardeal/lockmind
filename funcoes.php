<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function criptografarSenha($senha)
{
    $chave = getenv("LOCKMIND_DB_KEY");
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $senha_crip = openssl_encrypt($senha, 'aes-256-cbc', $chave, 0, $iv);
    return base64_encode($iv . $senha_crip);
}

function descriptografarSenha($senha_criptografada_base64)
{
    $chave = getenv("LOCKMIND_DB_KEY");
    $dados = base64_decode($senha_criptografada_base64);
    $iv = substr($dados, 0, openssl_cipher_iv_length('aes-256-cbc'));
    $senha_crip = substr($dados, openssl_cipher_iv_length('aes-256-cbc'));
    return openssl_decrypt($senha_crip, 'aes-256-cbc', $chave, 0, $iv);
}

function enviarEmail($destinatario, $assunto, $corpo)
{
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = getenv("LOCKMIND_EMAIL_SENDER");
        $mail->Password = getenv("LOCKMIND_EMAIL_PASS");
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Remetente e destinatário
        $mail->setFrom(getenv("LOCKMIND_EMAIL_SENDER"), "Lockmind - Administrador");
        $mail->addAddress($destinatario);

        // Conteúdo
        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body = $corpo;
        $mail->AltBody = strip_tags($corpo);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        $mail->send();
        return true;

    } catch (Exception $e) {
        return false;
    }
}
?>