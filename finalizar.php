<?php
require './data/auth.php';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#191818">
    <title>Finalizar pedido | Abyss</title>
    <link rel="icon" type="image/png" href="./imagens/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/finalizar.css">
</head>
<body>
    <main class="pagina-finalizar">
        <div class="cabecalho-finalizar">
            <div>
                <p class="caminho">Carrinho <span aria-hidden="true">›</span> Finalizar pedido</p>
                <h1>Finalizar pedido</h1>
                <p class="subtitulo">Confira seu jogo e escolha como deseja pagar.</p>
            </div>
            <a class="voltar-carrinho" href="./Carrinho-nv.php">Voltar ao carrinho</a>
        </div>

        <div class="checkout">
            <section class="itens-pedido" aria-labelledby="titulo-itens">
                <div class="titulo-secao">
                    <h2 id="titulo-itens">Itens</h2>
                    <span>1 produto</span>
                </div>

                <article class="item-checkout">
                    <img src="./imagens/God Of War.png" alt="Capa de God of War Ragnarök">
                    <div class="dados-item">
                        <p class="rotulo-item">Jogo para PlayStation</p>
                        <h3>God of War Ragnarök</h3>
                        <span class="tipo-midia"><span aria-hidden="true"></span>Mídia digital</span>
                        <p class="preco-item">R$ 139,90</p>
                    </div>
                    <span class="quantidade-item">Qtd. 1</span>
                </article>

                <section class="pagamento" aria-labelledby="titulo-pagamento">
                    <div class="titulo-secao">
                        <h2 id="titulo-pagamento">Forma de pagamento</h2>
                        <span>Única opção</span>
                    </div>
                    <label class="opcao-pix">
                        <input type="radio" name="pagamento" value="pix" checked>
                        <span class="icone-pix"><img src="./imagens/pix.png" alt="Logo PIX"></span>
                        <span class="texto-pix"><strong>PIX</strong><small>Aprovação rápida e pagamento seguro</small></span>
                        <span class="pix-selecionado" aria-hidden="true"><span></span></span>
                    </label>
                    <p class="aviso-pix">O código PIX será apresentado após a confirmação do pedido.</p>
                </section>
            </section>

            <aside class="resumo-pedido" aria-labelledby="titulo-resumo">
                <h2 id="titulo-resumo">Resumo do pedido</h2>
                <article class="resumo-item">
                    <img src="./imagens/God Of War.png" alt="">
                    <div>
                        <h3>God of War Ragnarök</h3>
                        <p>Mídia digital · 1 unidade</p>
                    </div>
                    <strong>R$ 139,90</strong>
                </article>

                <dl class="valores-pedido">
                    <div><dt>Subtotal</dt><dd>R$ 139,90</dd></div>
                    <div><dt>Frete</dt><dd class="frete-valor">R$ 0,00</dd></div>
                    <div><dt>Desconto</dt><dd>R$ 0,00</dd></div>
                </dl>

                <div class="total-pedido"><span>Total a pagar</span><strong>R$ 139,90</strong></div>
                <a class="confirmar-pedido" href="./pagamento.php">Ir para pagamento <span aria-hidden="true">›</span></a>
                <p class="nota-seguranca"><span aria-hidden="true">◇</span> Seus dados são protegidos durante toda a compra.</p>
            </aside>
        </div>
    </main>

</body>
</html>
