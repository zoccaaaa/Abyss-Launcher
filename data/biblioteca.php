<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function bibliotecaDoUsuario(): array
{
    return $_SESSION['biblioteca'] ?? [];
}

function jogoJaComprado(int $idJogo): bool
{
    return isset(bibliotecaDoUsuario()[$idJogo]);
}


function adicionarNaBiblioteca(int $idJogo, int $totalConquistas = 34): void
{
    if (jogoJaComprado($idJogo)) {
        return;
    }
    $_SESSION['biblioteca'][$idJogo] = [
        'id'                 => $idJogo,
        'conquistas_obtidas' => 0,
        'conquistas_total'   => $totalConquistas,
        'comprado_em'        => date('Y-m-d H:i:s'),
    ];
}


function registrarConquista(int $idJogo, int $quantidade = 1): void
{
    if (!jogoJaComprado($idJogo)) {
        return;
    }
    $item = &$_SESSION['biblioteca'][$idJogo];
    $item['conquistas_obtidas'] = min($item['conquistas_total'], $item['conquistas_obtidas'] + $quantidade);
}

function calcularProgresso(array $item): int
{
    if ($item['conquistas_total'] <= 0) {
        return 0;
    }
    return (int) round($item['conquistas_obtidas'] / $item['conquistas_total'] * 100);
}


function montarBiblioteca(array $jogos): array
{
    $lista = [];
    foreach (bibliotecaDoUsuario() as $id => $item) {
        if (!isset($jogos[$id])) {
            continue;
        }
        $item['progresso'] = calcularProgresso($item);
        $lista[] = $item + $jogos[$id];
    }
    return $lista;
}

function estatisticasBiblioteca(array $lista): array
{
    $obtidas = array_sum(array_column($lista, 'conquistas_obtidas'));
    $total   = array_sum(array_column($lista, 'conquistas_total'));

    return [
        'jogos'     => count($lista),
        'conquistas'=> $obtidas,
        'concluido' => $total > 0 ? (int) round($obtidas / $total * 100) : 0,
    ];
}

function filtrarBiblioteca(array $lista, string $busca, string $filtro, string $ordem): array
{
    if ($busca !== '') {
        $lista = array_filter($lista, fn($j) => mb_stripos($j['nome'], $busca) !== false);
    }

    if ($filtro === 'andamento') {
        $lista = array_filter($lista, fn($j) => $j['progresso'] > 0 && $j['progresso'] < 100);
    } elseif ($filtro === 'nao-iniciados') {
        $lista = array_filter($lista, fn($j) => $j['conquistas_obtidas'] === 0);
    }

    $lista = array_values($lista);

    if ($ordem === 'progresso') {
        usort($lista, fn($a, $b) => $b['progresso'] <=> $a['progresso']);
    }

    return $lista;
}
