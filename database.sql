CREATE DATABASE IF NOT EXISTS projetofinal
    CHARACTER SET utf8mb4;

USE projetofinal;

CREATE TABLE IF NOT EXISTS categorias (
    id_categoria INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    PRIMARY KEY (id_categoria)
);

CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(30) NOT NULL,
    email VARCHAR(150) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    cep CHAR(8) NULL,
    estado CHAR(2) NULL,
    cidade VARCHAR(100) NULL,
    rua VARCHAR(150) NULL,
    numero VARCHAR(20) NULL,
    descricao VARCHAR(500) NULL,
    foto_perfil VARCHAR(255) NULL,
    foto_banner VARCHAR(255) NULL,
    tipo ENUM('admin', 'usuario') DEFAULT 'usuario',
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    justificativa_desativacao TEXT NULL,
    PRIMARY KEY (id_usuario),
    UNIQUE KEY email (email)
);

SET @coluna_descricao_perfil = (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'usuarios' AND COLUMN_NAME = 'descricao'
);
SET @sql_descricao_perfil = IF(
    @coluna_descricao_perfil = 0,
    'ALTER TABLE usuarios ADD COLUMN descricao VARCHAR(500) NULL',
    'SELECT 1'
);
PREPARE stmt_descricao_perfil FROM @sql_descricao_perfil;
EXECUTE stmt_descricao_perfil;
DEALLOCATE PREPARE stmt_descricao_perfil;

SET @coluna_foto_perfil = (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'usuarios' AND COLUMN_NAME = 'foto_perfil'
);
SET @sql_foto_perfil = IF(
    @coluna_foto_perfil = 0,
    'ALTER TABLE usuarios ADD COLUMN foto_perfil VARCHAR(255) NULL',
    'SELECT 1'
);
PREPARE stmt_foto_perfil FROM @sql_foto_perfil;
EXECUTE stmt_foto_perfil;
DEALLOCATE PREPARE stmt_foto_perfil;

SET @coluna_foto_banner = (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'usuarios' AND COLUMN_NAME = 'foto_banner'
);
SET @sql_foto_banner = IF(
    @coluna_foto_banner = 0,
    'ALTER TABLE usuarios ADD COLUMN foto_banner VARCHAR(255) NULL',
    'SELECT 1'
);
PREPARE stmt_foto_banner FROM @sql_foto_banner;
EXECUTE stmt_foto_banner;
DEALLOCATE PREPARE stmt_foto_banner;

CREATE TABLE IF NOT EXISTS jogos (
    id_jogo INT NOT NULL AUTO_INCREMENT,
    id_categoria INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    estoque INT NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    imagem VARCHAR(255) DEFAULT NULL,
    favorito TINYINT(1) DEFAULT '0',
    PRIMARY KEY (id_jogo),
    KEY id_categoria (id_categoria),
    CONSTRAINT jogos_ibfk_1 FOREIGN KEY (id_categoria) REFERENCES categorias (id_categoria)
);

CREATE TABLE IF NOT EXISTS pedidos (
    id_pedido INT NOT NULL AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    data_pedido TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    valor_total DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (id_pedido),
    KEY id_usuario (id_usuario),
    CONSTRAINT pedidos_ibfk_1 FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
);

CREATE TABLE IF NOT EXISTS itens_pedido (
    id_itens_pedido INT NOT NULL AUTO_INCREMENT,
    id_pedido INT NOT NULL,
    id_jogo INT NOT NULL,
    quantidade INT NOT NULL,
    PRIMARY KEY (id_itens_pedido),
    KEY id_pedido (id_pedido),
    KEY id_jogo (id_jogo),
    CONSTRAINT itens_pedido_ibfk_1 FOREIGN KEY (id_pedido) REFERENCES pedidos (id_pedido),
    CONSTRAINT itens_pedido_ibfk_2 FOREIGN KEY (id_jogo) REFERENCES jogos (id_jogo)
);

CREATE TABLE IF NOT EXISTS comentarios (
    id_comentario INT NOT NULL AUTO_INCREMENT,
    id_jogo INT NOT NULL,
    id_usuario INT NOT NULL,
    comentario TEXT NOT NULL,
    nota INT DEFAULT NULL,
    criado_em TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_comentario),
    KEY id_jogo (id_jogo),
    KEY id_usuario (id_usuario),
    CONSTRAINT comentarios_ibfk_1 FOREIGN KEY (id_jogo) REFERENCES jogos (id_jogo),
    CONSTRAINT comentarios_ibfk_2 FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
);

CREATE TABLE IF NOT EXISTS movimentacoes_estoque (
    id_movimentacoes_estoque INT NOT NULL AUTO_INCREMENT,
    id_jogo INT NOT NULL,
    id_usuario INT NOT NULL,
    tipo ENUM('entrada', 'saida') NOT NULL,
    quantidade INT NOT NULL,
    PRIMARY KEY (id_movimentacoes_estoque)
);
