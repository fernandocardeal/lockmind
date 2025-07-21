<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_POST['id_senha'])) {
    header('Location: homepage.php');
    exit();
}

$host = getenv("LOCKMIND_DB_HOST");
$user = getenv("LOCKMIND_DB_USER");
$pass = getenv("LOCKMIND_DB_PASS");

$conn = new mysqli($host, $user, $pass, "lockmind");

if ($conn->connect_error) {
    die('Erro de conexão: ' . $conn->connect_error);
}

$senha_id = $_POST['id_senha'];
$usuario_id = $_POST['id_usuario'];

$stmt = $conn->prepare('DELETE FROM senha WHERE id = ? AND user_id = ?');
$stmt->bind_param('ss', $senha_id, $usuario_id);

if ($stmt->execute()) {
    $_SESSION['msg_sucesso'] = 'Senha deletada com sucesso!';
} else {
    $_SESSION['msg_erro'] = 'Erro ao deletar a senha.';
}

$stmt->close();
$conn->close();

header('Location: homepage.php');
exit();
