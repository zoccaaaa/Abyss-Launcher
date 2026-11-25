<?php
require_once __DIR__ . '/data/auth.php';

$idUsuarioPerfil = (int) $_SESSION['usuario_id'];
$errosPerfil = [];
$mensagemPerfil = $_SESSION['mensagem_perfil'] ?? '';
unset($_SESSION['mensagem_perfil']);

if (empty($_SESSION['token_perfil'])) {
    $_SESSION['token_perfil'] = bin2hex(random_bytes(32));
}

$consultaPerfil = $conn->prepare('SELECT nome, email, descricao, foto_perfil, foto_banner FROM usuarios WHERE id_usuario=? LIMIT 1');
$consultaPerfil->bind_param('i', $idUsuarioPerfil);
$consultaPerfil->execute();
$perfil = $consultaPerfil->get_result()->fetch_assoc();

if (!$perfil) {
    $_SESSION = [];
    session_destroy();
    header('Location: ./login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $removerFotoPerfil = isset($_POST['remover_foto_perfil']);
    $removerBannerPerfil = isset($_POST['remover_banner_perfil']);
    $tokenEnviado = (string) ($_POST['token_perfil'] ?? '');
    if (!hash_equals($_SESSION['token_perfil'], $tokenEnviado)) {
        $errosPerfil[] = 'Sua sessão do formulário expirou. Atualize a página e tente novamente.';
    }

    $nomePerfil = trim((string) ($_POST['nome'] ?? ''));
    $descricaoPerfil = trim((string) ($_POST['descricao'] ?? ''));
    $contarCaracteres = static function ($texto) {
        if (function_exists('mb_strlen')) {
            return mb_strlen($texto, 'UTF-8');
        }
        $quantidade = preg_match_all('/./us', $texto);
        return $quantidade === false ? strlen($texto) : $quantidade;
    };
    $tamanhoNome = $contarCaracteres($nomePerfil);
    $tamanhoDescricao = $contarCaracteres($descricaoPerfil);

    if ($nomePerfil === '' || $tamanhoNome > 30) {
        $errosPerfil[] = 'Informe um nome com até 30 caracteres.';
    }
    if ($tamanhoDescricao > 500) {
        $errosPerfil[] = 'A descrição deve ter no máximo 500 caracteres.';
    }

    $fotoAtual = $perfil['foto_perfil'] ?? null;
    $arquivoEnviado = $_FILES['foto_perfil'] ?? null;
    $caminhoNovaFoto = null;
    $diretorioFotos = __DIR__ . '/uploads/perfis';

    if (!$removerFotoPerfil && $arquivoEnviado && $arquivoEnviado['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($arquivoEnviado['error'] !== UPLOAD_ERR_OK || $arquivoEnviado['size'] > 5 * 1024 * 1024) {
            $errosPerfil[] = 'A foto deve ter no máximo 5 MB e ser enviada corretamente.';
        } elseif (!is_uploaded_file($arquivoEnviado['tmp_name'])) {
            $errosPerfil[] = 'Não foi possível validar o arquivo enviado.';
        } else {
            $tiposPermitidos = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                'image/gif' => 'gif',
            ];
            $imagemValida = @getimagesize($arquivoEnviado['tmp_name']);
            $tipoArquivo = is_array($imagemValida) ? ($imagemValida['mime'] ?? '') : '';

            if (!isset($tiposPermitidos[$tipoArquivo]) || !$imagemValida) {
                $errosPerfil[] = 'Use uma imagem JPG, PNG, WEBP ou GIF.';
            } else {
                $nomeArquivo = $idUsuarioPerfil . '-' . bin2hex(random_bytes(16)) . '.' . $tiposPermitidos[$tipoArquivo];
                $caminhoNovaFoto = 'uploads/perfis/' . $nomeArquivo;
                if (!is_dir($diretorioFotos) && !mkdir($diretorioFotos, 0755, true) && !is_dir($diretorioFotos)) {
                    $errosPerfil[] = 'Não foi possível preparar o armazenamento das fotos.';
                    $caminhoNovaFoto = null;
                } elseif (!move_uploaded_file($arquivoEnviado['tmp_name'], $diretorioFotos . '/' . $nomeArquivo)) {
                    $errosPerfil[] = 'Não foi possível salvar a foto enviada.';
                    $caminhoNovaFoto = null;
                }
            }
        }
    }

    $bannerAtual = $perfil['foto_banner'] ?? null;
    $arquivoBanner = $_FILES['foto_banner'] ?? null;
    $caminhoNovoBanner = null;
    if (!$removerBannerPerfil && $arquivoBanner && $arquivoBanner['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($arquivoBanner['error'] !== UPLOAD_ERR_OK || $arquivoBanner['size'] > 5 * 1024 * 1024) {
            $errosPerfil[] = 'Nao foi possivel processar o banner.';
        } elseif (!is_uploaded_file($arquivoBanner['tmp_name'])) {
            $errosPerfil[] = 'Nao foi possivel processar o banner.';
        } else {
            $tiposPermitidos = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                'image/gif' => 'gif',
            ];
            $imagemValida = @getimagesize($arquivoBanner['tmp_name']);
            $tipoArquivo = is_array($imagemValida) ? ($imagemValida['mime'] ?? '') : '';

            if (!isset($tiposPermitidos[$tipoArquivo]) || !$imagemValida) {
                $errosPerfil[] = 'Nao foi possivel processar o banner.';
            } else {
                $nomeArquivo = $idUsuarioPerfil . '-banner-' . bin2hex(random_bytes(16)) . '.' . $tiposPermitidos[$tipoArquivo];
                $caminhoNovoBanner = 'uploads/perfis/' . $nomeArquivo;
                if (!is_dir($diretorioFotos) && !mkdir($diretorioFotos, 0755, true) && !is_dir($diretorioFotos)) {
                    $errosPerfil[] = 'Nao foi possivel processar o banner.';
                    $caminhoNovoBanner = null;
                } elseif (!move_uploaded_file($arquivoBanner['tmp_name'], $diretorioFotos . '/' . $nomeArquivo)) {
                    $errosPerfil[] = 'Nao foi possivel processar o banner.';
                    $caminhoNovoBanner = null;
                }
            }
        }
    }

    if (!$errosPerfil) {
        $fotoParaSalvar = $removerFotoPerfil ? null : ($caminhoNovaFoto ?? $fotoAtual);
        $bannerParaSalvar = $removerBannerPerfil ? null : ($caminhoNovoBanner ?? $bannerAtual);
        $atualizarPerfil = $conn->prepare('UPDATE usuarios SET nome=?, descricao=?, foto_perfil=?, foto_banner=? WHERE id_usuario=?');
        $atualizarPerfil->bind_param('ssssi', $nomePerfil, $descricaoPerfil, $fotoParaSalvar, $bannerParaSalvar, $idUsuarioPerfil);

        if ($atualizarPerfil->execute()) {
            $_SESSION['usuario_nome'] = $nomePerfil;
            if (($removerFotoPerfil || $caminhoNovaFoto !== null) && !empty($fotoAtual) && strpos($fotoAtual, 'uploads/perfis/') === 0) {
                $arquivoAntigo = $diretorioFotos . '/' . basename($fotoAtual);
                if (is_file($arquivoAntigo)) {
                    unlink($arquivoAntigo);
                }
            }
            if (($removerBannerPerfil || $caminhoNovoBanner !== null) && !empty($bannerAtual) && strpos($bannerAtual, 'uploads/perfis/') === 0) {
                $bannerAntigo = $diretorioFotos . '/' . basename($bannerAtual);
                if (is_file($bannerAntigo)) {
                    unlink($bannerAntigo);
                }
            }
            if ($removerFotoPerfil && $removerBannerPerfil) {
                $_SESSION['mensagem_perfil'] = 'Foto de perfil e banner removidos.';
            } elseif ($removerFotoPerfil) {
                $_SESSION['mensagem_perfil'] = 'Foto de perfil removida.';
            } elseif ($removerBannerPerfil) {
                $_SESSION['mensagem_perfil'] = 'Banner removido.';
            } else {
                $_SESSION['mensagem_perfil'] = 'Perfil atualizado com sucesso.';
            }
            header('Location: ./perfil.php#editar-perfil');
            exit;
        }

        if ($caminhoNovaFoto !== null && is_file(__DIR__ . '/' . $caminhoNovaFoto)) {
            unlink(__DIR__ . '/' . $caminhoNovaFoto);
        }
        if ($caminhoNovoBanner !== null && is_file(__DIR__ . '/' . $caminhoNovoBanner)) {
            unlink(__DIR__ . '/' . $caminhoNovoBanner);
        }
        $errosPerfil[] = 'Não foi possível atualizar seu perfil. Tente novamente.';
    }

    if ($caminhoNovaFoto !== null && is_file(__DIR__ . '/' . $caminhoNovaFoto)) {
        unlink(__DIR__ . '/' . $caminhoNovaFoto);
    }
    if ($caminhoNovoBanner !== null && is_file(__DIR__ . '/' . $caminhoNovoBanner)) {
        unlink(__DIR__ . '/' . $caminhoNovoBanner);
    }
    $perfil['nome'] = $nomePerfil;
    $perfil['descricao'] = $descricaoPerfil;
    if ($caminhoNovaFoto !== null && !$errosPerfil) {
        $perfil['foto_perfil'] = $caminhoNovaFoto;
    }
}

$fotoPerfil = !empty($perfil['foto_perfil']) ? './' . ltrim($perfil['foto_perfil'], '/') : './imagens/perfil.png';
$fotoPerfilPadrao = empty($perfil['foto_perfil']);
$bannerPerfil = !empty($perfil['foto_banner']) ? './' . ltrim($perfil['foto_banner'], '/') : './imagens/fundo.png';
$bannerPerfilPadrao = empty($perfil['foto_banner']);
$escapar = static function ($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
};
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil do jogador</title>
  <link rel="icon" type="image/png" href="./imagens/logo.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./css/header.css">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="./css/footer.css">
  <style>header,.footer{font-family:'Poppins',sans-serif}</style>
</head>
<body>
  <?php require './view/header.php'; ?>

  <main class="page">
    <section class="profile-card">
      <form id="profile-form" class="profile-form" action="./perfil.php#editar-perfil" method="post" enctype="multipart/form-data">
      <input type="hidden" name="token_perfil" value="<?= $escapar($_SESSION['token_perfil']) ?>">
      <div class="cover edit-image">
        <img class="cover-image" src="<?= $escapar($bannerPerfil) ?>" alt="Banner do perfil de <?= $escapar($perfil['nome']) ?>">
        <input class="image-upload" id="foto_banner" name="foto_banner" type="file" accept="image/jpeg,image/png,image/webp,image/gif" aria-label="Escolher banner do perfil">
        <label class="image-edit" for="foto_banner"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/></svg><span>Editar banner</span></label>
        <?php if (!$bannerPerfilPadrao): ?>
          <button class="overlay-remove overlay-remove-banner" type="submit" name="remover_banner_perfil" value="1" formnovalidate>Remover banner</button>
        <?php endif; ?>
        <span class="age-rating"></span>
      </div>

      <div class="profile-content">
        <section class="user-summary">
          <div class="avatar avatar-large edit-image <?= $fotoPerfilPadrao ? 'avatar-placeholder' : '' ?>">
            <img src="<?= $escapar($fotoPerfil) ?>" alt="Foto de perfil de <?= $escapar($perfil['nome']) ?>" data-avatar-preview>
            <input class="image-upload" id="foto_perfil" name="foto_perfil" type="file" accept="image/jpeg,image/png,image/webp,image/gif" aria-label="Escolher foto de perfil">
            <button class="avatar-actions-toggle" type="button" aria-label="Opções da foto de perfil" aria-expanded="false" aria-controls="avatar-photo-actions">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/></svg>
            </button>
            <div class="avatar-photo-actions" id="avatar-photo-actions" hidden>
              <label for="foto_perfil">Editar foto</label>
              <?php if (!$fotoPerfilPadrao): ?>
                <button type="submit" name="remover_foto_perfil" value="1" formnovalidate>Excluir foto</button>
              <?php endif; ?>
            </div>
          </div>
          <div class="user-info">
            <h1><?= $escapar($perfil['nome']) ?></h1>
            <p class="email"><?= $escapar($perfil['email']) ?></p>
            <p class="bio-short"><?= $escapar($perfil['descricao'] ?: 'Adicione uma descrição ao seu perfil.') ?></p>
          </div>
        </section>

        <section class="edit-profile" id="editar-perfil">
          

          <?php if ($mensagemPerfil !== ''): ?>
            <p class="feedback-perfil sucesso" role="status"><?= $escapar($mensagemPerfil) ?></p>
          <?php endif; ?>
          <?php foreach ($errosPerfil as $erroPerfil): ?>
            <p class="feedback-perfil erro" role="alert"><?= $escapar($erroPerfil) ?></p>
          <?php endforeach; ?>

            <div class="form-field">
              <label for="descricao">Descrição</label>
              <textarea id="descricao" name="descricao" maxlength="500" placeholder="Conte um pouco sobre você."><?= $escapar($perfil['descricao'] ?? '') ?></textarea>
            </div>

            <div class="form-field">
              <label for="nome">Nome</label>
              <input id="nome" name="nome" type="text" maxlength="30" required value="<?= $escapar($perfil['nome']) ?>">
            </div>

            

            <div class="form-actions">
              <a class="cancel" href="./perfil.php">Cancelar</a>
              <button class="save" type="submit">Salvar alterações</button>
            </div>
          
        </section>

        <section class="favorites" aria-labelledby="favorites-title">
          <div class="favorites-heading">
            <h2 id="favorites-title">Jogos favoritos</h2>
            <span aria-hidden="true">★</span>
          </div>
          <div class="game-list">
            <img src="./imagens/god of ware.png" alt="God of War">
            <img src="./imagens/cyberpunk.png" alt="Cyberpunk 2077">
            <img src="./imagens/spider man.png" alt="Marvel's Spider-Man">
            <img src="./imagens/fortnite.png" alt="Fortnite">
            <img src="./imagens/metro.png" alt="Metro">
            <img src="./imagens/hollow knight.jpg" alt="Hollow Knight">
          </div>
        </section>
      </div>
      </form>
    </section>
  </main>

  <dialog class="crop-dialog" id="crop-dialog" aria-labelledby="crop-title">
    <div class="crop-modal">
      <div class="crop-heading">
        <div>
          <p class="crop-eyebrow">AJUSTE DA IMAGEM</p>
          <h2 id="crop-title">Enquadrar foto</h2>
        </div>
        <button class="crop-close" id="crop-cancel-top" type="button" aria-label="Cancelar edição">&times;</button>
      </div>
      <p class="crop-instructions">Arraste a imagem para posicionar. Use o controle para aproximar ou afastar.</p>
      <div class="crop-stage">
        <canvas id="crop-canvas" aria-label="Prévia do recorte. Arraste para ajustar a posição." tabindex="0"></canvas>
      </div>
      <label class="crop-zoom" for="crop-zoom-range">
        <span>Zoom</span>
        <input id="crop-zoom-range" type="range" min="1" max="3" step="0.01" value="1">
        <output id="crop-zoom-value" for="crop-zoom-range">100%</output>
      </label>
      <p class="crop-status" id="crop-status" role="status"></p>
      <div class="crop-actions">
        <button class="crop-cancel" id="crop-cancel" type="button">Cancelar</button>
        <button class="crop-apply" id="crop-apply" type="button">Aplicar recorte</button>
      </div>
    </div>
  </dialog>

  <?php require './view/footer.php'; ?>
  <script>
    (() => {
      const dialogo = document.getElementById('crop-dialog');
      const canvas = document.getElementById('crop-canvas');
      const contexto = canvas.getContext('2d');
      const zoomControle = document.getElementById('crop-zoom-range');
      const zoomValor = document.getElementById('crop-zoom-value');
      const titulo = document.getElementById('crop-title');
      const status = document.getElementById('crop-status');
      const botaoAplicar = document.getElementById('crop-apply');
      let inputAtual = null;
      let imagemAtual = null;
      let urlTemporaria = null;
      let escalaBase = 1;
      let zoom = 1;
      let posicaoX = 0;
      let posicaoY = 0;
      let arrastando = false;
      let pontoAnterior = null;

      function limitarPosicao() {
        const larguraImagem = imagemAtual.width * escalaBase * zoom;
        const alturaImagem = imagemAtual.height * escalaBase * zoom;
        posicaoX = Math.min(0, Math.max(canvas.width - larguraImagem, posicaoX));
        posicaoY = Math.min(0, Math.max(canvas.height - alturaImagem, posicaoY));
      }

      function desenharRecorte() {
        if (!imagemAtual) return;
        limitarPosicao();
        contexto.clearRect(0, 0, canvas.width, canvas.height);
        contexto.fillStyle = '#111';
        contexto.fillRect(0, 0, canvas.width, canvas.height);
        contexto.drawImage(
          imagemAtual,
          posicaoX,
          posicaoY,
          imagemAtual.width * escalaBase * zoom,
          imagemAtual.height * escalaBase * zoom
        );
      }

      function cancelarRecorte() {
        if (inputAtual) inputAtual.value = '';
        if (urlTemporaria) URL.revokeObjectURL(urlTemporaria);
        urlTemporaria = null;
        imagemAtual = null;
        inputAtual = null;
        status.textContent = '';
        if (dialogo.open) dialogo.close();
      }

      document.querySelectorAll('.image-upload').forEach((input) => {
        input.addEventListener('change', () => {
          const arquivo = input.files && input.files[0];
          if (!arquivo) return;

          inputAtual = input;
          const ehBanner = input.id === 'foto_banner';
          canvas.width = ehBanner ? 1200 : 640;
          if (ehBanner) {
            const areaBanner = input.closest('.cover').getBoundingClientRect();
            canvas.height = Math.round(canvas.width * areaBanner.height / areaBanner.width);
          } else {
            canvas.height = 640;
          }
          titulo.textContent = ehBanner ? 'Enquadrar banner' : 'Enquadrar foto';
          status.textContent = '';
          botaoAplicar.disabled = true;
          if (urlTemporaria) URL.revokeObjectURL(urlTemporaria);
          urlTemporaria = URL.createObjectURL(arquivo);
          imagemAtual = new Image();
          imagemAtual.onload = () => {
            escalaBase = Math.max(canvas.width / imagemAtual.width, canvas.height / imagemAtual.height);
            zoom = 1;
            zoomControle.value = '1';
            zoomValor.value = '100%';
            posicaoX = (canvas.width - imagemAtual.width * escalaBase) / 2;
            posicaoY = (canvas.height - imagemAtual.height * escalaBase) / 2;
            desenharRecorte();
            botaoAplicar.disabled = false;
            if (!dialogo.open) dialogo.showModal();
            canvas.focus();
          };
          imagemAtual.onerror = () => {
            cancelarRecorte();
            status.textContent = 'Não foi possível abrir essa imagem. Escolha outro arquivo.';
            dialogo.showModal();
          };
          imagemAtual.src = urlTemporaria;
        });
      });

      zoomControle.addEventListener('input', () => {
        if (!imagemAtual) return;
        const novoZoom = Number(zoomControle.value);
        const proporcao = novoZoom / zoom;
        posicaoX = canvas.width / 2 - (canvas.width / 2 - posicaoX) * proporcao;
        posicaoY = canvas.height / 2 - (canvas.height / 2 - posicaoY) * proporcao;
        zoom = novoZoom;
        zoomValor.value = `${Math.round(zoom * 100)}%`;
        desenharRecorte();
      });

      canvas.addEventListener('pointerdown', (evento) => {
        arrastando = true;
        pontoAnterior = { x: evento.clientX, y: evento.clientY };
        canvas.setPointerCapture(evento.pointerId);
        canvas.classList.add('arrastando');
      });

      canvas.addEventListener('pointermove', (evento) => {
        if (!arrastando || !pontoAnterior) return;
        const limites = canvas.getBoundingClientRect();
        posicaoX += (evento.clientX - pontoAnterior.x) * canvas.width / limites.width;
        posicaoY += (evento.clientY - pontoAnterior.y) * canvas.height / limites.height;
        pontoAnterior = { x: evento.clientX, y: evento.clientY };
        desenharRecorte();
      });

      function pararArrasto() {
        arrastando = false;
        pontoAnterior = null;
        canvas.classList.remove('arrastando');
      }
      canvas.addEventListener('pointerup', pararArrasto);
      canvas.addEventListener('pointercancel', pararArrasto);
      canvas.addEventListener('keydown', (evento) => {
        const passo = evento.shiftKey ? 20 : 6;
        if (evento.key === 'ArrowLeft') posicaoX += passo;
        else if (evento.key === 'ArrowRight') posicaoX -= passo;
        else if (evento.key === 'ArrowUp') posicaoY += passo;
        else if (evento.key === 'ArrowDown') posicaoY -= passo;
        else return;
        evento.preventDefault();
        desenharRecorte();
      });

      botaoAplicar.addEventListener('click', () => {
        if (!imagemAtual || !inputAtual) return;
        botaoAplicar.disabled = true;
        canvas.toBlob((blob) => {
          if (!blob) {
            status.textContent = 'Não foi possível preparar o recorte.';
            botaoAplicar.disabled = false;
            return;
          }
          const nomeBase = inputAtual.files[0].name.replace(/\.[^.]+$/, '');
          const arquivoCortado = new File([blob], `${nomeBase}-cortada.jpg`, { type: 'image/jpeg' });
          const transferencia = new DataTransfer();
          transferencia.items.add(arquivoCortado);
          inputAtual.files = transferencia.files;
          const alvo = inputAtual.id === 'foto_banner'
            ? inputAtual.closest('.cover').querySelector('.cover-image')
            : inputAtual.closest('.avatar').querySelector('img');
          if (alvo.dataset.previewUrl) URL.revokeObjectURL(alvo.dataset.previewUrl);
          alvo.dataset.previewUrl = URL.createObjectURL(arquivoCortado);
          alvo.src = alvo.dataset.previewUrl;
          const inputSalvo = inputAtual;
          if (urlTemporaria) URL.revokeObjectURL(urlTemporaria);
          urlTemporaria = null;
          imagemAtual = null;
          inputAtual = null;
          dialogo.close();
          inputSalvo.focus();
        }, 'image/jpeg', .92);
      });

      document.getElementById('crop-cancel').addEventListener('click', cancelarRecorte);
      document.getElementById('crop-cancel-top').addEventListener('click', cancelarRecorte);
      const avatarActionsToggle = document.querySelector('.avatar-actions-toggle');
      const avatarPhotoActions = document.getElementById('avatar-photo-actions');
      avatarActionsToggle.addEventListener('click', () => {
        const abrir = avatarActionsToggle.getAttribute('aria-expanded') !== 'true';
        avatarActionsToggle.setAttribute('aria-expanded', String(abrir));
        avatarPhotoActions.hidden = !abrir;
      });
      document.addEventListener('click', (evento) => {
        if (!evento.target.closest('.avatar.edit-image')) {
          avatarActionsToggle.setAttribute('aria-expanded', 'false');
          avatarPhotoActions.hidden = true;
        }
      });
      document.addEventListener('keydown', (evento) => {
        if (evento.key === 'Escape') {
          avatarActionsToggle.setAttribute('aria-expanded', 'false');
          avatarPhotoActions.hidden = true;
        }
      });
      dialogo.addEventListener('cancel', (evento) => {
        evento.preventDefault();
        cancelarRecorte();
      });
    })();
  </script>
</body>
</html>
