<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho | Abyss</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/carrinho-nv.css">
</head>
<body>
    <?php require './view/header.php'; ?>

    <main class="pagina-carrinho-nv">
        <section class="painel-carrinho" aria-label="Seu carrinho">
            <div class="lista-produtos">
                <article class="produto" data-preco="139.90">
                    <img class="produto-imagem" src="./imagens/God Of War.png" alt="Capa de God of War Ragnarök">
                    <div class="produto-detalhes">
                        <h1>God of War Ragnarök</h1>
                        <p class="produto-tipo">Mídia-Física</p>
                        <p class="desconto">-30%</p>
                        <p class="produto-preco">R$ 139,90</p>
                    </div>
                    <div class="controle-quantidade" aria-label="Quantidade de God of War Ragnarök">
                        <button type="button" class="quantidade-ajuste" data-delta="-1" aria-label="Diminuir quantidade">−</button>
                        <output class="quantidade">1</output>
                        <button type="button" class="quantidade-ajuste" data-delta="1" aria-label="Aumentar quantidade">+</button>
                    </div>
                </article>

                <article class="produto" data-preco="119.90">
                    <img class="produto-imagem" src="./imagens/Cyberpunk.png" alt="Capa de Cyberpunk 2077">
                    <div class="produto-detalhes">
                        <h2>Cyberpunk 2077</h2>
                        <p class="produto-tipo">Mídia-Física</p>
                        <p class="desconto">-20%</p>
                        <p class="produto-preco">R$ 119,90</p>
                    </div>
                    <div class="controle-quantidade" aria-label="Quantidade de Cyberpunk 2077">
                        <button type="button" class="quantidade-ajuste" data-delta="-1" aria-label="Diminuir quantidade">−</button>
                        <output class="quantidade">1</output>
                        <button type="button" class="quantidade-ajuste" data-delta="1" aria-label="Aumentar quantidade">+</button>
                    </div>
                </article>
            </div>

            <form class="form-cupom" id="form-cupom">
                <label for="cupom">Aplicar Cupom</label>
                <input id="cupom" name="cupom" type="text" placeholder="Digite seu cupom..." autocomplete="off">
                <p class="mensagem-cupom" id="mensagem-cupom" aria-live="polite"></p>
            </form>

            <dl class="resumo-carrinho">
                <div><dt>Subtotal</dt><dd id="subtotal">R$ 239,80</dd></div>
                <div><dt>Frete</dt><dd id="frete">R$ 15,00</dd></div>
                <div class="linha-total"><dt>Total</dt><dd id="total">R$ 254,80</dd></div>
            </dl>

            <button class="finalizar-pedido" type="button">Finalizar Pedido</button>
        </section>
    </main>

    <script>
        (() => {
            const produtos = [...document.querySelectorAll('.produto')];
            const subtotalEl = document.getElementById('subtotal');
            const totalEl = document.getElementById('total');
            const cupomInput = document.getElementById('cupom');
            const mensagemCupom = document.getElementById('mensagem-cupom');
            let descontoCupom = 0;
            const dinheiro = valor => valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

            function atualizarResumo() {
                const subtotal = produtos.reduce((soma, produto) => {
                    const preco = Number(produto.dataset.preco);
                    const quantidade = Number(produto.querySelector('.quantidade').value);
                    return soma + preco * quantidade;
                }, 0);
                const frete = subtotal > 0 ? 15 : 0;
                subtotalEl.textContent = dinheiro(subtotal);
                totalEl.textContent = dinheiro(Math.max(0, subtotal * (1 - descontoCupom) + frete));
                document.getElementById('frete').textContent = dinheiro(frete);
            }

            document.querySelectorAll('.quantidade-ajuste').forEach(botao => {
                botao.addEventListener('click', () => {
                    const quantidade = botao.closest('.produto').querySelector('.quantidade');
                    quantidade.value = Math.max(1, Number(quantidade.value) + Number(botao.dataset.delta));
                    atualizarResumo();
                });
            });

            document.getElementById('form-cupom').addEventListener('submit', evento => {
                evento.preventDefault();
                if (cupomInput.value.trim().toUpperCase() === 'ABYSS10') {
                    descontoCupom = 0.10;
                    mensagemCupom.textContent = 'Cupom ABYSS10 aplicado: 10% de desconto.';
                    mensagemCupom.classList.add('cupom-valido');
                } else {
                    descontoCupom = 0;
                    mensagemCupom.textContent = 'Cupom inválido.';
                    mensagemCupom.classList.remove('cupom-valido');
                }
                atualizarResumo();
            });
        })();
    </script>
</body>
</html>
