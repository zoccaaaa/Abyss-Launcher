<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="/cadastro.css">
</head>
<body>
    <div class="cadastro">
        <div class="esquerda">
            <img class="logo" src="./imagens/logo.png" alt="">
            <div class="elipses"></div>
            <div class="escrita">
                <h3>SUA LOJA , DO SEU JEITO</h3>
                <h1>Bem-vindo.</h1>
                <p>Crie uma conta para acompanhar seus pedido e descobrir produtos para o seu dia a dia</p>
            </div>
        </div>
        <div class="direita">
            <div class="escritas">
                <h4>AREA DO CLIENTE</h4>
                <h1>Criar uma conta</h1>
                <p>Crie seu nome, seu e-mail e sua senha para continuar.</p>
            </div>
            <form action="">
                <label for="name">Nome</label>
                <input type="text" placeholder="Digite seu nome" required>
                <label for="email">E-mail</label>
                <input type="email" placeholder="Digite seu e-mail" required>
                <label for="password">Senha</label>
                <input type="password" placeholder="Digite sua senha" required>
                <p>Já tem conta?<a href="/login.php">Entre-já</a> </p>
                <button type="submit">Cadastrar <img src="./imagens/seta-entrar.png" alt=""></button>
            </form>
        </div>
    </div>
</body>
</html>