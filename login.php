<?php
session_start();
require "./auth/conexao.php";
$mensagem_erro = "";
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    $sql = "SELECT * from usuarios where email=? and senha =?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_object();
    if ($usuario) {
        $_SESSION["usuario_id"] = $usuario->id;
        $_SESSION["usuario_nome"] = $usuario->nome;
        $_SESSION["usuario_tipo"] = $usuario->tipo;
        if ($usuario->tipo === "admin") {
            header("Location:/administrador.php");
            exit;
        } else {
            header("Location:/produtos.php");
        }
    } else {
        $mensagem_erro = "Credenciais invalidas";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/login.css">
</head>

<body>
    <div class="login">
        <div class="esquerda">
            <img class="logo" src="./imagens/logo.png" alt="">
            <div class="elipses"></div>
            <div class="escrita">
                <h3>SUA LOJA , DO SEU JEITO</h3>
                <h1>Bem-vindo de volta.</h1>
                <p>Acesso sua conta para acompanhar seus pedido e descobrir produtos para o seu dia a dia</p>
            </div>
        </div>
        <div class="direita">
            <div class="escritas">
                <h4>AREA DO CLIENTE</h4>
                <h1>Entrar na conta</h1>
                <p>Use seu e-mail e sua senha para continuar.</p>
            </div>
            <form method="POST">
                <label for="email">E-mail</label>
                <input type="email" placeholder="Digite seu e-mail" required>
                <label for="password">Senha</label>
                <input type="password" placeholder="Digite sua senha" required>

                <p>Ainda não tem conta?<a href="/cadastro.php">Cadastre-se</a> </p>
                <button type="submit">Entrar <img src="./imagens/seta-entrar.png" alt=""></button>
            </form>
        </div>
    </div>
</body>

</html>