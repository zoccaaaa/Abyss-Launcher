<?php
require __DIR__ . '/data/jogos.php';
require __DIR__ . '/data/biblioteca.php';

// Modo teste: abra index.php?demo=1 para colocar os jogos do catálogo na biblioteca.
if (isset($_GET['demo'])) {
    foreach (array_keys($jogos) as $idDemo) {
        adicionarNaBiblioteca((int) $idDemo);
    }
    header('Location: index.php');
    exit;
}

$busca  = trim($_GET['busca']  ?? '');
$filtro = $_GET['filtro'] ?? '';
$ordem  = $_GET['ordem']  ?? '';

$biblioteca = montarBiblioteca($jogos);
$stats      = estatisticasBiblioteca($biblioteca);
$exibidos   = filtrarBiblioteca($biblioteca, $busca, $filtro, $ordem);

function linkFiltro(string $chave, string $valor, string $busca, string $filtro, string $ordem): string
{
    $params = ['busca' => $busca, 'filtro' => $filtro, 'ordem' => $ordem];
    $params[$chave] = ($params[$chave] === $valor) ? '' : $valor;
    return '?' . http_build_query(array_filter($params, fn($v) => $v !== ''));
}

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

$tituloPagina = 'Minha Biblioteca';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($tituloPagina) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/biblioteca.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>

<?php require __DIR__ . '/view/header.php'; ?>

<main class="biblioteca">

    <section class="biblioteca__topo">
        <div class="biblioteca__titulo">
            <h1>Minha Biblioteca</h1>
            <p>Seus jogos, suas conquistas.</p>
        </div>

        <dl class="biblioteca__stats">
            <div>
                <dt>Jogos</dt>
                <dd><?= $stats['jogos'] ?></dd>
            </div>
            <div>
                <dt>Conquistas</dt>
                <dd><?= $stats['conquistas'] ?></dd>
            </div>
            <div>
                <dt>Concluído</dt>
                <dd><?= $stats['concluido'] ?>%</dd>
            </div>
        </dl>
    </section>

    <section class="biblioteca__barra">
        <form class="busca" method="get">
            <?php if ($filtro !== ''): ?><input type="hidden" name="filtro" value="<?= e($filtro) ?>"><?php endif; ?>
            <?php if ($ordem !== ''): ?><input type="hidden" name="ordem" value="<?= e($ordem) ?>"><?php endif; ?>
            <input type="search" name="busca" value="<?= e($busca) ?>" placeholder="Pesquisar na biblioteca..." aria-label="Pesquisar na biblioteca">
        </form>

        <nav class="filtros" aria-label="Filtros">
            <a class="filtros__botao <?= $filtro === 'andamento' ? 'is-ativo' : '' ?>"
               href="<?= linkFiltro('filtro', 'andamento', $busca, $filtro, $ordem) ?>">Em andamento</a>
            <a class="filtros__botao <?= $filtro === 'nao-iniciados' ? 'is-ativo' : '' ?>"
               href="<?= linkFiltro('filtro', 'nao-iniciados', $busca, $filtro, $ordem) ?>">Não iniciados</a>
            <a class="filtros__botao <?= $ordem === 'progresso' ? 'is-ativo' : '' ?>"
               href="<?= linkFiltro('ordem', 'progresso', $busca, $filtro, $ordem) ?>">Ordenar por progresso</a>
        </nav>
    </section>

    <?php if (empty($exibidos)): ?>
        <p class="biblioteca__vazio">
            <?= $stats['jogos'] === 0
                ? 'Você ainda não tem jogos. Os jogos que você comprar aparecem aqui.'
                : 'Nenhum jogo encontrado com esses filtros.' ?>
        </p>
    <?php else: ?>
        <section class="biblioteca__grade">
            <?php foreach ($exibidos as $jogo): ?>
                <a class="card" href="jogo.php?id=<?= (int) $jogo['id'] ?>"
                   style="--cor1: <?= e($jogo['cor1']) ?>; --cor2: <?= e($jogo['cor2']) ?>;">
                    <img class="card__capa" src="<?= e($jogo['imagem']) ?>" alt="Capa de <?= e($jogo['nome']) ?>">

                    <div class="card__progresso">
                        <span class="card__conquistas"><?= $jogo['conquistas_obtidas'] ?>/<?= $jogo['conquistas_total'] ?></span>
                        <div class="barra" role="progressbar" aria-valuenow="<?= $jogo['progresso'] ?>" aria-valuemin="0" aria-valuemax="100">
                            <div class="barra__preenchida" style="width: <?= $jogo['progresso'] ?>%"></div>
                        </div>
                        <span class="card__percentual"><?= $jogo['progresso'] ?>%</span>
                        <span class="card__trofeu" aria-hidden="true">🏆</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

</main>

<?php require __DIR__ . '/view/footer.php'; ?>

</body>
</html>
