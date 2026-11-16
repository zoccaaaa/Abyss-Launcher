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
    tipo ENUM('admin', 'usuario') DEFAULT 'usuario',
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    justificativa_desativacao TEXT NULL,
    PRIMARY KEY (id_usuario),
    UNIQUE KEY email (email)
);

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