<?php
session_start();

$token = $_GET['token'] ?? '';

if (!$token) {
    die('Token inválido ou ausente.');
}


$host = getenv("LOCKMIND_DB_HOST");
$user = getenv("LOCKMIND_DB_USER");
$pass = getenv("LOCKMIND_DB_PASS");
$base = getenv("LOCKMIND_DB_DATABASE");

$conn = new mysqli($host, $user, $pass, $base);

$stmt = $conn->prepare("SELECT usuario_id, expira_em FROM token_recuperacao WHERE token = ? LIMIT 1");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$recuperacao = $result->fetch_assoc();



if (!$recuperacao) {
    die('Token inválido.');
}

if (new DateTime() > new DateTime($recuperacao['expira_em'])) {
    die('Token expirado. Solicite uma nova recuperação de senha.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha1 = $_POST['senha'] ?? '';
    $senha2 = $_POST['confirma_senha'] ?? '';

    if (!$senha1 || !$senha2) {
        $erro = "Preencha os dois campos de senha.";
    } elseif ($senha1 !== $senha2) {
        $erro = "As senhas não conferem.";
    } else {
        $hash = password_hash($senha1, PASSWORD_DEFAULT);

        $conn->begin_transaction();

        try {

            $stmt = $conn->prepare("UPDATE usuario SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hash, $recuperacao['usuario_id']);
            $stmt->execute();

            $stmt = $conn->prepare("DELETE FROM token_recuperacao WHERE token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();

            $conn->commit();

            $_SESSION['mensagem_sucesso'] = "Senha alterada com sucesso! Faça login.";
            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $erro = "Erro ao alterar a senha. Tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha - Lockmind</title>
    <link rel="stylesheet" href="style/redefinir_senha.css">
    <script src="script/script.js" defer></script>

</head>

<body>

    <div class="login-container">
        <h2>Redefinir Senha</h2>

        <?php if (!empty($erro)): ?>
            <div class="error-message"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group see-pass">
                <div class="input-wrapper">
                    <input type="password" id="senha" name="senha" placeholder="Senha" required />
                    <img height="20" width="20" src="images/closedeye.png" alt="Mostrar senha" class="toggle-senha"
                        onclick="toggleSenha(this, 'senha')">
                </div>
            </div>

            <div class="form-group see-pass">
                <div class="input-wrapper">
                    <input type="password" id="confirma_senha" name="confirma_senha" placeholder="Confirmar Senha"
                        required />
                    <img height="20" width="20" src="images/closedeye.png" alt="Mostrar senha" class="toggle-senha"
                        onclick="toggleSenha(this, 'confirma_senha')">
                </div>
                <p id="mensagem-senha" style="color: red; font-size: 0.9rem;"></p>

            </div>

            <div class="form-group btnn">
                <button class="login-button" type="submit">Alterar senha</button>
            </div>

        </form>

        <script>
            const senha = document.getElementById('senha');
            const confirmar = document.getElementById('confirma_senha');
            const mensagem = document.getElementById('mensagem-senha');

            function verificarSenhas() {
                if (senha.value !== confirmar.value) {
                    mensagem.textContent = "As senhas não coincidem.";
                } else {
                    mensagem.textContent = "";
                }
            }

            senha.addEventListener('input', verificarSenhas);
            confirmar.addEventListener('input', verificarSenhas);
        </script>

        <a href="login.php" class="register-link">Voltar ao login</a>
    </div>

</body>

</html>