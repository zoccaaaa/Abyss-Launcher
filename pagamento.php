<?php
require './data/auth.php';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#191818">
    <title>Pagamento PIX | Abyss</title>
    <link rel="icon" type="image/png" href="./imagens/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/pagamento.css">
</head>
<body>
    <main class="pagina-pagamento">
        <header class="cabecalho-pagamento">
            <a class="marca-abyss" href="./index.php" aria-label="Abyss, início">
                <img src="./imagens/logo.png" alt="">
                <span>Abyss</span>
            </a>
            <p>Pagamento seguro <span aria-hidden="true">·</span> PIX</p>
        </header>

        <section class="painel-pagamento" id="etapa-pix" aria-labelledby="titulo-pix">
            <div class="detalhes-pagamento">
                <p class="etapa">ETAPA 2 DE 2</p>
                <h1 id="titulo-pix">Pague com PIX</h1>
                <p class="instrucao">Escaneie o QR Code com o aplicativo do seu banco para concluir o pedido.</p>

                <div class="produto-digital">
                    <img src="./imagens/God Of War.png" alt="">
                    <div>
                        <span>Jogo digital</span>
                        <h2>God of War Ragnarök</h2>
                        <p>Entrega digital após a confirmação</p>
                    </div>
                    <strong>R$ 139,90</strong>
                </div>

                <div class="total-pix"><span>Total</span><strong>R$ 139,90</strong></div>
                <div class="passos-pix">
                    <p><span>1</span> Abra o app do seu banco</p>
                    <p><span>2</span> Escolha pagar com PIX e leia o QR Code</p>
                    <p><span>3</span> Confirme o pagamento no banco</p>
                </div>
            </div>

            <div class="cartao-qr">
                <span class="selo-demo">DEMONSTRAÇÃO</span>
                <canvas id="pix-qr" width="232" height="232" role="img" aria-label="QR Code PIX ilustrativo. A cobrança real será conectada ao backend posteriormente."></canvas>
                <div class="marca-pix"><img src="./imagens/pix.png" alt="PIX"></div>
                <p class="rotulo-qr">QR Code PIX demonstrativo</p>
                <p class="aviso-qr">O código de cobrança real será gerado quando a integração de pagamento estiver conectada.</p>
                <button class="botao-simular" id="simular-pagamento" type="button">Simular pagamento concluído</button>
                <a class="voltar-checkout" href="./finalizar.php">Voltar ao pedido</a>
            </div>
        </section>

        <section class="painel-carregando" id="etapa-carregando" aria-live="polite" aria-busy="true" hidden>
            <span class="indicador-carregamento" aria-hidden="true"></span>
            <h1>Confirmando pagamento</h1>
            <p>Aguarde enquanto preparamos sua key digital.</p>
        </section>

        <section class="painel-sucesso" id="etapa-sucesso" aria-labelledby="titulo-sucesso" aria-live="polite" hidden>
            <div class="icone-sucesso" aria-hidden="true">✓</div>
            <p class="etapa">PEDIDO CONCLUÍDO</p>
            <h1 id="titulo-sucesso">Pagamento efetuado!</h1>
            <p class="texto-sucesso">Sua key digital foi gerada. Guarde-a em um lugar seguro.</p>

            <div class="cartao-key">
                <span class="rotulo-key">SUA KEY DE ATIVAÇÃO</span>
                <strong id="key-gerada">ABYSS-XXXX-XXXX-XXXX</strong>
                <p>Essa é sua key, você pode acessá-la na sua Biblioteca.</p>
            </div>

            <p class="aviso-email">Em uma versão futura, também enviaremos a key para o e-mail cadastrado.</p>
            <a class="botao-inicio" href="./index.php">Voltar para a loja</a>
        </section>

        <p class="rodape-pagamento">Abyss Launcher <span>·</span> Seus dados são protegidos durante a compra.</p>
    </main>

    <script>
        (() => {
            const canvas = document.getElementById('pix-qr');
            const contexto = canvas.getContext('2d');
            const modulo = 8;
            const tamanho = 29;
            const matriz = Array.from({ length: tamanho }, () => Array(tamanho).fill(false));
            const reservado = Array.from({ length: tamanho }, () => Array(tamanho).fill(false));

            function marcador(inicioX, inicioY) {
                for (let y = -1; y <= 7; y++) {
                    for (let x = -1; x <= 7; x++) {
                        const px = inicioX + x;
                        const py = inicioY + y;
                        if (px < 0 || py < 0 || px >= tamanho || py >= tamanho) continue;
                        reservado[py][px] = true;
                        matriz[py][px] = x >= 0 && x <= 6 && y >= 0 && y <= 6 &&
                            (x === 0 || x === 6 || y === 0 || y === 6 || (x >= 2 && x <= 4 && y >= 2 && y <= 4));
                    }
                }
            }

            marcador(0, 0);
            marcador(tamanho - 7, 0);
            marcador(0, tamanho - 7);
            for (let y = 0; y < tamanho; y++) {
                for (let x = 0; x < tamanho; x++) {
                    if (!reservado[y][x]) matriz[y][x] = Math.random() > .52;
                }
            }

            contexto.fillStyle = '#fff';
            contexto.fillRect(0, 0, canvas.width, canvas.height);
            contexto.fillStyle = '#201b25';
            const margem = 0;
            for (let y = 0; y < tamanho; y++) {
                for (let x = 0; x < tamanho; x++) {
                    if (matriz[y][x]) contexto.fillRect(margem + x * modulo, margem + y * modulo, modulo, modulo);
                }
            }

            document.getElementById('simular-pagamento').addEventListener('click', () => {
                const alfabeto = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
                const bytes = new Uint8Array(12);
                crypto.getRandomValues(bytes);
                const key = Array.from(bytes, (byte) => alfabeto[byte % alfabeto.length]).join('');
                const etapaPix = document.getElementById('etapa-pix');
                const etapaCarregando = document.getElementById('etapa-carregando');
                const etapaSucesso = document.getElementById('etapa-sucesso');
                const botao = document.getElementById('simular-pagamento');
                botao.disabled = true;
                etapaPix.hidden = true;
                etapaCarregando.hidden = false;
                window.scrollTo({ top: 0, behavior: 'smooth' });

                window.setTimeout(() => {
                    document.getElementById('key-gerada').textContent = `ABYSS-${key.slice(0, 4)}-${key.slice(4, 8)}-${key.slice(8, 12)}`;
                    etapaCarregando.hidden = true;
                    etapaSucesso.hidden = false;
                }, 1600);
            });
        })();
    </script>
</body>
</html>
