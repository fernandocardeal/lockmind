<?php
session_start();

require 'funcoes.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

$usuario = $_SESSION['usuario'] ?? 'Usuário';

$host = getenv("LOCKMIND_DB_HOST");
$user = getenv("LOCKMIND_DB_USER");
$pass = getenv("LOCKMIND_DB_PASS");
$base = getenv("LOCKMIND_DB_DATABASE");


$conn = new mysqli($host, $user, $pass, $base);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$user_id = $_SESSION['usuario_id'];

$stmt = $conn->prepare("SELECT s.id AS ID_SENHA, s.service_password AS SENHA, s.service AS SERVICO, s.service_user AS USUARIO FROM usuario u JOIN senha s ON u.id = s.user_id AND u.id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$resultado = $stmt->get_result();

$senhas = [];
while ($row = $resultado->fetch_assoc()) {
    $senhas[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <title>Bem-vindo - Lockmind</title>
    <link rel="stylesheet" href="style/homepage.css">
    <script src="script/script.js" defer></script>
</head>

<body>

    <div class="container">
        <div class="cabecalho">
            <h2>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></h2>

            <div class="menu-hamburguer">
                <div class="hamburguer-icon" onclick="toggleMenu()">☰</div>
                <div class="dropdown-menu" id="dropdownMenu">
                    <a href="logout.php">Sair</a>
                    <a href="configuracoes.php">Configurações</a>
                </div>
            </div>

        </div>
        <div class="grid-senhas">

            <?php foreach ($senhas as $index => $item): ?>
                <div class="senha-card">

                    <strong><?= htmlspecialchars($item['SERVICO']) ?></strong>

                    <div class="input-copy">
                        <input type="text" id="conteudo-u-<?= $index ?>" name="usuario"
                            value="<?= htmlspecialchars($item['USUARIO']) ?>"
                            onclick="copiarTexto('conteudo-u-<?= $index ?>')" readonly>
                    </div>

                    <div class="input-copy">
                        <input type="password" onmouseover="this.type='text'" onmouseout="this.type='password'"
                            id="conteudo-p-<?= $index ?>" name="senha"
                            value="<?= htmlspecialchars(descriptografarSenha($item['SENHA'])) ?>"
                            onclick="copiarTexto('conteudo-p-<?= $index ?>')" readonly>
                    </div>

                    <form method="post" action="excluir_senha.php" onsubmit="return confirm('Deseja excluir esta senha?');">
                        <input type="hidden" name="id_senha" value="<?= $item['ID_SENHA'] ?>">
                        <input type="hidden" name="id_usuario" value="<?= $user_id ?>">
                        <button type="submit">Excluir</button>
                    </form>

                </div>
            <?php endforeach; ?>

            <div class="senha-card add-nova-senha" onclick="window.location.href='nova_senha.php'" tabindex="0"
                role="button">
                + Nova Senha
            </div>

        </div>
    </div>


</body>

</html>