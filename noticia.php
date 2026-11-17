<?php

use Microblog\Helpers\Utils;
use Microblog\Services\NoticiaServico;

require_once __DIR__ . '/vendor/autoload.php';

$id = Utils::normalizarEntrada($_GET['id'] ?? null, 'inteiro');
Utils::verificarId($id);

$noticia = null;
$mensagemErro = '';

try {
    $noticiaServico = new NoticiaServico();
    $noticia = $noticiaServico->buscarParaExibicaoPorId($id);

    if ($noticia === null) {
        $mensagemErro = 'Notícia não encontrada.';
    }
} catch (Throwable $e) {
    Utils::registrarLog($e);
    $mensagemErro = 'Não foi possível carregar a notícia.';
}

require_once __DIR__ . '/includes/cabecalho.php';
?>

<div class="row my-1 mx-md-n1">

    <article class="col-12">
        <?php if ($mensagemErro !== ''): ?>
            <div class="alert alert-warning text-center">
                <?= Utils::escaparHtml($mensagemErro) ?>
            </div>
        <?php elseif ($noticia !== null): ?>
            <h2><?= Utils::escaparHtml($noticia['titulo']) ?></h2>
            <p class="font-weight-light">
                <time datetime="<?= Utils::escaparHtml($noticia['data']) ?>">
                    <?= Utils::escaparHtml(date('d/m/Y', strtotime($noticia['data']))) ?>
                </time> -
                <span><?= Utils::escaparHtml($noticia['autor'] ?? 'Equipe Microblog') ?></span>
            </p>
            <img
                src="images/<?= Utils::escaparHtml(rawurlencode($noticia['imagem'])) ?>"
                alt="<?= Utils::escaparHtml($noticia['titulo']) ?>"
                class="float-start pe-2 img-fluid"
            >
            <p class="ajusta-texto"><?= Utils::escaparHtml($noticia['texto']) ?></p>
        <?php endif; ?>
    </article>
    
</div>

<?php
require_once __DIR__ . '/includes/todas.php';
require_once __DIR__ . '/includes/rodape.php';
?>
