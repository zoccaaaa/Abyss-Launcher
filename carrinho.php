<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abyss | Carrinho</title>
    <link rel="icon" type="image/png" href="./imagens/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/carrinho.css">
</head>

<body>
    <?php require './view/header.php'; ?>

    <main class="pagina-carrinho">
        <section class="carrinho-vazio" aria-labelledby="titulo-carrinho-vazio">
            <img src="./imagens/Mascote carrinho.png" alt="" class="mascote-carrinho">
            <h1 id="titulo-carrinho-vazio">Seu carrinho está vazio.</h1>
            <a class="explorar-catalogo" href="./index.php">Explorar catálogo</a>
        </section>
    </main>
</body>

</html>