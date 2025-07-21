<?php
session_start();

require 'funcoes.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: newpass.php');
    exit();
}

$host = getenv("LOCKMIND_DB_HOST");
$user = getenv("LOCKMIND_DB_USER");
$pass = getenv("LOCKMIND_DB_PASS");
$base = getenv("LOCKMIND_DB_DATABASE");


$conn = new mysqli($host, $user, $pass, $base);

if ($conn->connect_error) {
    die('Erro de conexão: ' . $conn->connect_error);
}

$servico = trim($_POST['service'] ?? '');
$usuario_servico = trim($_POST['service_user'] ?? '');
$senha = trim($_POST['service_password'] ?? '');

$senha_hash = criptografarSenha($senha);

$user_id = $_SESSION['usuario_id'];

$stmt = $conn->prepare("INSERT INTO senha (user_id, service, service_user, service_password) VALUES (?, ?, ?, ?)");
$stmt->bind_param('isss', $user_id, $servico, $usuario_servico, $senha_hash);

if ($stmt->execute()) {
    $_SESSION['msg_sucesso'] = 'Senha cadastrada com sucesso!';
} else {
    $_SESSION['msg_erro'] = 'Erro ao salvar a senha.';
}

$stmt->close();
$conn->close();

header('Location: homepage.php');
exit();
