<?php
session_start();

$host = getenv("LOCKMIND_DB_HOST");
$user = getenv("LOCKMIND_DB_USER");
$pass = getenv("LOCKMIND_DB_PASS");
$base = getenv("LOCKMIND_DB_DATABASE");


$conn = new mysqli($host, $user, $pass, $base);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $conn->prepare("SELECT id, name, password FROM usuario WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 1) {
    $stmt->bind_result($user_id, $name, $hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        $_SESSION['usuario'] = $username;
        $_SESSION['usuario_id'] = $user_id;
        $_SESSION['usuario_nome'] = $name;
        header("Location: homepage.php");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: index.php?erro-p=1&username=" . $username);
        exit();
    }
}

$stmt->close();
$conn->close();
header("Location: index.php?erro-u=1");
exit();
?>