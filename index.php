<?php
include __DIR__ . "/data/jogos.php";


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
        <button class="btn-comprar">Compre agora</button>
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
        <article class="card" id="jogo-<?= $i ?>" data-generos="<?= htmlspecialchars(str_replace(" • ", "|", $j["generos"])) ?>">
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
      sorteado.scrollIntoView({ behavior: "smooth", block: "center" });
    });
  })();
</script>


<?php include __DIR__ . "/view/footer.php"; ?>

</body>
</html>