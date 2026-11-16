<?php require "./data/conexao.php";
$mensagem_erro = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if ($nome == '' || $email == '' || $senha == '') {
        $mensagem_erro = 'preencha todos os campos';
    } else {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, 'usuario')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nome, $email, $senhaHash);
        try {
            $stmt->execute();
            header("Location: login.php");
            exit;
        } catch (mysqli_sql_exception $e) {
            $mensagem_erro = $e->getCode() === 1062
                ? "Email ja cadastrado"
                : 'Erro ao cadastrar'
            ;
        }

    }
}


?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="icon" type="image/png" href="./imagens/logo.png">
    <link rel="stylesheet" href="./css/cadastro.css">
</head>

<body>
    <div class="cadastro">
        <div class="esquerda">
            <img class="logo" src="./imagens/logo.png" alt="Logo Abyss">
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
            <form method="POST">
                <label for="name">Nome</label>
                <input id="nome" name="nome" type="text" placeholder="Digite seu nome" required>
                <label for="email">E-mail</label>
                <input id="email" name="email" type="email" placeholder="Digite seu e-mail" required>
                <label for="password">Senha</label>
                <div class="campo-senha">
                    <input id="senha" name="senha" type="password" placeholder="Digite sua senha" required>
                    <button class="revelar-senha" type="button" data-revelar-senha aria-pressed="false">Mostrar</button>
                </div>
                <?php if ($mensagem_erro !== ''): ?>
                    <p class="mensagem-erro" role="alert"><?= htmlspecialchars($mensagem_erro, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
                <p class="link-login">Já tem conta?<a href="./login.php">Entre já</a> </p>
                <button type="submit">Cadastrar <img src="./imagens/seta-entrar.png" alt=""></button>
            </form>
        </div>
    </div>
    <script>
      document.querySelectorAll('[data-revelar-senha]').forEach((botao) => {
        botao.addEventListener('click', () => {
          const campo = botao.parentElement.querySelector('input');
          const revelar = campo.type === 'password';
          campo.type = revelar ? 'text' : 'password';
          botao.textContent = revelar ? 'Ocultar' : 'Mostrar';
          botao.setAttribute('aria-pressed', revelar ? 'true' : 'false');
          botao.setAttribute('aria-label', revelar ? 'Ocultar senha' : 'Mostrar senha');
        });
      });
    </script>
</body>

</html>
