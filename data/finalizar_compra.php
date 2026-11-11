<?php

require __DIR__ . '/jogos.php';
require __DIR__ . '/biblioteca.php';

$idJogo = (int) ($_POST['id_jogo'] ?? 0);

if (!isset($jogos[$idJogo])) {
    http_response_code(404);
    exit('Jogo não encontrado.');
}



adicionarNaBiblioteca($idJogo);

header('Location: ../index.php');
exit;
