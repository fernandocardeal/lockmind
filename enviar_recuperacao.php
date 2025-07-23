<?php

require 'funcoes.php';

$email = $_POST['email'] ?? '';

if (!$email) {
    die('E-mail é obrigatório.');
}

$host = getenv("LOCKMIND_DB_HOST");
$user = getenv("LOCKMIND_DB_USER");
$pass = getenv("LOCKMIND_DB_PASS");
$base = getenv("LOCKMIND_DB_DATABASE");

$conn = new mysqli($host, $user, $pass, $base);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT id FROM usuario WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die('E-mail não encontrado.');
} else {
    $stmt->bind_result($id);
    $stmt->fetch();
}

$token = bin2hex(random_bytes(32));
$expira_em = date('Y-m-d H:i:s', strtotime('+1 hour'));

$stmt = $conn->prepare("INSERT INTO token_recuperacao (usuario_id, token, expira_em) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $id, $token, $expira_em);
$stmt->execute();

$link = "https://lionfish-refined-similarly.ngrok-free.app/redefinir_senha.php?token=$token";

$assunto = "Esqueceu sua senha??? - Lockmind";
$body = "
    <p>Olá,</p>
    <p>Você solicitou a recuperação da sua senha no Lockmind.</p>
    <p>Clique no link abaixo para redefinir sua senha:</p>
    <p><a href='$link'>$link</a></p>
    <p>Esse link expira em 1 hora. Se você não fez essa solicitação, ignore este e-mail.</p>
";

if (enviarEmail($email, $assunto, $body)) {
    header("Location: recuperar_senha.php?success=1");
} else {
    echo "Erro ao enviar e-mail.";
}

?>