<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_saida'])) {
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $parametros = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $parametros['path'], $parametros['domain'], $parametros['secure'], $parametros['httponly']);
    }

    session_destroy();
    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar saída | Abyss</title>
    <link rel="stylesheet" href="../css/nao-logado.css">
</head>
<body>
    <main class="aviso-acesso">
        <h1>Tem certeza que deseja sair?</h1>
        <p class="descricao">Você precisará entrar novamente para acessar sua conta.</p>
        <div class="acoes">
            <form method="post">
                <button class="botao principal" type="submit" name="confirmar_saida" value="1" style="width:100%;font:inherit;cursor:pointer">Sim, sair</button>
            </form>
            <a class="botao secundario" href="../index.php">Cancelar</a>
        </div>
    </main>
</body>
</html>
