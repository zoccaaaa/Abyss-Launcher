<?php
$jogos = require __DIR__ . '/data/jogos.php';
$id    = $_GET['id'] ?? 'peak';
$tipo  = $_GET['tipo'] ?? 'fisico';
$cond  = $_GET['cond'] ?? 'lacrado';

if (!isset($jogos[$id])) { http_response_code(404); exit('Jogo não encontrado'); }
$j = $jogos[$id];
if (!isset($j['condicoes'][$cond])) $cond = 'lacrado';
$c = $j['condicoes'][$cond];

$e     = fn($s) => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
$preco = fn($v) => 'R$ ' . number_format($v, 2, ',', '.');
$url   = fn(array $p) => '?' . http_build_query(array_merge(['id'=>$id,'tipo'=>$tipo,'cond'=>$cond], $p));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $e($j['titulo']) ?></title>
<link rel="stylesheet" href="css/jogos.css">
<link rel="stylesheet" href="css/header.css">
<link rel="stylesheet" href="css/footer.css">
</head>
<body>
<?php include __DIR__ . "/view/header.php"; ?>

<div class="banner" style="background-image:url('<?= $e($j['imagem']) ?>')">
  <div class="bar">
    <div>
      <strong>Mídia física <?= $cond === 'lacrado' ? 'lacrado' : 'seminovo' ?></strong><br>
      <small>Em estoque (<?= (int)$c['estoque'] ?> un.)</small>
    </div>
    <div class="tabs">
      <a href="<?= $url(['tipo'=>'fisico']) ?>"  class="<?= $tipo==='fisico'?'on':'' ?>">Mídia física</a>
      <a href="<?= $url(['tipo'=>'digital']) ?>" class="<?= $tipo==='digital'?'on':'' ?>">Versão digital</a>
    </div>
  </div>
</div>

<div class="wrap">
  <main>
    <div class="card">
      <strong>Lançado em <?= $e($j['lancamento']) ?></strong><br>
      <strong>Publicado por <?= $e($j['publicadora']) ?></strong>
    </div>

    <?php if (!empty($j['video'])): ?>
      <video class="cover" controls playsinline preload="metadata" poster="<?= $e($j['imagem']) ?>">
        <source src="<?= $e($j['video']) ?>" type="video/mp4">
        Seu navegador não suporta vídeo.
      </video>
    <?php else: ?>
      <img class="cover" src="<?= $e($j['imagem']) ?>" alt="<?= $e($j['titulo']) ?>">
    <?php endif; ?>

    <div class="card">
      <h3>Sobre o jogo</h3>
      <p><?= $e($j['sobre']) ?></p>
      <p>Classificação: <?= $e($j['classificacao']) ?></p>
    </div>

    <div class="card">
      <h3>Ficha do produto</h3>
      <dl>
        <dt>Plataformas</dt><dd><?= $e(implode(', ', $j['plataformas'])) ?></dd>
        <dt>Gênero</dt><dd><?= $e($j['genero']) ?></dd>
        <dt>Idioma</dt><dd><?= $e($j['idioma']) ?></dd>
        <dt>Formatos</dt><dd><?= $e($j['formatos']) ?></dd>
      </dl>
    </div>
  </main>

  <aside>
    <div class="card">
      <p>Plataforma<br>
        <?php foreach ($j['plataformas'] as $p): ?><span class="tag"><?= $e($p) ?></span><?php endforeach; ?>
      </p>

      <?php if ($tipo === 'fisico'): ?>
        <p>Condição</p>
        <div class="cond">
          <?php foreach ($j['condicoes'] as $k => $o): ?>
            <a href="<?= $url(['cond'=>$k]) ?>" class="<?= $k===$cond?'on':'' ?>">
              <?= $e($o['label']) ?><br><small><?= $preco($o['preco']) ?></small>
            </a>
          <?php endforeach; ?>
        </div>
        <div class="preco"><?= $preco($c['preco']) ?></div>
        <small>Em estoque (<?= (int)$c['estoque'] ?> un.)</small>
        <a class="btn" href="#">Comprar físico</a>
      <?php else: ?>
        <div class="preco"><?= $preco($j['digital']) ?></div>
        <small>Entrega imediata por código digital</small>
        <a class="btn" href="#">Comprar digital</a>
      <?php endif; ?>
    </div>
  </aside>
</div>


<?php include __DIR__ . "/view/footer.php"; ?>

</body>
</html>