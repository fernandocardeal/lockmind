<?php
session_start();

$host = getenv("LOCKMIND_DB_HOST");
$user = getenv("LOCKMIND_DB_USER");
$pass = getenv("LOCKMIND_DB_PASS");
$base = getenv("LOCKMIND_DB_DATABASE");


$conn = new mysqli($host, $user, $pass, $base);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$name = $_POST['name'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if ($password !== $confirm_password) {
    header("Location: cadastro.php?erro=1");
    exit();
}

$stmt = $conn->prepare("SELECT id FROM usuario WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    $conn->close();
    header("Location: register.php?erro=1");
    exit();
}
$stmt->close();

$senha_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO usuario (name, username, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $username, $senha_hash);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    echo '
    <html>
    <body>
        <form id="autoLogin" action="login.php" method="post">
            <input type="hidden" name="username" value="' . htmlspecialchars($username) . '">
            <input type="hidden" name="password" value="' . htmlspecialchars($password) . '">
        </form>
        <script>
            document.getElementById("autoLogin").submit();
        </script>
    </body>
    </html>
    ';
    exit();
} else {
    $stmt->close();
    $conn->close();
    header("Location: register.php?erro=1");
    exit();
}
?>