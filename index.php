<?php
include __DIR__ . "/data/jogos.php";

// Vídeo do jogo: usa img/<mesmo-nome-da-imagem>.mp4 se o arquivo existir (ex.: img/elden-ring.mp4)
foreach ($jogos as &$jogo) {
    $video = preg_replace('/\.[A-Za-z0-9]+$/', '.mp4', $jogo["img"]);
    $jogo["video"] = file_exists(__DIR__ . "/" . $video) ? $video : "";
}
unset($jogo, $video);

// Dados do banner no mesmo formato dos jogos (usado pelo botão "Compre agora" do banner)
$bannerModal = [
    "nome"        => $banner["nome"],
    "generos"     => $banner["generos"],
    "preco"       => $banner["preco"],
    "img"         => $banner["img"],
    "video"       => file_exists(__DIR__ . "/" . $banner["video"]) ? $banner["video"] : "",
    "dev"         => $banner["dev"],
    "ano"         => $banner["ano"],
    "plataformas" => $banner["plataformas"],
    "sinopse"     => $banner["sinopse"],
];


$contagem = [];
foreach ($jogos as $j) {
    foreach (explode(" • ", $j["generos"]) as $g) {
        $contagem[$g] = ($contagem[$g] ?? 0) + 1;
    }
}
function semAcento($t) {
    // usa mbstring se estiver ativa; senão cai para strtolower (sem derrubar o site)
    $t = function_exists('mb_strtolower') ? mb_strtolower($t, 'UTF-8') : strtolower($t);
    return strtr($t, ["á"=>"a","à"=>"a","â"=>"a","ã"=>"a","é"=>"e","ê"=>"e","í"=>"i","ó"=>"o","ô"=>"o","õ"=>"o","ú"=>"u","ç"=>"c","Á"=>"a","À"=>"a","Â"=>"a","Ã"=>"a","É"=>"e","Ê"=>"e","Í"=>"i","Ó"=>"o","Ô"=>"o","Õ"=>"o","Ú"=>"u","Ç"=>"c"]);
}
uksort($contagem, function ($a, $b) { return strcmp(semAcento($a), semAcento($b)); });
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Catálogo de Jogos</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/footer.css">
  <link rel="stylesheet" href="css/modal.css">
</head>
<body>

  <?php include __DIR__ . "/view/header.php"; ?>
  


  <section class="hero" id="hero" style="--banner: url('<?= $banner["img"] ?>')">
    <video class="hero__video" id="heroVideo" src="<?= $banner["video"] ?>" muted playsinline preload="auto"></video>
    <div class="hero__logo"><?= $banner["titulo"] ?></div>

    <div class="hero__info">
      <p class="hero__tag">JÁ DISPONÍVEL</p>
      <p class="hero__desc"><?= $banner["descricao"] ?></p>
      <p class="hero__preco"><?= $banner["preco"] ?></p>
      <div class="hero__acoes">
        <button class="btn-comprar" id="heroComprar">Compre agora</button>
        <button class="btn-icone" aria-label="Adicionar ao carrinho">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h2l2.4 11h10.2L20 7H6.2"/><circle cx="9" cy="20" r="1.2"/><circle cx="17" cy="20" r="1.2"/></svg>
        </button>
        <button class="btn-icone" aria-label="Salvar nos favoritos">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h12v18l-6-4-6 4z"/></svg>
        </button>
      </div>
    </div>

    <div class="hero__dots" id="heroDots">
      <span class="ativo" data-slide="0"></span>
      <span data-slide="1"></span>
    </div>
  </section>

  <div class="conteudo">
    <div class="divisor"></div>

    <form class="busca">
      <button type="button" id="surpresa">Surpreenda-me</button>
    </form>

    <div class="filtro" id="filtro">
      <button type="button" class="chip ativo" data-genero="">Todos</button>
      <?php foreach ($contagem as $genero => $total) { ?>
        <button type="button" class="chip" data-genero="<?= htmlspecialchars($genero) ?>"><?= htmlspecialchars($genero) ?></button>
      <?php } ?>
    </div>

   
    <main class="grid">
      <?php foreach ($jogos as $i => $j) { ?>
        <article class="card" id="jogo-<?= $i ?>" data-indice="<?= $i ?>" tabindex="0" role="link" data-generos="<?= htmlspecialchars(str_replace(" • ", "|", $j["generos"])) ?>">
          <img src="<?= $j["img"] ?>" alt="<?= $j["nome"] ?>">
          <div class="card__corpo">
            <h3 class="card__nome"><?= $j["nome"] ?></h3>
            <p class="card__generos"><?= $j["generos"] ?></p>
            <p class="card__preco"><?= $j["preco"] ?></p>
          </div>
        </article>
      <?php } ?>
    </main>
    <p class="vazio" id="vazio" hidden>Nenhum jogo encontrado com esse filtro.</p>
  </div>

<script>
 
  (function () {
    var hero  = document.getElementById("hero");
    var video = document.getElementById("heroVideo");
    var dots  = document.querySelectorAll("#heroDots span");
    var TEMPO_IMAGEM = 5000;
    var timer = null;

    function marcarDot(i) {
      dots.forEach(function (d, n) { d.classList.toggle("ativo", n === i); });
    }

    function mostrarImagem() {
      clearTimeout(timer);
      hero.classList.remove("hero--video");
      video.pause();
      marcarDot(0);
      timer = setTimeout(mostrarVideo, TEMPO_IMAGEM);
    }

    function mostrarVideo() {
      clearTimeout(timer);
      video.currentTime = 0;
      var p = video.play();
      if (p && p.catch) { p.catch(mostrarImagem); } 
      hero.classList.add("hero--video");
      marcarDot(1);
    }

    video.addEventListener("ended", mostrarImagem);
    dots.forEach(function (d) {
      d.addEventListener("click", function () {
        d.dataset.slide === "1" ? mostrarVideo() : mostrarImagem();
      });
    });

    mostrarImagem();
  })();
</script>

<script>
  
  (function () {
    var chips  = document.querySelectorAll("#filtro .chip");
    var cards  = document.querySelectorAll(".card");
    var busca  = document.getElementById("busca");
    var vazio  = document.getElementById("vazio");
    var genero = "";

    function limpar(t) {
      return t.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    }

    function filtrar() {
      var termo = busca ? limpar(busca.value.trim()) : "";
      var achou = 0;
      cards.forEach(function (c) {
        var nome = limpar(c.querySelector(".card__nome").textContent);
        var okGenero = genero === "" || c.dataset.generos.split("|").indexOf(genero) !== -1;
        var okBusca  = termo === "" || nome.indexOf(termo) !== -1;
        c.hidden = !(okGenero && okBusca);
        if (!c.hidden) achou++;
      });
      vazio.hidden = achou > 0;
    }

    chips.forEach(function (chip) {
      chip.addEventListener("click", function () {
        genero = chip.dataset.genero;
        chips.forEach(function (c) { c.classList.toggle("ativo", c === chip); });
        filtrar();
      });
    });

    if (busca) busca.addEventListener("input", filtrar);
    document.querySelector(".busca").addEventListener("submit", function (e) { e.preventDefault(); });

    document.getElementById("surpresa").addEventListener("click", function () {
      var visiveis = Array.prototype.filter.call(cards, function (c) { return !c.hidden; });
      if (!visiveis.length) return;
      cards.forEach(function (c) { c.classList.remove("card--sorteado"); });
      var sorteado = visiveis[Math.floor(Math.random() * visiveis.length)];
      sorteado.classList.add("card--sorteado");
      sorteado.scrollIntoView({ block: "center" });
      if (window.abrirJogo) window.abrirJogo(+sorteado.dataset.indice, document.getElementById("surpresa"));
    });
  })();
</script>


  <div class="modal" id="modalJogo" role="dialog" aria-modal="true" aria-labelledby="modalNome" aria-hidden="true">
    <div class="modal__caixa">
      <button type="button" class="modal__fechar" id="modalFechar" aria-label="Fechar">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
      </button>

      <div class="modal__capa">
        <img id="modalImg" src="" alt="">
        <div class="modal__titulo">
          <h2 id="modalNome"></h2>
          <div class="modal__generos" id="modalGeneros"></div>
        </div>
      </div>

      <div class="modal__corpo">
        <section class="modal__sinopse">
          <h3>Sobre o jogo</h3>
          <p id="modalSinopse"></p>
        </section>

        <aside class="modal__ficha">
          <h3>Ficha técnica</h3>
          <dl>
            <div><dt>Desenvolvedora</dt><dd id="modalDev"></dd></div>
            <div><dt>Lançamento</dt><dd id="modalAno"></dd></div>
            <div><dt>Plataformas</dt><dd id="modalPlataformas"></dd></div>
          </dl>
        </aside>

        <section class="modal__video" id="modalVideoBox" hidden>
          <h3>Vídeo</h3>
          <video id="modalVideo" muted loop playsinline controls preload="none"></video>
        </section>

        <div class="modal__compra">
          <span class="modal__preco" id="modalPreco"></span>
          <button type="button" class="btn-comprar">Compre agora</button>
          <button type="button" class="btn-icone" id="modalCarrinho" aria-pressed="false" aria-label="Adicionar ao carrinho">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h2l2.4 11h10.2L20 7H6.2"/><circle cx="9" cy="20" r="1.2"/><circle cx="17" cy="20" r="1.2"/></svg>
          </button>
          <button type="button" class="btn-icone" id="modalFavorito" aria-pressed="false" aria-label="Salvar nos favoritos">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h12v18l-6-4-6 4z"/></svg>
          </button>
        </div>
      </div>
    </div>
  </div>

<script>
  // Modal de detalhes do jogo
  (function () {
    var BANNER  = <?= json_encode($bannerModal, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    var JOGOS   = <?= json_encode($jogos, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    var modal   = document.getElementById("modalJogo");
    var cards   = document.querySelectorAll(".card");
    var btnCarr = document.getElementById("modalCarrinho");
    var btnFav  = document.getElementById("modalFavorito");
    var ultimo  = null;      // elemento que abriu o modal (devolve o foco ao fechar)
    var jogoAtual = -1;
    var carrinho  = {};      // { indice: true }
    var favoritos = {};

    function texto(id, valor) { document.getElementById(id).textContent = valor || "—"; }

    function marcar(btn, ligado) {
      btn.classList.toggle("ativo", ligado);
      btn.setAttribute("aria-pressed", ligado ? "true" : "false");
    }

    function abrir(i, origem) {
      abrirDetalhes(JOGOS[i], i, origem);
    }

    function abrirDetalhes(j, i, origem) {
      if (!j) return;
      jogoAtual = i;
      ultimo = origem || null;

      var img = document.getElementById("modalImg");
      img.src = j.img;
      img.alt = j.nome;

      // Vídeo do jogo (se existir) fica abaixo da descrição; a capa continua sendo a imagem
      var video = document.getElementById("modalVideo");
      var caixaVideo = document.getElementById("modalVideoBox");
      caixaVideo.hidden = !j.video;
      if (j.video) {
        video.poster = j.img;
        video.src = j.video;
      }
      texto("modalNome", j.nome);
      texto("modalSinopse", j.sinopse);
      texto("modalDev", j.dev);
      texto("modalAno", j.ano);
      texto("modalPlataformas", j.plataformas);
      texto("modalPreco", j.preco);

      var generos = document.getElementById("modalGeneros");
      generos.innerHTML = "";
      j.generos.split(" • ").forEach(function (g) {
        var tag = document.createElement("span");
        tag.textContent = g;
        generos.appendChild(tag);
      });

      marcar(btnCarr, !!carrinho[i]);
      marcar(btnFav, !!favoritos[i]);

      modal.classList.add("aberto");
      modal.setAttribute("aria-hidden", "false");
      document.body.classList.add("modal-aberto");
      document.getElementById("modalFechar").focus();
    }

    function fechar() {
      var video = document.getElementById("modalVideo");
      video.pause();
      video.removeAttribute("src");
      video.load();
      modal.classList.remove("aberto");
      modal.setAttribute("aria-hidden", "true");
      document.body.classList.remove("modal-aberto");
      if (ultimo) ultimo.focus();
    }

    // O vídeo fica mais abaixo no modal: toca quando aparece e pausa quando sai de vista
    var videoEl = document.getElementById("modalVideo");
    if ("IntersectionObserver" in window) {
      new IntersectionObserver(function (itens) {
        if (!modal.classList.contains("aberto") || !videoEl.getAttribute("src")) return;
        if (itens[0].isIntersecting) {
          var p = videoEl.play();
          if (p && p.catch) p.catch(function () {});
        } else {
          videoEl.pause();
        }
      }, { root: document.querySelector(".modal__caixa"), threshold: 0.5 }).observe(videoEl);
    }

    window.abrirJogo = abrir;

    // "Compre agora" do banner e os cards levam para a tela do jogo (catalogo.php).
    // O modal fica só para o botão "Surpreenda-me" (window.abrirJogo).
    document.getElementById("heroComprar").addEventListener("click", function () {
      window.location.href = "catalogo.php?jogo=banner";
    });

    cards.forEach(function (c) {
      function ir() { window.location.href = "catalogo.php?jogo=" + c.dataset.indice; }
      c.addEventListener("click", ir);
      c.addEventListener("keydown", function (e) {
        if (e.key === "Enter" || e.key === " ") { e.preventDefault(); ir(); }
      });
    });

    document.getElementById("modalFechar").addEventListener("click", fechar);
    modal.addEventListener("click", function (e) { if (e.target === modal) fechar(); });

    document.addEventListener("keydown", function (e) {
      if (!modal.classList.contains("aberto")) return;
      if (e.key === "Escape") { fechar(); return; }
      if (e.key === "Tab") {           // mantém o foco dentro do modal
        var foco = modal.querySelectorAll("button");
        var primeiro = foco[0], final = foco[foco.length - 1];
        if (e.shiftKey && document.activeElement === primeiro) { e.preventDefault(); final.focus(); }
        else if (!e.shiftKey && document.activeElement === final) { e.preventDefault(); primeiro.focus(); }
      }
    });

    btnCarr.addEventListener("click", function () {
      carrinho[jogoAtual] = !carrinho[jogoAtual];
      marcar(btnCarr, carrinho[jogoAtual]);
    });
    btnFav.addEventListener("click", function () {
      favoritos[jogoAtual] = !favoritos[jogoAtual];
      marcar(btnFav, favoritos[jogoAtual]);
    });
  })();
</script>

<?php include __DIR__ . "/view/footer.php"; ?>

</body>
</html>