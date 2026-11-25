<?php require "./data/conexao.php";
$mensagem_erro = "";
$valoresCadastro = [
    'nome' => '',
    'email' => '',
    'cep' => '',
    'estado' => '',
    'cidade' => '',
    'rua' => '',
    'numero' => '',
];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($valoresCadastro as $campo => $valorAtual) {
        $valoresCadastro[$campo] = trim((string) ($_POST[$campo] ?? ''));
    }
    $nome = $valoresCadastro['nome'];
    $email = $valoresCadastro['email'];
    $senha = (string) ($_POST['senha'] ?? '');
    $cep = preg_replace('/\D/', '', $valoresCadastro['cep']);
    $estado = strtoupper($valoresCadastro['estado']);
    $cidade = $valoresCadastro['cidade'];
    $rua = $valoresCadastro['rua'];
    $numero = $valoresCadastro['numero'];
    $contarCaracteres = static function ($texto) {
        if (function_exists('mb_strlen')) return mb_strlen($texto, 'UTF-8');
        $quantidade = preg_match_all('/./us', $texto);
        return $quantidade === false ? strlen($texto) : $quantidade;
    };

    if ($nome === '' || $email === '' || $senha === '' || $cep === '' || $estado === '' || $cidade === '' || $rua === '' || $numero === '') {
        $mensagem_erro = 'Preencha todos os campos para continuar.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem_erro = 'Informe um e-mail válido.';
    } elseif (!preg_match('/^\d{8}$/', $cep)) {
        $mensagem_erro = 'Informe um CEP válido com 8 números.';
    } elseif (!preg_match('/^[A-Z]{2}$/', $estado) || $contarCaracteres($cidade) > 100 || $contarCaracteres($rua) > 150 || $contarCaracteres($numero) > 20) {
        $mensagem_erro = 'Confira os dados do endereço.';
    } else {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nome, email, senha, cep, estado, cidade, rua, numero, tipo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'usuario')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $nome, $email, $senhaHash, $cep, $estado, $cidade, $rua, $numero);
        try {
            $stmt->execute();
            header("Location: login.php");
            exit;
        } catch (mysqli_sql_exception $e) {
            $mensagem_erro = $e->getCode() === 1062
                ? "Email ja cadastrado"
                : 'Erro ao cadastrar'
            ;
        }

    }
}


?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="icon" type="image/png" href="./imagens/logo.png">
    <link rel="stylesheet" href="./css/cadastro.css">
</head>

<body>
    <div class="cadastro">
        <div class="esquerda">
            <img class="logo" src="./imagens/logo.png" alt="Logo Abyss">
            <div class="elipses"></div>
            <div class="escrita">
                <h3>SUA LOJA , DO SEU JEITO</h3>
                <h1>Bem-vindo.</h1>
                <p>Crie uma conta para acompanhar seus pedido e descobrir produtos para o seu dia a dia</p>
            </div>
        </div>
        <div class="direita">
            <div class="escritas">
                <h4>AREA DO CLIENTE</h4>
                <h1>Criar uma conta</h1>
                <p>Preencha seus dados e endereço para criar sua conta.</p>
            </div>
            <form method="POST">
                <label for="nome">Nome</label>
                <input id="nome" name="nome" type="text" maxlength="30" placeholder="Digite seu nome" value="<?= htmlspecialchars($valoresCadastro['nome'], ENT_QUOTES, 'UTF-8') ?>" required>
                <label for="email">E-mail</label>
                <input id="email" name="email" type="email" maxlength="150" placeholder="Digite seu e-mail" value="<?= htmlspecialchars($valoresCadastro['email'], ENT_QUOTES, 'UTF-8') ?>" required>
                <label for="cep">CEP</label>
                <input id="cep" name="cep" type="text" inputmode="numeric" autocomplete="postal-code" maxlength="9" placeholder="00000-000" value="<?= htmlspecialchars($valoresCadastro['cep'], ENT_QUOTES, 'UTF-8') ?>" aria-describedby="endereco-status" required>
                <div class="endereco-linha">
                    <div class="campo-endereco">
                        <label for="estado">Estado</label>
                        <select id="estado" name="estado" autocomplete="address-level1" data-selecionado="<?= htmlspecialchars($valoresCadastro['estado'], ENT_QUOTES, 'UTF-8') ?>" required>
                            <option value="">Escolha o estado</option>
                        </select>
                    </div>
                    <div class="campo-endereco">
                        <label for="cidade">Cidade</label>
                        <select id="cidade" name="cidade" autocomplete="address-level2" data-selecionada="<?= htmlspecialchars($valoresCadastro['cidade'], ENT_QUOTES, 'UTF-8') ?>" required disabled>
                            <option value="">Informe o CEP ou escolha o estado</option>
                        </select>
                    </div>
                </div>
                <label for="rua">Rua</label>
                <input id="rua" name="rua" type="text" maxlength="150" autocomplete="address-line1" placeholder="Nome da rua" value="<?= htmlspecialchars($valoresCadastro['rua'], ENT_QUOTES, 'UTF-8') ?>" required>
                <label for="numero">Número</label>
                <input id="numero" name="numero" type="text" maxlength="20" autocomplete="address-line2" placeholder="Ex.: 123 ou S/N" value="<?= htmlspecialchars($valoresCadastro['numero'], ENT_QUOTES, 'UTF-8') ?>" required>
                <p class="endereco-status" id="endereco-status" role="status" aria-live="polite"></p>
                <label for="password">Senha</label>
                <div class="campo-senha">
                    <input id="senha" name="senha" type="password" placeholder="Digite sua senha" required>
                    <button class="revelar-senha" type="button" data-revelar-senha aria-pressed="false">Mostrar</button>
                </div>
                <?php if ($mensagem_erro !== ''): ?>
                    <p class="mensagem-erro" role="alert"><?= htmlspecialchars($mensagem_erro, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
                <p class="link-login">Já tem conta?<a href="./login.php">Entre já</a> </p>
                <button type="submit">Cadastrar <img src="./imagens/seta-entrar.png" alt=""></button>
            </form>
        </div>
    </div>
    <script>
      (() => {
        const campoCep = document.getElementById('cep');
        const campoEstado = document.getElementById('estado');
        const campoCidade = document.getElementById('cidade');
        const campoRua = document.getElementById('rua');
        const statusEndereco = document.getElementById('endereco-status');
        const estados = {
          AC: 'Acre', AL: 'Alagoas', AP: 'Amapá', AM: 'Amazonas', BA: 'Bahia', CE: 'Ceará', DF: 'Distrito Federal',
          ES: 'Espírito Santo', GO: 'Goiás', MA: 'Maranhão', MT: 'Mato Grosso', MS: 'Mato Grosso do Sul', MG: 'Minas Gerais',
          PA: 'Pará', PB: 'Paraíba', PR: 'Paraná', PE: 'Pernambuco', PI: 'Piauí', RJ: 'Rio de Janeiro', RN: 'Rio Grande do Norte',
          RS: 'Rio Grande do Sul', RO: 'Rondônia', RR: 'Roraima', SC: 'Santa Catarina', SP: 'São Paulo', SE: 'Sergipe', TO: 'Tocantins'
        };
        let requisicaoCep = 0;
        let requisicaoCidades = 0;
        let temporizadorCep;

        Object.entries(estados).forEach(([uf, nome]) => {
          const opcao = new Option(`${nome} (${uf})`, uf);
          campoEstado.add(opcao);
        });

        const normalizar = (texto) => texto.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLocaleLowerCase('pt-BR').trim();

        async function carregarCidades(uf, cidadeSelecionada = '') {
          const idRequisicao = ++requisicaoCidades;
          campoCidade.disabled = true;
          campoCidade.replaceChildren(new Option('Carregando cidades...', ''));
          if (!uf) {
            campoCidade.replaceChildren(new Option('Escolha o estado primeiro', ''));
            return;
          }

          try {
            const resposta = await fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${encodeURIComponent(uf)}/municipios?orderBy=nome`);
            if (!resposta.ok) throw new Error('Falha ao carregar cidades');
            const cidades = await resposta.json();
            if (idRequisicao !== requisicaoCidades) return;
            campoCidade.replaceChildren(new Option('Escolha a cidade', ''));
            cidades.forEach((item) => campoCidade.add(new Option(item.nome, item.nome)));
            if (cidadeSelecionada) {
              let opcao = Array.from(campoCidade.options).find((item) => normalizar(item.value) === normalizar(cidadeSelecionada));
              if (!opcao) {
                opcao = new Option(cidadeSelecionada, cidadeSelecionada);
                campoCidade.add(opcao);
              }
              campoCidade.value = opcao.value;
            }
            campoCidade.disabled = false;
          } catch (erro) {
            if (idRequisicao !== requisicaoCidades) return;
            campoCidade.replaceChildren(new Option('Não foi possível carregar as cidades', ''));
            statusEndereco.textContent = 'Não foi possível carregar as cidades. Tente escolher o estado novamente.';
          }
        }

        async function buscarCep() {
          const cep = campoCep.value.replace(/\D/g, '');
          if (cep.length !== 8) return;
          const idRequisicao = ++requisicaoCep;
          statusEndereco.textContent = 'Buscando endereço...';
          try {
            const resposta = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
            if (!resposta.ok) throw new Error('Falha na consulta do CEP');
            const endereco = await resposta.json();
            if (idRequisicao !== requisicaoCep) return;
            if (endereco.erro) {
              statusEndereco.textContent = 'CEP não encontrado. Você pode preencher o endereço manualmente.';
              return;
            }
            campoEstado.value = endereco.uf || '';
            await carregarCidades(endereco.uf || '', endereco.localidade || '');
            if (idRequisicao !== requisicaoCep) return;
            if (endereco.logradouro) campoRua.value = endereco.logradouro;
            if (!campoCidade.disabled) statusEndereco.textContent = 'Endereço encontrado. Confira os dados e complete a rua, se necessário.';
          } catch (erro) {
            if (idRequisicao === requisicaoCep) statusEndereco.textContent = 'Não foi possível consultar o CEP. Preencha o endereço manualmente.';
          }
        }

        campoCep.addEventListener('input', () => {
          const numeros = campoCep.value.replace(/\D/g, '').slice(0, 8);
          campoCep.value = numeros.length > 5 ? `${numeros.slice(0, 5)}-${numeros.slice(5)}` : numeros;
          window.clearTimeout(temporizadorCep);
          if (numeros.length === 8) temporizadorCep = window.setTimeout(buscarCep, 250);
          else {
            requisicaoCep++;
            statusEndereco.textContent = '';
          }
        });

        campoEstado.addEventListener('change', () => {
          requisicaoCep++;
          window.clearTimeout(temporizadorCep);
          statusEndereco.textContent = '';
          carregarCidades(campoEstado.value);
        });

        const estadoInicial = campoEstado.dataset.selecionado;
        if (estadoInicial) {
          campoEstado.value = estadoInicial;
          carregarCidades(estadoInicial, campoCidade.dataset.selecionada);
        }
      })();

      document.querySelectorAll('[data-revelar-senha]').forEach((botao) => {
        botao.addEventListener('click', () => {
          const campo = botao.parentElement.querySelector('input');
          const revelar = campo.type === 'password';
          campo.type = revelar ? 'text' : 'password';
          botao.textContent = revelar ? 'Ocultar' : 'Mostrar';
          botao.setAttribute('aria-pressed', revelar ? 'true' : 'false');
          botao.setAttribute('aria-label', revelar ? 'Ocultar senha' : 'Mostrar senha');
        });
      });
    </script>
</body>

</html>
