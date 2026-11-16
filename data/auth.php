<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$paginaProtegida = basename($_SERVER['PHP_SELF'] ?? '');
$usuarioLogado = !empty($_SESSION['usuario_id']);
$usuarioAdmin = ($_SESSION['usuario_tipo'] ?? '') === 'admin';

if (!$usuarioLogado) {
    $destino = basename($_SERVER['PHP_SELF'] ?? 'index.php');
    header('Location: ./nao-logado.php?destino=' . rawurlencode($destino));
    exit;
}

if ($paginaProtegida === 'painel.php' && !$usuarioAdmin) {
    header('Location: ./index.php');
    exit;
}
