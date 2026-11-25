<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre nós | Abyss</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/sobre.css">
    <link rel="stylesheet" href="./css/footer.css">
</head>

<body>
    <?php require './view/header.php'; ?>
    <div class="hero">
        <div class="texto">
            <h1>Seu universo. <span>Do seu jeito.</span></h1>
            <p>O Abyss nasceu para devolver aos jogadores o que sempre foi deles: a liberdade de descobrir, organizar e
                viver cada jogo sem barreiras.</p>
        </div>
        <div class="imagem">
            <img src="./imagens/cartas.gif" alt="">
        </div>
    </div>
    <div class="informacoes">
        <div class="informacao">
            <h1 id="n1">30</h1>
            <p>JOGOS NA BIBLIOTECA</p>
        </div>
        <div class="informacao">
            <h1 id="n2">1200</h1>
            <p>JOGADORES CONECTADOS</p>
        </div>
        <div class="informacao">
            <h1 id="n3">180</h1>
            <p>PAÍSES ALCANÇADOS</p>
        </div>
        <div class="informacao">
            <h1 id="n4">70,4%</h1>
            <p>TEMPO ONLINE</p>
        </div>
    </div>
    <div class="cartas-antes">
        <div class="texto">
            <h1>Não criamos apenas um launcher</h1>
            <h3>Criamos um ponto de encontro para quem acredita que jogar é mais do que passar o tempo.</h3>
            <p>É descobrir mundos, formar amizades e colecionar histórias que continuam com você depois que a tela
                apaga. Por isso, o Nexus coloca a experiência do jogador em primeiro lugar -sempre.</p>
        </div>
        <img src="./imagens/jogos.png" alt="">
    </div>
    <div class="cards">
        <div class="card">
            <div class="frente">
                <img class="logo" src="./imagens/image 24.png" alt="">
            </div>
            <div class="tras">
                <img src="./imagens/image 43.png" alt="">
                <h1>Feito por jogadores</h1>
                <p>Somos uma equipe que joga, escuta e constrói lado a lado com a comunidade.</p>
            </div>
        </div>
        <div class="card2">
            <div class="frente">
                <img class="logo" src="./imagens/image 24.png" alt="">
            </div>
            <div class="tras">
                <h1>Liberdade primeiro</h1>
                <p>Seus jogos, suas escolhas, seus dados. Sem amarras e sem experiências invasivas</p>
            </div>
        </div>
        <div class="card card3">
            <div class="frente">
                <img class="logo" src="./imagens/image 24.png" alt="">
            </div>
            <div class="tras">
                <img src="./imagens/estrela (1).png" alt="">
                <h1>Sempre em evolução</h1>
                <p>Cada atualização torna o Nexus mais rápido, simples e aberto a novas possibilidades.</p>
            </div>
        </div>
    </div>
    <?php require './view/footer.php'; ?>
    <script src="informacao.js"></script>
</body>

</html>
