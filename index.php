<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header('Location: homepage.php');
    exit();
}
?>

<!DOCTYPE html5>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Lockmind</title>
    <link rel="stylesheet" href="style/index.css">
    <script src="script/script.js" defer></script>
</head>

<body>

    <div class="login-container">

        <h2>Login Lockmind</h2>
        <?php

        if (isset($_GET['erro-u'])) {
            echo '<div class="error-message">Usuário inválido!</div>';
        } elseif (isset($_GET['erro-p'])) {
            echo '<div class="error-message">Senha inválida!</div>';
            $username = $_GET['username'] ?? '';
        }
        ?>

        <form method="post" action="login.php" autocomplete="off">

            <div class="form-group">
                <input type="text" id="username" name="username" value="<?= $username ?>" placeholder="Usuário"
                    required />
            </div>

            <div class="form-group see-pass">
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="Senha" required />
                    <img height="20" width="20" src="images/closedeye.png" alt="Mostrar senha" class="toggle-senha"
                        onclick="toggleSenha(this, 'password')">
                </div>
            </div>


            <button type="submit" class="login-button">Entrar</button>
        </form>

        <a href="cadastro.php" class="register-link">Cadastrar-se</a>
    </div>

</body>

</html>