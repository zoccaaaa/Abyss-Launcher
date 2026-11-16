<?php
session_start();
require "./data/conexao.php";
$mensagem_erro = "";
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    $sql = "SELECT id_usuario, nome, tipo, senha, ativo, justificativa_desativacao FROM usuarios WHERE email=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_object();
    $senhaArmazenada = $usuario->senha ?? '';
    $senhaValida = $usuario && (
        password_verify($senha, $senhaArmazenada)
        || hash_equals($senhaArmazenada, $senha)
    );

    if ($senhaValida && (int) $usuario->ativo === 1) {
        if (password_needs_rehash($senhaArmazenada, PASSWORD_DEFAULT)) {
            try {
                $senhaAtualizada = password_hash($senha, PASSWORD_DEFAULT);
                $atualizarSenha = $conn->prepare('UPDATE usuarios SET senha=? WHERE id_usuario=?');
                $atualizarSenha->bind_param('si', $senhaAtualizada, $usuario->id_usuario);
                $atualizarSenha->execute();
            } catch (mysqli_sql_exception $erro) {
                error_log('Falha ao atualizar o hash de senha do usuÃ¡rio: ' . $erro->getMessage());
            }
        }
        session_regenerate_id(true);
        $_SESSION["usuario_id"] = $usuario->id_usuario;
        $_SESSION["usuario_nome"] = $usuario->nome;
        $_SESSION["usuario_tipo"] = $usuario->tipo;

        header("Location:./index.php");
        exit;
    } elseif ($senhaValida) {
        $motivo = trim($usuario->justificativa_desativacao ?? '');
        $mensagem_erro = $motivo === ''
            ? 'Esta conta está desativada.'
            : 'Esta conta está desativada. Entrar em contato com a adminstração';
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
    <link rel="icon" type="image/png" href="./imagens/logo.png">
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    <div class="login">
        <div class="esquerda">
            <img class="logo" src="./imagens/logo.png" alt="Logo Abyss">
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
                <input type="email" name="email" placeholder="Digite seu e-mail" required>
                <label for="password">Senha</label>
                <div class="campo-senha">
                    <input id="password" type="password" name="senha" placeholder="Digite sua senha" required>
                    <button class="revelar-senha" type="button" data-revelar-senha aria-pressed="false">Mostrar</button>
                </div>

                <?php if ($mensagem_erro !== ''): ?>
                    <p class="mensagem-erro" role="alert"><?= htmlspecialchars($mensagem_erro, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>

                <p class="link-cadastro">Ainda não tem conta? <a href="./cadastro.php">Cadastre-se</a></p>
                <button type="submit">Entrar <img src="./imagens/seta-entrar.png" alt=""></button>
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
