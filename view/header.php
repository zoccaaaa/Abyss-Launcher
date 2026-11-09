<header>
    <div class="esquerda">
      <a href="" class="ativo">Inicio</a>
      <a href="">Catalogo</a>
      <a href="">Sobre nós</a>
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
        <summary aria-label="Abrir opções do perfil">
          <img src="img/PERFIL.png" alt="Foto de perfil">
        </summary>
        <div class="opcoes-perfil">
          <a href="#editar-perfil">Editar perfil</a>
          <a href="#biblioteca">Biblioteca</a>
        </div>
      </details>
      <img src="img/logo.png" alt="Logo Abyss">
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
