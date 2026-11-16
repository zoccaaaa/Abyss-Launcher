<?php
session_start();
$destino = basename($_GET['destino'] ?? 'index.php');
$destinosPermitidos = ['biblioteca.php', 'jogo.php', 'Carrinho-nv.php', 'carrinho.php', 'finalizar.php', 'pagamento.php', 'painel.php'];
if (!in_array($destino, $destinosPermitidos, true)) {
    $destino = 'index.php';
}
if (!empty($_SESSION['usuario_id'])) {
    header('Location: ./' . $destino);
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entre para continuar | Abyss</title>
    <link rel="icon" type="image/png" href="./imagens/logo.png">
    <link rel="stylesheet" href="./css/nao-logado.css">
</head>
<body class="pagina-nao-logado">
    <main class="aviso-acesso">
        <h1>Faça login para continuar</h1>
        <p class="descricao">Entre na sua conta ou continue explorando a loja.</p>
        <div class="acoes">
            <a class="botao principal" href="./login.php?destino=<?= rawurlencode($destino) ?>">Fazer login</a>
            <a class="botao secundario" href="./index.php">Explorar deslogado</a>
        </div>
    </main>
</body>
</html>
