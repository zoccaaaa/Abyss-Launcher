<?php
$usuarioLogado = !empty($_SESSION['usuario_id']);
$tipoUsuario = $_SESSION['usuario_tipo'] ?? '';

if ($usuarioLogado) {
    require_once __DIR__ . '/../data/conexao.php';
    $consultaCabecalho = $conn->prepare('SELECT nome, tipo, ativo, foto_perfil FROM usuarios WHERE id_usuario=?');
    $consultaCabecalho->bind_param('i', $_SESSION['usuario_id']);
    $consultaCabecalho->execute();
    $usuarioCabecalho = $consultaCabecalho->get_result()->fetch_assoc();

    if (!$usuarioCabecalho || (int) $usuarioCabecalho['ativo'] !== 1) {
        $_SESSION = [];
        session_destroy();
        $usuarioLogado = false;
        $tipoUsuario = '';
    } else {
        $_SESSION['usuario_nome'] = $usuarioCabecalho['nome'];
        $_SESSION['usuario_tipo'] = $usuarioCabecalho['tipo'];
        $tipoUsuario = $usuarioCabecalho['tipo'];
    }
}

$usuarioAdmin = $usuarioLogado && $tipoUsuario === 'admin';
$fotoCabecalhoPadrao = !$usuarioLogado || empty($usuarioCabecalho['foto_perfil']);
$fotoCabecalho = !$fotoCabecalhoPadrao ? './' . ltrim($usuarioCabecalho['foto_perfil'], '/') : './imagens/perfil.png';
$paginaAtual = basename($_SERVER['PHP_SELF'] ?? '');
?>
<header>
    <div class="esquerda">
      <img class="logo-abyss" src="./imagens/logo.png" alt="Abyss Launcher">
      <a href="./index.php" class="<?= $paginaAtual === 'index.php' ? 'ativo' : '' ?>">Início</a>
      <a href="./index.php#catalogo">Catálogo</a>
      <a href="./sobre.php" class="<?= $paginaAtual === 'sobre.php' ? 'ativo' : '' ?>">Sobre nós</a>
    </div>

    <div class="meio">
      <input type="search" class="pesquisa" aria-label="Pesquisar" placeholder="">
      <button type="button" class="botao-pesquisa" aria-label="Buscar">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <circle cx="10.8" cy="10.8" r="6.3"></circle>
          <path d="m15.5 15.5 4.2 4.2"></path>
        </svg>
      </button>
    </div>

    <div class="direita">
      <details class="menu-perfil">
        <summary aria-label="Abrir opções da conta">
          <img class="<?= $fotoCabecalhoPadrao ? 'foto-perfil-padrao' : '' ?>" src="<?= htmlspecialchars($fotoCabecalho, ENT_QUOTES, 'UTF-8') ?>" alt="Foto de perfil">
        </summary>
        <nav class="opcoes-perfil" aria-label="Opções da conta">
          <?php if ($usuarioLogado): ?>
            <span class="nome-perfil">Olá, <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário', ENT_QUOTES, 'UTF-8') ?></span>
            <?php if ($usuarioAdmin): ?>
              <a class="opcao-admin" href="./painel.php">Painel administrativo</a>
            <?php endif; ?>
            <a href="./perfil.php#editar-perfil">Editar perfil</a>
            <a href="./biblioteca.php">Biblioteca</a>
            <a href="./Carrinho-nv.php">Carrinho</a>
            <a href="./data/sair.php">Sair</a>
          <?php else: ?>
            <a href="./login.php">Entrar</a>
            <a href="./Carrinho-nv.php">Carrinho</a>
          <?php endif; ?>
        </nav>
      </details>
    </div>
</header>

<script>
document.addEventListener('click', (evento) => {
  document.querySelectorAll('.menu-perfil[open]').forEach((menu) => {
    if (!menu.contains(evento.target) || evento.target.closest('.opcoes-perfil a')) {
      menu.removeAttribute('open');
    }
  });
});
</script>
