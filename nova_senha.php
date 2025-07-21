<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html5>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Lockmind</title>
    <link rel="stylesheet" href="style/novasenha.css">
    <script src="script/script.js" defer></script>


</head>

<body>

    <div class="login-container">
        <h2>Cadastrar Nova Senha</h2>

        <form method="post" action="registra_senha.php">

            <div class="form-group">
                <input type="text" id="service" name="service" placeholder="Serviço / Site" required />
            </div>

            <div class="form-group">
                <input type="text" id="service_user" name="service_user" placeholder="Usuário do Serviço" required />
            </div>

            <div class="form-group see-pass">
                <div class="input-wrapper">
                    <input type="password" id="service_password" name="service_password" placeholder="Senha" required />
                    <img height="20" width="20" src="images/closedeye.png" alt="Mostrar senha" class="toggle-senha"
                        onclick="toggleSenha(this, 'service_password')">
                </div>
            </div>

            <button type="submit" class="login-button">Cadastrar</button>
        </form>

        <div class="voltar-wrapper">
            <a href="homepage.php" class="voltar-link">Voltar</a>
        </div>

    </div>

</body>

</html>