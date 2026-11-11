<?php

$selecao = $_GET['secao'] ?? 'painel';

$secoes = ['painel', 'produtos', 'usuarios', 'vendas'];

if (!in_array($selecao, $secoes, true)) {
    $selecao = 'painel';
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel administrativo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/painel.css">
</head>

<body>

    <?php require './view/header.php'; ?>

    <main class="painel">

        <nav class="comeco" aria-label="Navegação do painel">

            <button type="button" onclick="mudarSecao('painel')" class="txt <?= $selecao === 'painel' ? 'ativo' : '' ?>"
                <?= $selecao === 'painel' ? 'aria-current="page"' : '' ?>>
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M4 19V5m0 14h16M8 15V9m5 6V5m5 10v-4" />
                </svg>
                <h1>Visão geral</h1>
            </button>

            <button type="button" onclick="mudarSecao('produtos')"
                class="txt <?= $selecao === 'produtos' ? 'ativo' : '' ?>" <?= $selecao === 'produtos' ? 'aria-current="page"' : '' ?>>
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Z" />
                    <path d="m4.5 7.8 7.5 4.3 7.5-4.3M12 12v9" />
                </svg>
                <h1>Produtos e estoque</h1>
            </button>

            <button type="button" onclick="mudarSecao('usuarios')"
                class="txt <?= $selecao === 'usuarios' ? 'ativo' : '' ?>" <?= $selecao === 'usuarios' ? 'aria-current="page"' : '' ?>>
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2m5-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm12 10v-2a4 4 0 0 0-3-3.87m-1-11.13a4 4 0 0 1" />
                </svg>
                <h1>Usuários</h1>
            </button>

            <button type="button" onclick="mudarSecao('vendas')" class="txt <?= $selecao === 'vendas' ? 'ativo' : '' ?>"
                <?= $selecao === 'vendas' ? 'aria-current="page"' : '' ?>>
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2m5-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm12 10v-2a4 4 0 0 0-3-3.87m-1-11.13a4 4 0 0 1" />
                </svg>
                <h1>Vendas</h1>
            </button>

        </nav>

        <!-- VISÃO GERAL -->
        <section class="visao-geral" id="painel" <?= $selecao !== 'painel' ? 'hidden' : '' ?>>

            <div class="titulo">
                <h1>Visão geral</h1>
                <p>Acompanhe as vendas, usuários e estoque.</p>
            </div>

            <div class="resumos">
                <div class="resumo">
                    <h2>Receita</h2>
                    <p>R$ 00,00</p>
                </div>
                <div class="resumo">
                    <h2>Pedidos concluídos</h2>
                    <p>0</p>
                </div>
                <div class="resumo">
                    <h2>Produtos ativos</h2>
                    <p>20</p>
                </div>
                <div class="resumo">
                    <h2>Produtos acabando</h2>
                    <p>8</p>
                </div>
                <div class="resumo">
                    <h2>Usuários ativos</h2>
                    <p>3</p>
                </div>
            </div>

            <section class="grafico" aria-label="Receita diária nos últimos sete dias">
                <h2>Receita diária - Últimos 7 dias</h2>
                <div class="barras">
                    <div class="barra">
                        <span style="height: 46px"></span>
                        <p>29/10</p>
                    </div>
                    <div class="barra">
                        <span style="height: 91px"></span>
                        <p>30/10</p>
                    </div>
                    <div class="barra">
                        <span style="height: 63px"></span>
                        <p>31/10</p>
                    </div>
                    <div class="barra">
                        <span style="height: 35px"></span>
                        <p>01/11</p>
                    </div>
                    <div class="barra">
                        <span style="height: 22px"></span>
                        <p>02/11</p>
                    </div>
                    <div class="barra">
                        <span style="height: 110px"></span>
                        <p>03/11</p>
                    </div>
                </div>
            </section>

            <section class="pedidos">
                <h2>Pedidos recentes</h2>
                <table>
                    <thead>
                        <tr>
                            <th class="coluna-tabela">Pedido</th>
                            <th class="coluna-tabela">Cliente</th>
                            <th class="coluna-tabela">Data</th>
                            <th class="coluna-tabela">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="celula-tabela">#1</td>
                            <td class="celula-tabela">Zocca</td>
                            <td class="celula-tabela">04/11/2026</td>
                            <td class="celula-tabela">R$ 274,50</td>
                        </tr>
                    </tbody>
                </table>
            </section>

        </section>

        <!-- PRODUTOS E ESTOQUE -->
        <section class="produtos" id="produtos" <?= $selecao !== 'produtos' ? 'hidden' : '' ?>>

            <div class="titulo">
                <h1>Produtos e estoque</h1>
                <p>Cadastre produtos e mantenha preços e quantidade atualizados.</p>
            </div>

            <section class="cadastro">
                <h2>Cadastrar produtos</h2>

                <form action="?secao=produtos#produtos" method="post">
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
                            <select id="categoria" name="categoria" required>
                                <option value="" selected disabled>Selecione uma categoria</option>
                                <option value="Ação">Ação</option>
                                <option value="Anime">Anime</option>
                                <option value="Aventura">Aventura</option>
                                <option value="Cooperativo">Cooperativo</option>
                                <option value="Faroeste">Faroeste</option>
                                <option value="Furtividade">Furtividade</option>
                                <option value="Hack And Slash">Hack And Slash</option>
                                <option value="Indie">Indie</option>
                                <option value="Lego">Lego</option>
                                <option value="Luta">Luta</option>
                                <option value="Esporte">Esporte</option>
                                <option value="Metroidvania">Metroidvania</option>
                                <option value="Mundo aberto">Mundo aberto</option>
                                <option value="Plataforma">Plataforma</option>
                                <option value="Rpg">Rpg</option>
                                <option value="Sobrevivencia">Sobrevivencia</option>
                                <option value="Soulslike">Soulslike</option>
                                <option value="Terror">Terror</option>
                            </select>
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
                            <tr>
                                <th class="coluna-tabela">Produto</th>
                                <th class="coluna-tabela">Categoria</th>
                                <th class="coluna-tabela">Preço</th>
                                <th class="coluna-tabela">Estoque</th>
                                <th class="coluna-tabela">Status</th>
                                <th class="coluna-tabela coluna-acao">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="celula-tabela produto-info">
                                    <span class="produto-nome">Elden Ring</span>
                                    <span class="produto-tipo">Digital</span>
                                </td>
                                <td class="celula-tabela">RPG • Soulslike</td>
                                <td class="celula-tabela">R$ 274,00</td>
                                <td class="celula-tabela">27</td>
                                <td class="celula-tabela"><span class="status">Ativo</span></td>
                                <td class="celula-tabela acoes">
                                    <button type="button">Editar</button>
                                    <button class="botao-desativar" type="button">Desativar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

        </section>

        <!-- USUÁRIOS -->
        <section class="usuarios" id="usuarios" <?= $selecao !== 'usuarios' ? 'hidden' : '' ?>>

            <div class="titulo">
                <h1>Usuários</h1>
                <p>Edite os dados, altere o acesso ou desative a conta.</p>
            </div>

            <section class="contas">
                <h2>Contas cadastradas</h2>

                <div class="busca-contas">
                    <label for="busca-usuario">Buscar contas</label>
                    <div class="campo-busca">
                        <select id="filtro-usuario" name="filtro-usuario" aria-label="Filtrar pesquisa por">
                            <option value="nome">Nome</option>
                            <option value="email">Email</option>
                            <option value="acesso">Acesso</option>
                        </select>
                        <input type="search" id="busca-usuario" name="busca-usuario" placeholder="Digite sua pesquisa">
                        <button type="button">Buscar</button>
                    </div>
                </div>

                <div class="tabela-usuarios">
                    <table>
                        <thead>
                            <tr>
                                <th class="coluna-tabela">Nome</th>
                                <th class="coluna-tabela">Email</th>
                                <th class="coluna-tabela">Acesso</th>
                                <th class="coluna-tabela">Status</th>
                                <th class="coluna-tabela sem-borda">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="celula-tabela">Carrasqueira</td>
                                <td class="celula-tabela">carrasqueira@email.com</td>
                                <td class="celula-tabela">Administrador</td>
                                <td class="celula-tabela"><span class="status">Ativo</span></td>
                                <td class="celula-tabela sem-borda">
                                    <button type="button">Editar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

        </section>

        <!-- VENDAS -->
        <section class="vendas" id="vendas" <?= $selecao !== 'vendas' ? 'hidden' : '' ?>>

            <div class="titulo">
                <h1>Vendas</h1>
                <p>Receita e pedidos registrados.</p>
            </div>

            <div class="resumos">
                <div class="resumo">
                    <h2>Receita registrada</h2>
                    <p>R$ 00,00</p>
                </div>
                <div class="resumo">
                    <h2>Pedidos concluídos</h2>
                    <p>0</p>
                </div>
                <div class="resumo">
                    <h2>Ticket médio</h2>
                    <p>R$ 0,00</p>
                </div>
                <div class="resumo">
                    <h2>Período no gráfico</h2>
                    <select id="periodo-grafico" name="periodo-grafico" aria-label="Período do gráfico">
                        <option value="1">1 mês</option>
                        <option value="6">6 meses</option>
                        <option value="12" selected>12 meses</option>
                    </select>
                </div>
            </div>

            <section class="grafico-vendas" aria-label="Receita mensal nos últimos 12 meses">
                <h2>Receita mensal - Últimos 12 meses</h2>

                <div class="barras-vendas">
                    <div class="barra-vendas"><span style="height: 5px"></span>
                        <p>12/25</p>
                    </div>
                    <div class="barra-vendas"><span style="height: 5px"></span>
                        <p>01/26</p>
                    </div>
                    <div class="barra-vendas"><span style="height: 6px"></span>
                        <p>02/26</p>
                    </div>
                    <div class="barra-vendas"><span style="height: 5px"></span>
                        <p>03/26</p>
                    </div>
                    <div class="barra-vendas"><span style="height: 5px"></span>
                        <p>04/26</p>
                    </div>
                    <div class="barra-vendas"><span style="height: 5px"></span>
                        <p>05/26</p>
                    </div>
                    <div class="barra-vendas"><span style="height: 5px"></span>
                        <p>06/26</p>
                    </div>
                    <div class="barra-vendas"><span style="height: 5px"></span>
                        <p>07/26</p>
                    </div>
                    <div class="barra-vendas"><span style="height: 5px"></span>
                        <p>08/26</p>
                    </div>
                    <div class="barra-vendas"><span style="height: 5px"></span>
                        <p>09/26</p>
                    </div>
                    <div class="barra-vendas"><span style="height: 5px"></span>
                        <p>10/26</p>
                    </div>
                    <div class="barra-vendas"><span style="height: 35px"></span>
                        <p>11/26</p>
                    </div>
                </div>
            </section>

            <section class="historico-vendas">
                <h2>Histórico de pedido</h2>

                <div class="tabela-vendas">
                    <table>
                        <thead>
                            <tr>
                                <th class="coluna-tabela">Pedido</th>
                                <th class="coluna-tabela">Cliente</th>
                                <th class="coluna-tabela">Data</th>
                                <th class="coluna-tabela">Status</th>
                                <th class="coluna-tabela">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="celula-tabela">#1</td>
                                <td class="celula-tabela">Zocca</td>
                                <td class="celula-tabela">04/11/2026</td>
                                <td class="celula-tabela"><span class="concluido">Concluído</span></td>
                                <td class="celula-tabela">R$ 274,50</td>
                            </tr>
                            <tr>
                                <td class="celula-tabela">#2</td>
                                <td class="celula-tabela">Carrasqueira</td>
                                <td class="celula-tabela">02/11/2026</td>
                                <td class="celula-tabela"><span class="cancelado">Cancelado</span></td>
                                <td class="celula-tabela">R$ 249,99</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

        </section>

    </main>

    <script>
        function mudarSecao(secao) {
            const secoes = ['painel', 'produtos', 'usuarios', 'vendas'];

            // Impede a tentativa de abrir uma seção inexistente.
            if (!secoes.includes(secao)) {
                return;
            }

            // Esconde todas as seções.
            secoes.forEach(function (nome) {
                document.getElementById(nome).hidden = true;
            });

            // Exibe somente a seção escolhida.
            document.getElementById(secao).hidden = false;

            // Atualiza o botão ativo.
            document.querySelectorAll('.comeco button').forEach(function (botao) {
                botao.classList.remove('ativo');
                botao.removeAttribute('aria-current');
            });

            const botaoSelecionado = document.querySelector(
                `.comeco button[onclick="mudarSecao('${secao}')"]`
            );

            if (botaoSelecionado) {
                botaoSelecionado.classList.add('ativo');
                botaoSelecionado.setAttribute('aria-current', 'page');
            }
        }

        const seletorPeriodo = document.querySelector('#periodo-grafico');
        const barrasVendas = [...document.querySelectorAll('.barra-vendas')];
        const containerBarrasVendas = document.querySelector('.barras-vendas');
        const graficoVendas = document.querySelector('.grafico-vendas');
        const tituloGraficoVendas = graficoVendas.querySelector('h2');

        function atualizarGraficoVendas() {
            const quantidade = Number(seletorPeriodo.value);
            containerBarrasVendas.querySelectorAll('.barra-diaria').forEach((barra) => barra.remove());

            if (quantidade === 1) {
                const diasNoMes = 30;
                containerBarrasVendas.classList.add('grafico-diario');
                containerBarrasVendas.style.setProperty('--quantidade-meses', diasNoMes);
                barrasVendas.forEach((barra) => { barra.hidden = true; });

                for (let dia = 1; dia <= diasNoMes; dia++) {
                    const barra = document.createElement('div');
                    const coluna = document.createElement('span');
                    const rotulo = document.createElement('p');
                    const altura = 14 + ((dia * 17 + dia * dia * 3) % 82);

                    barra.className = 'barra-vendas barra-diaria';
                    coluna.style.height = `${altura}px`;
                    rotulo.textContent = String(dia).padStart(2, '0');
                    barra.append(coluna, rotulo);
                    containerBarrasVendas.append(barra);
                }

                tituloGraficoVendas.textContent = 'Receita diária - Novembro 2026';
                graficoVendas.setAttribute('aria-label', 'Receita diária durante novembro de 2026');
                return;
            }

            containerBarrasVendas.classList.remove('grafico-diario');
            containerBarrasVendas.style.setProperty('--quantidade-meses', quantidade);
            barrasVendas.forEach((barra, indice) => {
                barra.hidden = indice < barrasVendas.length - quantidade;
            });

            const periodoTexto = `últimos ${quantidade} meses`;
            tituloGraficoVendas.textContent = `Receita mensal - ${periodoTexto}`;
            graficoVendas.setAttribute('aria-label', `Receita mensal nos ${periodoTexto}`);
        }

        seletorPeriodo.addEventListener('change', atualizarGraficoVendas);
        atualizarGraficoVendas();
    </script>

</body>

</html>
