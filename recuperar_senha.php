<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha - Lockmind</title>
    <link rel="stylesheet" href="style/recuperar_senha.css">
</head>

<body>

    <div class="login-container">
        <h2>Recuperar Senha</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">E-mail de recuperação enviado</div>
        <?php endif; ?>


        <form action="enviar_recuperacao.php" method="post">
            <div class="form-group">
                <input type="email" name="email" placeholder="Digite seu e-mail" required>
            </div>
            <div class="form-group">
                <button class="login-button" type="submit">Enviar link de recuperação</button>
            </div>
        </form>

        <a href="index.php" class="register-link">Voltar ao login</a>
    </div>

</body>

</html>