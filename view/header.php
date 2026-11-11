<?php
include_once __DIR__ . "/../data/jogos.php";

$itensBusca = [[
    "id"      => "hero",
    "nome"    => $banner["titulo"],
    "generos" => "Destaque",
    "preco"   => $banner["preco"],
    "img"     => $banner["img"],
]];
foreach ($jogos as $i => $j) {
    $itensBusca[] = [
        "id"      => "jogo-" . $i,
        "nome"    => $j["nome"],
        "generos" => $j["generos"],
        "preco"   => $j["preco"],
        "img"     => $j["img"],
    ];
}
?>
<header>
    <div class="esquerda">
      <a href="" class="ativo">Inicio</a>
      <a href="">Catalogo</a>
      <a href="">Sobre nós</a>
    </div>

    <div class="meio">
      <input type="search" class="pesquisa" id="pesquisaHeader" aria-label="Pesquisar" placeholder="Pesquisar jogos" autocomplete="off">
      <button type="button" class="botao-pesquisa" aria-label="Buscar">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <circle cx="10.8" cy="10.8" r="6.3"></circle>
          <path d="m15.5 15.5 4.2 4.2"></path>
        </svg>
      </button>
      <div class="resultados" id="resultadosBusca" role="listbox" hidden></div>
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

<script>
(function () {
  var JOGOS      = <?= json_encode($itensBusca, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
  var MAX        = 6;
  var campo      = document.getElementById("pesquisaHeader");
  var lista      = document.getElementById("resultadosBusca");
  var botao      = document.querySelector(".botao-pesquisa");
  var atual      = -1;
  var encontrados = [];

  
  function normalizar(texto) {
    var saida = "", mapa = [];
    for (var i = 0; i < texto.length; i++) {
      var n = texto[i].normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
      for (var k = 0; k < n.length; k++) { saida += n[k]; mapa.push(i); }
    }
    return { texto: saida, mapa: mapa };
  }

  function limpar(t) { return normalizar(t).texto; }

  function destacar(el, texto, termo) {
    var n = normalizar(texto);
    var pos = n.texto.indexOf(termo);
    if (pos === -1) { el.textContent = texto; return; }
    var ini = n.mapa[pos];
    var fim = n.mapa[pos + termo.length - 1] + 1;
    var mark = document.createElement("mark");
    mark.textContent = texto.slice(ini, fim);
    el.appendChild(document.createTextNode(texto.slice(0, ini)));
    el.appendChild(mark);
    el.appendChild(document.createTextNode(texto.slice(fim)));
  }

  function fechar() {
    lista.hidden = true;
    atual = -1;
  }

  function marcar(i) {
    var itens = lista.querySelectorAll(".resultado");
    itens.forEach(function (el, n) { el.classList.toggle("selecionado", n === i); });
    atual = i;
  }

  function irPara(jogo) {
    fechar();
    var alvo = document.getElementById(jogo.id);
    if (!alvo) return;
    if (alvo.hidden) {
      var filtro = document.getElementById("busca");
      if (filtro) { filtro.value = ""; filtro.dispatchEvent(new Event("input")); }
    }
    alvo.scrollIntoView({ behavior: "smooth", block: jogo.id === "hero" ? "start" : "center" });
    if (jogo.id !== "hero") {
      alvo.classList.add("destaque");
      setTimeout(function () { alvo.classList.remove("destaque"); }, 1800);
    }
  }

  function verTodos() {
    var filtro = document.getElementById("busca");
    var termo  = campo.value.trim();
    fechar();
    if (!filtro) return;
    filtro.value = termo;
    filtro.dispatchEvent(new Event("input"));
    filtro.scrollIntoView({ behavior: "smooth", block: "center" });
  }

  function desenhar() {
    var termoOriginal = campo.value.trim();
    if (!termoOriginal) { fechar(); return; }
    var termo = limpar(termoOriginal);

    encontrados = JOGOS.filter(function (j) {
      return limpar(j.nome).indexOf(termo) !== -1;
    });

    lista.innerHTML = "";
    atual = -1;

    if (!encontrados.length) {
      var vazio = document.createElement("p");
      vazio.className = "sem-resultado";
      vazio.textContent = "Nenhum jogo encontrado para \u201c" + termoOriginal + "\u201d";
      lista.appendChild(vazio);
      lista.hidden = false;
      return;
    }

    encontrados.slice(0, MAX).forEach(function (j, n) {
      var item = document.createElement("div");
      item.className = "resultado";
      item.setAttribute("role", "option");

      var img = document.createElement("img");
      img.src = j.img;
      img.alt = "";

      var info = document.createElement("div");
      info.className = "info";
      var nome = document.createElement("p");
      nome.className = "nome";
      destacar(nome, j.nome, termo);
      var meta = document.createElement("p");
      meta.className = "meta";
      meta.textContent = j.generos;
      info.appendChild(nome);
      info.appendChild(meta);

      var preco = document.createElement("span");
      preco.className = "preco";
      preco.textContent = j.preco;

      item.appendChild(img);
      item.appendChild(info);
      item.appendChild(preco);
      item.addEventListener("mousemove", function () { marcar(n); });
      item.addEventListener("click", function () { irPara(j); });
      lista.appendChild(item);
    });

    var todos = document.createElement("div");
    todos.className = "ver-todos";
    todos.textContent = "Ver todos os resultados para \u201c" + termoOriginal + "\u201d";
    todos.addEventListener("click", verTodos);
    lista.appendChild(todos);

    lista.hidden = false;
  }

  campo.addEventListener("input", desenhar);
  campo.addEventListener("focus", desenhar);

  campo.addEventListener("keydown", function (e) {
    var total = Math.min(encontrados.length, MAX);
    if (e.key === "ArrowDown" && total) {
      e.preventDefault();
      marcar((atual + 1) % total);
    } else if (e.key === "ArrowUp" && total) {
      e.preventDefault();
      marcar((atual - 1 + total) % total);
    } else if (e.key === "Enter") {
      e.preventDefault();
      if (atual >= 0 && encontrados[atual]) irPara(encontrados[atual]);
      else verTodos();
    } else if (e.key === "Escape") {
      fechar();
    }
  });

  botao.addEventListener("click", function () {
    if (campo.value.trim()) verTodos(); else campo.focus();
  });

  document.addEventListener("click", function (e) {
    if (!e.target.closest(".meio")) fechar();
  });
})();
</script>
