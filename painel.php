<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel administrativo</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/painel.css">
</head>
<body>
    <?php
    require './view/header.php';
    ?>

    <main class="painel">
        <nav class="comeco" aria-label="Navegação do painel">
            <a href="#visao-geral" class="txt ativo" aria-current="page">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 19V5m0 14h16M8 15V9m5 6V5m5 10v-4"/></svg>
                <h1>Visão geral</h1>
            </a>
            <a href="#" class="txt">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Z"/><path d="m4.5 7.8 7.5 4.3 7.5-4.3M12 12v9"/></svg>
                <h1>Produtos e estoque</h1>
            </a>
            <a href="#usuarios" class="txt">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2m5-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm12 10v-2a4 4 0 0 0-3-3.87m-1-11.13a4 4 0 0 1 0 7.75"/></svg>
                <h1>Usuários</h1>
            </a>
            <a href="#vendas" class="txt">
                <span class="dinheiro" aria-hidden="true">$</span>
                <h1>Vendas</h1>
            </a>
        </nav>

        <section class="visao-geral" id="visao-geral">
            <div class="titulo">
                <h1>Visão geral</h1>
                <p>Acompanhe as vendas, usuarios e estoque.</p>
            </div>

            <div class="resumos">
                <div class="resumo"><h2>Receita</h2><p>R$ 00,00</p></div>
                <div class="resumo"><h2>Pedidos concluidos</h2><p>0</p></div>
                <div class="resumo"><h2>Produtos ativos</h2><p>20</p></div>
                <div class="resumo"><h2>Produtos acabando</h2><p>8</p></div>
                <div class="resumo"><h2>Usuarios ativos</h2><p>3</p></div>
            </div>

            <section class="grafico" aria-label="Receita diária nos últimos sete dias">
                <h2>Receita diaria - Ultimos 7 dias</h2>
                <div class="barras">
                    <div class="barra"><span style="height: 46px"></span><p>29/10</p></div>
                    <div class="barra"><span style="height: 91px"></span><p>30/10</p></div>
                    <div class="barra"><span style="height: 63px"></span><p>31/10</p></div>
                    <div class="barra"><span style="height: 35px"></span><p>01/11</p></div>
                    <div class="barra"><span style="height: 22px"></span><p>02/11</p></div>
                    <div class="barra"><span style="height: 110px"></span><p>03/11</p></div>
                </div>
            </section>

            <section class="pedidos">
                <h2>Pedidos recentes</h2>
                <table>
                    <thead><tr><th>Pedido</th><th>Cliente</th><th>Data</th><th>Total</th></tr></thead>
                    <tbody><tr><td>#1</td><td>Zocca</td><td>04/11/2026</td><td>R$ 274,50</td></tr></tbody>
                </table>
            </section>
        </section>

        <section class="produtos" id="produtos">
            <div class="titulo">
                <h1>Produtos e estoque</h1>
                <p>Cadastre produtos e mantenha preços e quantidade atualizados.</p>
            </div>

            <section class="cadastro">
                <h2>Cadastrar produtos</h2>
                <form action="#produtos" method="post">
                    <div class="campos">
                        <div class="campo">
                            <label for="nome">Nome</label>
                            <input type="text" id="nome" name="nome" required>
                        </div>
                        <div class="campo">
                            <label for="marca">Marca</label>
                            <input type="text" id="marca" name="marca" required>
                        </div>
                        <div class="campo">
                            <label for="categoria">Categoria</label>
                            <input type="text" id="categoria" name="categoria" required>
                        </div>
                        <div class="campo">
                            <label for="imagem">URL da imagem</label>
                            <input type="url" id="imagem" name="imagem">
                        </div>
                        <div class="campo">
                            <label for="preco">Preço (R$)</label>
                            <input type="number" id="preco" name="preco" min="0" step="0.01" required>
                        </div>
                        <div class="campo">
                            <label for="estoque">Estoque</label>
                            <input type="number" id="estoque" name="estoque" min="0" step="1" required>
                        </div>
                    </div>
                    <div class="campo descricao">
                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao" rows="5"></textarea>
                    </div>
                    <button type="submit">Salvar produto</button>
                </form>
            </section>

            <section class="estoque">
                <h2>Estoque completo</h2>
                <div class="tabela-produtos">
                    <table>
                        <thead>
                            <tr><th>Produto</th><th>Categoria</th><th>Preço</th><th>Estoque</th><th>Status</th><th>Ações</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Elden Ring</strong><small>Digital</small></td>
                                <td>RPG • Soulslike</td>
                                <td>R$ 274,00</td>
                                <td>27</td>
                                <td><span class="status">Ativo</span></td>
                                <td class="acoes"><button type="button">Editar</button><button type="button">Desativar</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </section>

        <section class="usuarios" id="usuarios">
            <div class="titulo">
                <h1>Usuários</h1>
                <p>Edite os dados, altere o acesso ou desative a conta.</p>
            </div>

            <section class="contas">
                <h2>Contas cadastradas</h2>
                <div class="busca-contas">
                    <label for="busca-usuario">Buscar por nome ou email</label>
                    <div class="campo-busca">
                        <input type="search" id="busca-usuario" name="busca-usuario">
                        <button type="button">Buscar</button>
                    </div>
                </div>
                <div class="tabela-usuarios">
                    <table>
                        <thead>
                            <tr><th>Conta</th><th>Acesso</th><th>Status</th><th>Ações</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Carrasqueira</strong></td>
                                <td>Administrador</td>
                                <td><span class="status">Ativo</span></td>
                                <td><button type="button">Editar</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </section>
    </main>
</body>
</html>
