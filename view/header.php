<?php
$usuarioLogado = !empty($_SESSION['usuario_id']);
$tipoUsuario = $_SESSION['usuario_tipo'] ?? '';
$usuarioAdmin = $usuarioLogado && $tipoUsuario === 'admin';
$paginaAtual = basename($_SERVER['PHP_SELF'] ?? '');
?>
<header>
    <div class="esquerda">
      <img class="logo-abyss" src="./imagens/logo.png" alt="Abyss Launcher">
      <a href="./index.php" class="<?= $paginaAtual === 'index.php' ? 'ativo' : '' ?>">Início</a>
      <a href="./index.php#catalogo">Catálogo</a>
      <a href="./index.php#sobre">Sobre nós</a>
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
          <img src="./imagens/perfil.png" alt="Foto de perfil">
        </summary>
        <nav class="opcoes-perfil" aria-label="Opções da conta">
          <?php if ($usuarioLogado): ?>
            <span class="nome-perfil">Olá, <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário', ENT_QUOTES, 'UTF-8') ?></span>
            <?php if ($usuarioAdmin): ?>
              <a class="opcao-admin" href="./painel.php">Painel administrativo</a>
            <?php endif; ?>
            <?php if (!$usuarioAdmin): ?>
              <a href="#editar-perfil">Editar perfil</a>
            <?php endif; ?>
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
