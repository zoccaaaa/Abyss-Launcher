<?php
require './data/auth.php';

if (empty($_SESSION['csrf_painel'])) {
    $_SESSION['csrf_painel'] = bin2hex(random_bytes(32));
}

function painelTamanhoTexto(string $texto): int
{
    return function_exists('mb_strlen') ? mb_strlen($texto, 'UTF-8') : strlen($texto);
}

$feedbackUsuarios = $_SESSION['feedback_usuarios'] ?? '';
unset($_SESSION['feedback_usuarios']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'editar_usuario') {
    $csrfRecebido = is_string($_POST['csrf_token'] ?? null) ? $_POST['csrf_token'] : '';
    if (!hash_equals($_SESSION['csrf_painel'], $csrfRecebido)) {
        $_SESSION['feedback_usuarios'] = 'Sua sessão expirou. Atualize a página e tente novamente.';
    } else {
        $idUsuario = filter_input(INPUT_POST, 'id_usuario', FILTER_VALIDATE_INT);
        $nomeUsuario = is_string($_POST['nome'] ?? null) ? trim($_POST['nome']) : '';
        $emailUsuario = is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '';
        $tipoUsuario = is_string($_POST['tipo'] ?? null) ? $_POST['tipo'] : '';
        $ativoUsuario = ($_POST['ativo'] ?? null) === '1' ? 1 : 0;
        $justificativa = is_string($_POST['justificativa'] ?? null) ? trim($_POST['justificativa']) : '';

        if (!$idUsuario || $nomeUsuario === '' || !filter_var($emailUsuario, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['feedback_usuarios'] = 'Informe um nome e um e-mail válidos.';
        } elseif (!in_array($tipoUsuario, ['admin', 'usuario'], true)) {
            $_SESSION['feedback_usuarios'] = 'O nível de acesso selecionado é inválido.';
        } elseif (painelTamanhoTexto($nomeUsuario) > 30 || painelTamanhoTexto($emailUsuario) > 150 || painelTamanhoTexto($justificativa) > 1000) {
            $_SESSION['feedback_usuarios'] = 'O nome ou e-mail excede o limite permitido.';
        } elseif (!$ativoUsuario && $justificativa === '') {
            $_SESSION['feedback_usuarios'] = 'Informe uma justificativa para desativar a conta.';
        } else {
            $consultaUsuario = $conn->prepare('SELECT tipo, ativo FROM usuarios WHERE id_usuario=?');
            $consultaUsuario->bind_param('i', $idUsuario);
            $consultaUsuario->execute();
            $usuarioAtual = $consultaUsuario->get_result()->fetch_assoc();
            $editarPropriaConta = $idUsuario === (int) $_SESSION['usuario_id'];

            if (!$usuarioAtual) {
                $_SESSION['feedback_usuarios'] = 'Essa conta não foi encontrada.';
            } elseif ($editarPropriaConta && ($tipoUsuario !== 'admin' || !$ativoUsuario)) {
                $_SESSION['feedback_usuarios'] = 'Não é possível remover seu próprio acesso administrativo.';
            } else {
                $removendoAdminAtivo = $usuarioAtual['tipo'] === 'admin'
                    && (int) $usuarioAtual['ativo'] === 1
                    && ($tipoUsuario !== 'admin' || !$ativoUsuario);
                $ultimoAdminAtivo = false;

                if ($removendoAdminAtivo) {
                    $contagemAdmins = $conn->query("SELECT COUNT(*) AS total FROM usuarios WHERE tipo='admin' AND ativo=1");
                    $ultimoAdminAtivo = (int) $contagemAdmins->fetch_assoc()['total'] <= 1;
                }

                if ($ultimoAdminAtivo) {
                    $_SESSION['feedback_usuarios'] = 'O último administrador ativo não pode ser removido.';
                } else {
                    try {
                        $justificativaSalva = $ativoUsuario ? null : $justificativa;
                        $atualizacao = $conn->prepare('UPDATE usuarios SET nome=?, email=?, tipo=?, ativo=?, justificativa_desativacao=? WHERE id_usuario=?');
                        $atualizacao->bind_param('sssisi', $nomeUsuario, $emailUsuario, $tipoUsuario, $ativoUsuario, $justificativaSalva, $idUsuario);
                        $atualizacao->execute();
                        $_SESSION['feedback_usuarios'] = 'Conta atualizada com sucesso.';
                    } catch (mysqli_sql_exception $erro) {
                        $_SESSION['feedback_usuarios'] = $erro->getCode() === 1062
                            ? 'Esse e-mail já está sendo usado por outra conta.'
                            : 'Não foi possível atualizar a conta.';
                        error_log('Falha ao editar conta no painel: ' . $erro->getMessage());
                    }
                }
            }
        }
    }

    header('Location: ./painel.php?secao=usuarios');
    exit;
}

$usuariosPainel = [];
$resultadoUsuarios = $conn->query('SELECT id_usuario, nome, email, tipo, ativo, justificativa_desativacao FROM usuarios ORDER BY id_usuario');
$usuariosPainel = $resultadoUsuarios->fetch_all(MYSQLI_ASSOC);

$consultaAtivos = $conn->query('SELECT COUNT(*) AS total FROM usuarios WHERE ativo=1');
$totalUsuariosAtivos = (int) $consultaAtivos->fetch_assoc()['total'];

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
    <title>Abyss | Painel Administrativo</title>
    <link rel="icon" type="image/png" href="./imagens/logo.png">
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
                <div class="resumo"><h2>Receita</h2><p>R$ 0,00</p></div>
                <div class="resumo"><h2>Pedidos concluídos</h2><p>0</p></div>
                <div class="resumo"><h2>Produtos ativos</h2><p>0</p></div>
                <div class="resumo"><h2>Produtos acabando</h2><p>0</p></div>
                <div class="resumo"><h2>Usuários ativos</h2><p><?= $totalUsuariosAtivos ?></p></div>
            </div>

            <section class="grafico" aria-label="Receita diária nos últimos sete dias">
                <h2>Receita diária - Últimos 7 dias</h2>
                <div class="barras"><p class="estado-vazio">Nenhuma venda registrada.</p></div>
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
                    <tbody><tr><td class="celula-tabela" colspan="4">Nenhum pedido registrado.</td></tr></tbody>
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

                        <div class="campo campo-categoria">
                            <div class="cabecalho-categoria">
                                <label for="categoria">Categoria</label>
                                <button class="botao-nova-categoria" type="button" aria-expanded="false" aria-controls="nova-categoria">+ Criar categoria</button>
                            </div>
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
                            <div class="nova-categoria" id="nova-categoria" hidden>
                                <input type="text" id="nome-nova-categoria" placeholder="Nome da categoria" aria-label="Nome da nova categoria" maxlength="40">
                                <button class="salvar-categoria" type="button">Adicionar</button>
                                <button class="cancelar-categoria" type="button">Cancelar</button>
                            </div>
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
                        <tbody><tr><td class="celula-tabela" colspan="6">Nenhum produto cadastrado.</td></tr></tbody>
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
                                <th class="coluna-tabela">Justificativa</th>
                                <th class="coluna-tabela sem-borda">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuariosPainel as $usuarioPainel): ?>
                                <?php
                                $linhaAtiva = (int) $usuarioPainel['ativo'] === 1;
                                $linhaTipo = $usuarioPainel['tipo'];
                                ?>
                                <tr class="linha-usuario" data-nome="<?= htmlspecialchars($usuarioPainel['nome'], ENT_QUOTES, 'UTF-8') ?>" data-email="<?= htmlspecialchars($usuarioPainel['email'], ENT_QUOTES, 'UTF-8') ?>" data-acesso="<?= htmlspecialchars($linhaTipo, ENT_QUOTES, 'UTF-8') ?>">
                                    <td class="celula-tabela"><?= htmlspecialchars($usuarioPainel['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="celula-tabela"><?= htmlspecialchars($usuarioPainel['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="celula-tabela"><?= $linhaTipo === 'admin' ? 'Administrador' : 'Usuário' ?></td>
                                    <td class="celula-tabela"><span class="status <?= $linhaAtiva ? '' : 'status-inativo' ?>"><?= $linhaAtiva ? 'Ativa' : 'Desativada' ?></span></td>
                                    <td class="celula-tabela justificativa-usuario" title="<?= htmlspecialchars($usuarioPainel['justificativa_desativacao'] ?? '', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($usuarioPainel['justificativa_desativacao'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="celula-tabela sem-borda">
                                        <button type="button" class="editar-usuario" data-id="<?= (int) $usuarioPainel['id_usuario'] ?>" data-nome="<?= htmlspecialchars($usuarioPainel['nome'], ENT_QUOTES, 'UTF-8') ?>" data-email="<?= htmlspecialchars($usuarioPainel['email'], ENT_QUOTES, 'UTF-8') ?>" data-tipo="<?= htmlspecialchars($linhaTipo, ENT_QUOTES, 'UTF-8') ?>" data-ativo="<?= $linhaAtiva ? '1' : '0' ?>" data-justificativa="<?= htmlspecialchars($usuarioPainel['justificativa_desativacao'] ?? '', ENT_QUOTES, 'UTF-8') ?>">Editar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (!$usuariosPainel): ?>
                                <tr><td class="celula-tabela" colspan="6">Nenhuma conta cadastrada.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($feedbackUsuarios !== ''): ?>
                    <p class="feedback-usuarios" role="status"><?= htmlspecialchars($feedbackUsuarios, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
                <dialog class="modal-usuario" id="modal-editar-usuario" aria-labelledby="titulo-editar-usuario">
                    <form method="post" action="./painel.php?secao=usuarios" class="form-editar-usuario">
                        <input type="hidden" name="acao" value="editar_usuario">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_painel'], ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="id_usuario" id="editar-id-usuario">
                        <h2 id="titulo-editar-usuario">Editar conta</h2>
                        <label for="editar-nome-usuario">Nome</label>
                        <input type="text" name="nome" id="editar-nome-usuario" maxlength="30" required>
                        <label for="editar-email-usuario">E-mail</label>
                        <input type="email" name="email" id="editar-email-usuario" maxlength="150" required>
                        <label for="editar-tipo-usuario">Acesso</label>
                        <select name="tipo" id="editar-tipo-usuario" required>
                            <option value="usuario">Usuário</option>
                            <option value="admin">Administrador</option>
                        </select>
                        <label class="campo-ativo"><input type="checkbox" name="ativo" id="editar-ativo-usuario" value="1"> Conta ativa</label>
                        <label for="editar-justificativa">Justificativa para desativar</label>
                        <textarea name="justificativa" id="editar-justificativa" rows="3" maxlength="1000"></textarea>
                        <div class="acoes-modal">
                            <button type="button" class="cancelar-edicao">Cancelar</button>
                            <button type="submit">Salvar alterações</button>
                        </div>
                    </form>
                </dialog>
            </section>

        </section>

        <!-- VENDAS -->
        <section class="vendas" id="vendas" <?= $selecao !== 'vendas' ? 'hidden' : '' ?>>

            <div class="titulo">
                <h1>Vendas</h1>
                <p>Receita e pedidos registrados.</p>
            </div>

            <div class="resumos">
                <div class="resumo"><h2>Receita registrada</h2><p>R$ 0,00</p></div>
                <div class="resumo"><h2>Pedidos concluídos</h2><p>0</p></div>
                <div class="resumo"><h2>Ticket médio</h2><p>R$ 0,00</p></div>
            </div>

            <section class="grafico-vendas" aria-label="Receita mensal nos últimos 12 meses">
                <h2>Receita mensal - Últimos 12 meses</h2>

                <div class="barras-vendas"><p class="estado-vazio">Nenhuma venda registrada.</p></div>
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
                        <tbody><tr><td class="celula-tabela" colspan="5">Nenhum pedido registrado.</td></tr></tbody>
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

        const seletorCategoria = document.querySelector('#categoria');
        const painelNovaCategoria = document.querySelector('#nova-categoria');
        const entradaNovaCategoria = document.querySelector('#nome-nova-categoria');
        const botaoAbrirCategoria = document.querySelector('.botao-nova-categoria');

        botaoAbrirCategoria.addEventListener('click', () => {
            const aberto = !painelNovaCategoria.hidden;
            painelNovaCategoria.hidden = aberto;
            botaoAbrirCategoria.setAttribute('aria-expanded', String(!aberto));
            if (!aberto) entradaNovaCategoria.focus();
        });

        document.querySelector('.cancelar-categoria').addEventListener('click', () => {
            painelNovaCategoria.hidden = true;
            entradaNovaCategoria.value = '';
            botaoAbrirCategoria.setAttribute('aria-expanded', 'false');
        });

        document.querySelector('.salvar-categoria').addEventListener('click', () => {
            const nomeCategoria = entradaNovaCategoria.value.trim();
            if (!nomeCategoria) {
                entradaNovaCategoria.focus();
                return;
            }

            const categoriaExistente = [...seletorCategoria.options].find((opcao) =>
                opcao.value.toLocaleLowerCase('pt-BR') === nomeCategoria.toLocaleLowerCase('pt-BR')
            );

            if (categoriaExistente) {
                seletorCategoria.value = categoriaExistente.value;
            } else {
                const novaOpcao = new Option(nomeCategoria, nomeCategoria, true, true);
                seletorCategoria.add(novaOpcao);
            }

            painelNovaCategoria.hidden = true;
            entradaNovaCategoria.value = '';
            botaoAbrirCategoria.setAttribute('aria-expanded', 'false');
        });


        const modalUsuario = document.querySelector('#modal-editar-usuario');
        const campoAtivo = document.querySelector('#editar-ativo-usuario');
        const campoJustificativa = document.querySelector('#editar-justificativa');
        const campoTipo = document.querySelector('#editar-tipo-usuario');
        const campoId = document.querySelector('#editar-id-usuario');
        const campoNome = document.querySelector('#editar-nome-usuario');
        const campoEmail = document.querySelector('#editar-email-usuario');

        document.querySelectorAll('.editar-usuario').forEach((botao) => {
            botao.addEventListener('click', () => {
                campoId.value = botao.dataset.id;
                campoNome.value = botao.dataset.nome;
                campoEmail.value = botao.dataset.email;
                campoTipo.value = botao.dataset.tipo;
                campoAtivo.checked = botao.dataset.ativo === '1';
                campoJustificativa.value = botao.dataset.justificativa;
                campoJustificativa.required = !campoAtivo.checked;
                modalUsuario.showModal();
            });
        });

        campoAtivo.addEventListener('change', () => {
            campoJustificativa.required = !campoAtivo.checked;
        });
        document.querySelector('.cancelar-edicao').addEventListener('click', () => modalUsuario.close());

        const campoBuscaUsuario = document.querySelector('#busca-usuario');
        const filtroUsuario = document.querySelector('#filtro-usuario');
        const botaoBuscaUsuario = document.querySelector('.campo-busca button');
        function filtrarUsuarios() {
            const termo = campoBuscaUsuario.value.trim().toLocaleLowerCase('pt-BR');
            const chave = filtroUsuario.value;
            document.querySelectorAll('.linha-usuario').forEach((linha) => {
                const valor = chave === 'acesso' ? linha.cells[2].textContent : linha.dataset[chave];
                linha.hidden = termo !== '' && !valor.toLocaleLowerCase('pt-BR').includes(termo);
            });
        }
        campoBuscaUsuario.addEventListener('input', filtrarUsuarios);
        filtroUsuario.addEventListener('change', filtrarUsuarios);
        botaoBuscaUsuario.addEventListener('click', filtrarUsuarios);
    </script>

</body>

</html>
