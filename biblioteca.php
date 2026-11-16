<?php
require './data/auth.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca | Abyss</title>
    <link rel="icon" type="image/png" href="./imagens/logo.png">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/nao-logado.css">
</head>
<body>
    <?php require './view/header.php'; ?>
    <main class="aviso-acesso">
        <h1>Sua biblioteca</h1>
        <p class="descricao">Sua coleção de jogos aparecerá aqui.</p>
        <a class="botao principal" href="./index.php#catalogo">Explorar catálogo</a>
    </main>
</body>
</html>
