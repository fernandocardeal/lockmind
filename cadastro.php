<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header('Location: homepage.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="style/cadastro.css">
    <script src="script/script.js" defer></script>
    <title>Cadastro - Lockmind</title>

</head>

<body>

    <div class="login-container">
        <h2>Cadastro Lockmind</h2>

        <?php
        if (isset($_GET['erro'])) {
            echo '<div class="error-message">Erro ao cadastrar. Tente novamente.</div>';
        } elseif (isset($_GET['sucesso'])) {
            echo '<div class="success-message">Cadastro realizado com sucesso! <a href="index.php">Ir para login</a></div>';
        }
        ?>

        <form method="post" action="processa_cadastro.php">

            <div class="form-group see-pass">
                <input type="text" id="name" name="name" placeholder="Nome" required />
            </div>

            <div class="form-group see-pass">
                <input type="text" id="email" name="email" placeholder="E-mail" required />
            </div>

            <div class="form-group see-pass">
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="Senha" required />
                    <img height="20" width="20" src="images/closedeye.png" alt="Mostrar senha" class="toggle-senha"
                        onclick="toggleSenha(this, 'password')">
                </div>
            </div>

            <div class="form-group see-pass">
                <div class="input-wrapper">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmar Senha"
                        required />
                    <img height="20" width="20" src="images/closedeye.png" alt="Mostrar senha" class="toggle-senha"
                        onclick="toggleSenha(this, 'confirm_password')">
                    <p id="mensagem-senha" style="color: red; font-size: 0.9rem;"></p>

                </div>
            </div>



            <button type="submit" class="login-button">Cadastrar</button>
        </form>


        <a href="index.php" class="register-link">Já tem conta? Fazer login</a>
    </div>

    <script>
        const senha = document.getElementById('password');
        const confirmar = document.getElementById('confirm_password');
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


</body>

</html>