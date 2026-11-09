<?php
include __DIR__ . "/data/jogos.php";


$contagem = [];
foreach ($jogos as $j) {
    foreach (explode(" • ", $j["generos"]) as $g) {
        $contagem[$g] = ($contagem[$g] ?? 0) + 1;
    }
}
arsort($contagem);
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
      <input type="text" id="busca" placeholder="Pesquisa jogos no catálogo...">
      <button type="button">Surpreenda-me</button>
    </form>
   
    <main class="grid">
      <?php foreach ($jogos as $j) { ?>
        <article class="card" data-generos="<?= htmlspecialchars(str_replace(" • ", "|", $j["generos"])) ?>">
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
      var termo = limpar(busca.value.trim());
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

    busca.addEventListener("input", filtrar);
    document.querySelector(".busca").addEventListener("submit", function (e) { e.preventDefault(); });
  })();
</script>


<?php include __DIR__ . "/view/footer.php"; ?>

</body>
</html>