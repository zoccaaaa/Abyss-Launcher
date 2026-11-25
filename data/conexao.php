<?php
$host = getenv('ABYSS_DB_HOST') ?: 'localhost';
$dbname = getenv('ABYSS_DB_NAME') ?: 'projetofinal';
$user = getenv('ABYSS_DB_USER') ?: 'root';
$password = getenv('ABYSS_DB_PASSWORD');
if ($password === false) {
    $password = 'root'; // Compatibilidade com a configuração local atual.
}

try {
    $conn = new mysqli($host, $user, $password, $dbname);
} catch (mysqli_sql_exception $erro) {
    error_log('Falha ao conectar ao banco Abyss: ' . $erro->getMessage());
    http_response_code(500);
    exit('Não foi possível conectar ao banco de dados.');
}
if ($conn->connect_error) {
    error_log('Falha ao conectar ao banco Abyss: ' . $conn->connect_error);
    http_response_code(500);
    exit('Não foi possível conectar ao banco de dados.');
}
$conn->set_charset('utf8mb4');

// Atualiza bancos existentes com os campos de perfil adicionados ao projeto.
$colunasUsuario = $conn->query('SHOW COLUMNS FROM usuarios');
$nomesColunasUsuario = [];
while ($colunaUsuario = $colunasUsuario->fetch_assoc()) {
    $nomesColunasUsuario[] = $colunaUsuario['Field'];
}

$alteracoesPerfil = [];
if (!in_array('descricao', $nomesColunasUsuario, true)) {
    $alteracoesPerfil[] = 'ADD COLUMN descricao VARCHAR(500) NULL';
}
if (!in_array('foto_perfil', $nomesColunasUsuario, true)) {
    $alteracoesPerfil[] = 'ADD COLUMN foto_perfil VARCHAR(255) NULL';
}
if (!in_array('foto_banner', $nomesColunasUsuario, true)) {
    $alteracoesPerfil[] = 'ADD COLUMN foto_banner VARCHAR(255) NULL';
}

if ($alteracoesPerfil) {
    $conn->query('ALTER TABLE usuarios ' . implode(', ', $alteracoesPerfil));
}
