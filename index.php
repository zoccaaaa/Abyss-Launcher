
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abyss | Início</title>
    <link rel="icon" type="image/png" href="./imagens/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/footer.css">
</head>
<body>
    <?php
    require './view/header.php'; 
    ?>


   <section class="banner" aria-label="Destaque da semana: Phantom Blade Zero" data-video-banner>
    <video class="banner-video" muted loop playsinline preload="none" poster="./imagens/Banner.png" aria-hidden="true">
        <source src="./imagens/Video banner.mp4" type="video/mp4">
    </video>
    <div class="banner-conteudo">
        <img class="banner-logo" src="./imagens/Logotipo Phantom Blade Zero.png" alt="Phantom Blade Zero">
        <p class="banner-status">DESTAQUE DA SEMANA</p>
        <div class="acoes">
            <a class="comprar" href="#">Ver jogo</a>
            <button type="button" aria-label="Favoritar"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 3.5h12v18l-6-4-6 4z"/></svg></button>
        </div>
    </div>
   </section>

     <div class="destaques">
        <div class="titulo">
            <h1>Jogos em destaque</h1>
            <div class="linha"></div>
        </div>

        <div class="jogos">
            <div class="iten">
                <img src="./imagens/Fifa.png" alt="EA Sports FC 27">
                <div class="informacoes">
                    <h2>EA Sports FC 27</h2>
                    <p>Esportes</p>
                    <div class="final">
                        <h3>R$ 349,99</h3>
                        <a href="#">Ver jogo <span>›</span></a>
                    </div>
                </div>
            </div>

            
        </div>
     </div>

   <section class="vitrine" aria-label="Jogos em destaque">
        <article class="jogo-principal" id="jogo-principal">
            <div class="jogo-conteudo">
                <img class="logo-jogo" src="./imagens/remothered nome.png" alt="Remothered: Red Nun’s Legacy" data-logo>
                <p class="disponibilidade" data-status>JÁ DISPONÍVEL</p>
                <p class="descricao" data-descricao>Uma mãe. Uma filha desaparecida. Um mistério esperando pra ser desvendado</p>
                <p class="preco" data-preco>R$ 80,00</p>
                <div class="acoes">
                    <a class="comprar" href="#">Compre agora</a>
                    <button type="button" aria-label="Favoritar"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 3.5h12v18l-6-4-6 4z"/></svg></button>
                </div>
            </div>
        </article>
        <nav class="lista-jogos" aria-label="Escolher jogo em destaque">
            <button class="jogo-opcao" type="button" data-jogo="vampyr" aria-pressed="false"><img src="./imagens/Vampir.png" alt=""><span>Vampir</span><i></i></button>
            <button class="jogo-opcao selecionado" type="button" data-jogo="remothered" aria-pressed="true"><img src="./imagens/remothered.png" alt=""><span>Remothered:<br> Red Nun’s...</span><i></i></button>
            <button class="jogo-opcao" type="button" data-jogo="lego" aria-pressed="false"><img src="./imagens/LEGO Batman.png" alt=""><span>Lego Batman: O ..</span><i></i></button>
            <button class="jogo-opcao" type="button" data-jogo="corsair" aria-pressed="false"><img src="./imagens/Corsair.png" alt=""><span>Corsair cove</span><i></i></button>
        </nav>
   </section>

   <section class="descubra" aria-label="Descubra algo novo">
        <div class="descubra-titulo">
            <h1>Descobrir algo novo</h1>
            <div class="linha"></div>
        </div>
        <div class="descubra-conteudo">
            <article class="descubra-banner">
                <img src="./imagens/descubra.png" alt="Descobrir algo novo">
                <h2>Descobrir<br>algo novo</h2>
                <a href="#">Ver mais</a>
            </article>
            <div class="descubra-jogos">
                <div class="descubra-jogo"><a class="descubra-jogo-link" href="#"><img src="./imagens/Sorry We Are Closed.png" alt=""><span><strong>Sorry were closed</strong><small>R$ 21,99</small></span></a><button class="favoritar" type="button" aria-label="Favoritar" aria-pressed="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 4.6a5.4 5.4 0 0 0-7.6 0L12 5.8l-1.2-1.2a5.4 5.4 0 0 0-7.6 7.6l1.2 1.2L12 21l7.6-7.6 1.2-1.2a5.4 5.4 0 0 0 0-7.6Z"/></svg></button></div>
                <div class="descubra-jogo"><a class="descubra-jogo-link" href="#"><img src="./imagens/Metro 2030.png" alt=""><span><strong>Metro 2039</strong><small>R$ 359,99</small></span></a><button class="favoritar" type="button" aria-label="Favoritar" aria-pressed="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 4.6a5.4 5.4 0 0 0-7.6 0L12 5.8l-1.2-1.2a5.4 5.4 0 0 0-7.6 7.6l1.2 1.2L12 21l7.6-7.6 1.2-1.2a5.4 5.4 0 0 0 0-7.6Z"/></svg></button></div>
                <div class="descubra-jogo"><a class="descubra-jogo-link" href="#"><img src="./imagens/Permafrost.png" alt=""><span><strong>Permafrost</strong><small>R$ 65,99</small></span></a><button class="favoritar" type="button" aria-label="Favoritar" aria-pressed="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 4.6a5.4 5.4 0 0 0-7.6 0L12 5.8l-1.2-1.2a5.4 5.4 0 0 0-7.6 7.6l1.2 1.2L12 21l7.6-7.6 1.2-1.2a5.4 5.4 0 0 0 0-7.6Z"/></svg></button></div>
                <div class="descubra-jogo"><a class="descubra-jogo-link" href="#"><img src="./imagens/Beautiful ligth.png" alt=""><span><strong>Beautiful Light</strong><small>R$ 229,99</small></span></a><button class="favoritar" type="button" aria-label="Favoritar" aria-pressed="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 4.6a5.4 5.4 0 0 0-7.6 0L12 5.8l-1.2-1.2a5.4 5.4 0 0 0-7.6 7.6l1.2 1.2L12 21l7.6-7.6 1.2-1.2a5.4 5.4 0 0 0 0-7.6Z"/></svg></button></div>
                <div class="descubra-jogo"><a class="descubra-jogo-link" href="#"><img src="./imagens/Forever ago.png" alt=""><span><strong>Forever Ago</strong><small>R$ 70,00</small></span></a><button class="favoritar" type="button" aria-label="Favoritar" aria-pressed="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 4.6a5.4 5.4 0 0 0-7.6 0L12 5.8l-1.2-1.2a5.4 5.4 0 0 0-7.6 7.6l1.2 1.2L12 21l7.6-7.6 1.2-1.2a5.4 5.4 0 0 0 0-7.6Z"/></svg></button></div>
                <div class="descubra-jogo"><a class="descubra-jogo-link" href="#"><img src="./imagens/Out of word.png" alt=""><span><strong>Out of words</strong><small>R$ 199,99</small></span></a><button class="favoritar" type="button" aria-label="Favoritar" aria-pressed="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 4.6a5.4 5.4 0 0 0-7.6 0L12 5.8l-1.2-1.2a5.4 5.4 0 0 0-7.6 7.6l1.2 1.2L12 21l7.6-7.6 1.2-1.2a5.4 5.4 0 0 0 0-7.6Z"/></svg></button></div>
            </div>
        </div>
   </section>

   <section class="descontos" aria-label="Descontos em destaque">
        <div class="descontos-titulo">
            <h1>Descontos em destaque</h1>
            <div class="linha"></div>
        </div>
        <div class="descontos-jogos">
            <article class="desconto-jogo">
                <img src="./imagens/Tomb raider.png" alt="Rise of the Tomb Raider: 20 Year Celebration">
                <p class="categoria">Jogo base</p>
                <h2>Rise of the Tomb Raider:<br>20 year celebration</h2>
                <div class="precos-desconto"><span class="porcentagem">-90%</span><del>R$ 95,00</del><strong>R$ 9,50</strong></div>
            </article>
            <article class="desconto-jogo">
                <img src="./imagens/Tomb Raider GOTY.png" alt="Tomb Raider GOTY">
                <p class="categoria">Jogo base</p>
                <h2>Tomb Raider GOTY</h2>
                <div class="precos-desconto"><span class="porcentagem">-90%</span><del>R$ 39,00</del><strong>R$ 3,90</strong></div>
            </article>
            <article class="desconto-jogo">
                <img src="./imagens/Metro Exodus.png" alt="Metro Exodus">
                <p class="categoria">Jogo base</p>
                <h2>Metro Exodus</h2>
                <div class="precos-desconto"><span class="porcentagem">-90%</span><del>R$ 149,00</del><strong>R$ 14,90</strong></div>
            </article>
        </div>
   </section>

   <section class="lancamentos" aria-label="Novos lançamentos">
        <div class="lancamentos-titulo">
            <h1>Novos lançamentos</h1>
            <div class="linha"></div>
        </div>
        <div class="lancamentos-conteudo">
            <article class="lancamentos-banner">
                <img src="./imagens/Verde lancamentos.png" alt="">
                <h2>Novos<br>lançamentos</h2>
                <a href="#">Ver mais</a>
            </article>
            <div class="lancamentos-jogos">
                <div class="lancamento-jogo"><a class="lancamento-jogo-link" href="#"><img src="./imagens/Lost twin.png" alt=""><span><strong>Lost twins II</strong><small>Em breve!</small></span></a><button class="favoritar" type="button" aria-label="Favoritar" aria-pressed="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 4.6a5.4 5.4 0 0 0-7.6 0L12 5.8l-1.2-1.2a5.4 5.4 0 0 0-7.6 7.6l1.2 1.2L12 21l7.6-7.6 1.2-1.2a5.4 5.4 0 0 0 0-7.6Z"/></svg></button></div>
                <div class="lancamento-jogo"><a class="lancamento-jogo-link" href="#"><img src="./imagens/Tatto shop simulator.png" alt=""><span><strong>Tattoo Shop Simulator</strong><small>R$ 50,99</small></span></a><button class="favoritar" type="button" aria-label="Favoritar" aria-pressed="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 4.6a5.4 5.4 0 0 0-7.6 0L12 5.8l-1.2-1.2a5.4 5.4 0 0 0-7.6 7.6l1.2 1.2L12 21l7.6-7.6 1.2-1.2a5.4 5.4 0 0 0 0-7.6Z"/></svg></button></div>
                <div class="lancamento-jogo"><a class="lancamento-jogo-link" href="#"><img src="./imagens/My dear.exe.png" alt=""><span><strong>My dear.exe</strong><small>R$ 20,99</small></span></a><button class="favoritar" type="button" aria-label="Favoritar" aria-pressed="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 4.6a5.4 5.4 0 0 0-7.6 0L12 5.8l-1.2-1.2a5.4 5.4 0 0 0-7.6 7.6l1.2 1.2L12 21l7.6-7.6 1.2-1.2a5.4 5.4 0 0 0 0-7.6Z"/></svg></button></div>
                <div class="lancamento-jogo"><a class="lancamento-jogo-link" href="#"><img src="./imagens/Deadly Match.png" alt=""><span><strong>Deadly match</strong><small>R$ 15,99</small></span></a><button class="favoritar" type="button" aria-label="Favoritar" aria-pressed="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 4.6a5.4 5.4 0 0 0-7.6 0L12 5.8l-1.2-1.2a5.4 5.4 0 0 0-7.6 7.6l1.2 1.2L12 21l7.6-7.6 1.2-1.2a5.4 5.4 0 0 0 0-7.6Z"/></svg></button></div>
                <div class="lancamento-jogo"><a class="lancamento-jogo-link" href="#"><img src="./imagens/Loftia.png" alt=""><span><strong>Loftia</strong><small>R$ 70,99</small></span></a><button class="favoritar" type="button" aria-label="Favoritar" aria-pressed="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 4.6a5.4 5.4 0 0 0-7.6 0L12 5.8l-1.2-1.2a5.4 5.4 0 0 0-7.6 7.6l1.2 1.2L12 21l7.6-7.6 1.2-1.2a5.4 5.4 0 0 0 0-7.6Z"/></svg></button></div>
                <div class="lancamento-jogo"><a class="lancamento-jogo-link" href="#"><img src="./imagens/RObot mil.png" alt=""><span><strong>Robot mil</strong><small>R$ 12,99</small></span></a><button class="favoritar" type="button" aria-label="Favoritar" aria-pressed="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 4.6a5.4 5.4 0 0 0-7.6 0L12 5.8l-1.2-1.2a5.4 5.4 0 0 0-7.6 7.6l1.2 1.2L12 21l7.6-7.6 1.2-1.2a5.4 5.4 0 0 0 0-7.6Z"/></svg></button></div>
            </div>
        </div>
   </section>

    <script>
    (() => {
        const jogos = {
            vampyr: { banner: './imagens/vampyr banner.jpg', logo: './imagens/vampyr logo.png', alt: 'Vampyr', descricao: 'Em uma Londres tomada pela doença, escolha quem vive e quem morre.', preco: 'R$ 59,99' },
            remothered: { banner: './imagens/Remothered Banner.png', logo: './imagens/remothered nome.png', alt: 'Remothered: Red Nun’s Legacy', descricao: 'Uma mãe. Uma filha desaparecida. Um mistério esperando pra ser desvendado', preco: 'R$ 80,00' },
            lego: { banner: './imagens/LEGO batman banner.jpg', logo: './imagens/Logo lego batman.png', alt: 'LEGO Batman', descricao: 'Vista a capa, proteja Gotham e viva uma aventura cheia de ação.', preco: 'R$ 99,99' },
            corsair: { banner: './imagens/Corsair banner.jpg', logo: './imagens/Corsair Cove logo.png', alt: 'Corsair Cove', descricao: 'Explore novos mares e descubra os segredos de Corsair Cove.', preco: 'R$ 49,99' }
        };
        const principal = document.querySelector('#jogo-principal');
        const botoes = [...document.querySelectorAll('.jogo-opcao')];
        let atual = 1;
        let timer;
        function selecionar(indice, automatico = false) {
            atual = indice;
            const botao = botoes[indice];
            const jogo = jogos[botao.dataset.jogo];
            principal.style.backgroundImage = `linear-gradient(90deg, rgba(0,0,0,.86), rgba(0,0,0,.28) 58%, rgba(0,0,0,.05)), url("${jogo.banner}")`;
            principal.querySelector('[data-logo]').src = jogo.logo;
            principal.querySelector('[data-logo]').alt = jogo.alt;
            principal.querySelector('[data-descricao]').textContent = jogo.descricao;
            principal.querySelector('[data-preco]').textContent = jogo.preco;
            botoes.forEach((item, i) => {
                item.classList.toggle('selecionado', i === indice);
                item.setAttribute('aria-pressed', i === indice ? 'true' : 'false');
                item.style.setProperty('--progresso', '0%');
            });
            clearInterval(timer);
            let progresso = 0;
            timer = setInterval(() => {
                progresso += 2;
                botao.style.setProperty('--progresso', `${progresso}%`);
                if (progresso >= 100) selecionar((atual + 1) % botoes.length, true);
            }, 200);
        }
        botoes.forEach((botao, indice) => botao.addEventListener('click', () => selecionar(indice)));
        selecionar(atual, true);
        const banner = document.querySelector('[data-video-banner]');
        const bannerVideo = banner.querySelector('.banner-video');

        function iniciarVideoBanner() {
            banner.classList.add('video-ativo');
            const reproducao = bannerVideo.play();
            if (reproducao && typeof reproducao.catch === 'function') {
                reproducao.catch(() => banner.classList.remove('video-ativo'));
            }
        }

        function pausarVideoBanner() {
            bannerVideo.pause();
            bannerVideo.currentTime = 0;
            banner.classList.remove('video-ativo');
        }

        banner.addEventListener('pointerenter', (evento) => {
            if (evento.pointerType === 'mouse') iniciarVideoBanner();
        });
        banner.addEventListener('pointerleave', pausarVideoBanner);
        banner.addEventListener('focusin', iniciarVideoBanner);
        banner.addEventListener('focusout', (evento) => {
            if (!banner.contains(evento.relatedTarget)) pausarVideoBanner();
        });
        document.querySelectorAll('.favoritar').forEach((botao) => {
            botao.addEventListener('click', () => {
                const favoritado = botao.getAttribute('aria-pressed') === 'true';
                botao.setAttribute('aria-pressed', favoritado ? 'false' : 'true');
                botao.setAttribute('aria-label', favoritado ? 'Favoritar' : 'Remover dos favoritos');
                if (favoritado) botao.blur();
            });
        });
    })();
    </script>

    <?php
    require './view/footer.php';
    ?>
</body>
</html>
