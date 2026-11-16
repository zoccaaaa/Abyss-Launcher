<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once __DIR__ . '/conexao.php';

$paginaProtegida = basename($_SERVER['PHP_SELF'] ?? '');
$usuarioLogado = !empty($_SESSION['usuario_id']);

if ($usuarioLogado) {
    $consultaSessao = $conn->prepare('SELECT nome, tipo, ativo FROM usuarios WHERE id_usuario=?');
    $consultaSessao->bind_param('i', $_SESSION['usuario_id']);
    $consultaSessao->execute();
    $usuarioSessao = $consultaSessao->get_result()->fetch_assoc();

    if (!$usuarioSessao || (int) $usuarioSessao['ativo'] !== 1) {
        $_SESSION = [];
        session_destroy();
        $usuarioLogado = false;
    } else {
        $_SESSION['usuario_nome'] = $usuarioSessao['nome'];
        $_SESSION['usuario_tipo'] = $usuarioSessao['tipo'];
    }
}

$usuarioAdmin = $usuarioLogado && ($_SESSION['usuario_tipo'] ?? '') === 'admin';

if (!$usuarioLogado) {
    $destino = basename($_SERVER['PHP_SELF'] ?? 'index.php');
    header('Location: ./nao-logado.php?destino=' . rawurlencode($destino));
    exit;
}

if ($paginaProtegida === 'painel.php' && !$usuarioAdmin) {
    header('Location: ./index.php');
    exit;
}
