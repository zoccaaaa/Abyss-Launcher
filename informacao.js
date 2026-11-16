// Procura o indicador correspondente na página.
var elementoContadorDeJogos = document.getElementById("n1");
var elementoContadorDeJogadores = document.getElementById("n2");
var elementoContadorDePaises = document.getElementById("n3");
var elementoContadorDeTempoOnline = document.getElementById("n4");

// Lê o valor salvo ou usa o valor inicial quando não há um número válido.
function obterValorSalvo(chave, valorInicial) {
    var valorArmazenado = Number(sessionStorage.getItem(chave));

    if (!Number.isFinite(valorArmazenado) || valorArmazenado === 0) {
        return valorInicial;
    }

    return valorArmazenado;
}

// Incrementa um valor, mostra o resultado na página e guarda para a próxima visita.
function incrementarEExibir(chave, elemento, valorInicial, formatarValor) {
    var valorAtualizado = obterValorSalvo(chave, valorInicial) + 1;
    elemento.textContent = formatarValor(valorAtualizado);
    sessionStorage.setItem(chave, valorAtualizado);
}

// Mantém a exibição compacta para totais de jogos na casa dos milhões.
function formatarTotalDeJogos(totalDeJogos) {
    if (totalDeJogos >= 1000000) {
        return (totalDeJogos / 1000000).toFixed(1).replace(".", ",") + "M";
    }

    return totalDeJogos.toLocaleString("pt-BR");
}

// Mantém a exibição compacta para totais de jogadores na casa dos milhares.
function formatarTotalDeJogadores(totalDeJogadores) {
    if (totalDeJogadores >= 1000) {
        return (totalDeJogadores / 1000).toFixed(0) + "K+";
    }

    return totalDeJogadores.toLocaleString("pt-BR");
}

// Exibe países com separadores numéricos do português brasileiro.
function formatarTotalDePaises(totalDePaises) {
    return totalDePaises.toLocaleString("pt-BR");
}

// O tempo online é guardado em décimos: 999 representa 99,9%.
// Assim, cada incremento acrescenta 0,1 ponto percentual.
function formatarTempoOnline(tempoOnlineEmDecimos) {
    var tempoOnlineEmPercentual = tempoOnlineEmDecimos / 10;
    return tempoOnlineEmPercentual.toFixed(1).replace(".", ",") + "%";
}

// Atualiza e salva os indicadores com seus valores iniciais e formatos próprios.
incrementarEExibir("jogos", elementoContadorDeJogos, 2400000, formatarTotalDeJogos);
incrementarEExibir("jogadores", elementoContadorDeJogadores, 12000, formatarTotalDeJogadores);
incrementarEExibir("paises", elementoContadorDePaises, 180, formatarTotalDePaises);
incrementarEExibir("tempoOnline", elementoContadorDeTempoOnline, 999, formatarTempoOnline);

