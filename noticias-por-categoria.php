<?php

use Microblog\Helpers\Utils;
use Microblog\Services\CategoriaServico;
use Microblog\Services\NoticiaServico;

require_once __DIR__ . '/vendor/autoload.php';

$categoriaId = Utils::normalizarEntrada($_GET['id'] ?? null, 'inteiro');
Utils::verificarId($categoriaId);

$categoriaSelecionada = null;
$noticias = [];
$mensagemErro = '';

try {
    $categoriaServico = new CategoriaServico();
    $categoriaSelecionada = $categoriaServico->buscarPorId($categoriaId);

    if ($categoriaSelecionada === null) {
        $mensagemErro = 'Categoria não encontrada.';
    } else {
        $noticiaServico = new NoticiaServico();
        $noticias = $noticiaServico->listarPorCategoria($categoriaId);

        if ($noticias === []) {
            $mensagemErro = 'Não há notícias cadastradas nesta categoria.';
        }
    }
} catch (Throwable $e) {
    Utils::registrarLog($e);
    $mensagemErro = 'Não foi possível carregar as notícias da categoria.';
}

require_once __DIR__ . '/includes/cabecalho.php';
?>

<div class="row my-1 mx-md-n1">
    <article class="col-12">
        <h2 class="text-center">
            Notícias sobre
            <span class="badge bg-primary">
                <?= Utils::escaparHtml($categoriaSelecionada['nome'] ?? 'Categoria') ?>
            </span>
        </h2>

        <div class="row my-1">
            <div class="col-12 px-md-1">
                <div class="list-group">
                    <?php if ($mensagemErro !== ''): ?>
                        <div class="alert alert-warning text-center">
                            <?= Utils::escaparHtml($mensagemErro) ?>
                        </div>
                    <?php else: ?>
                        <?php foreach ($noticias as $noticia): ?>
                            <a href="noticia.php?id=<?= (int) $noticia['id'] ?>" class="list-group-item list-group-item-action">
                                <h3 class="fs-6"><?= Utils::escaparHtml($noticia['titulo']) ?></h3>
                                <p>
                                    <time datetime="<?= Utils::escaparHtml($noticia['data']) ?>">
                                        <?= Utils::escaparHtml(date('d/m/Y', strtotime($noticia['data']))) ?>
                                    </time>
                                    - <?= Utils::escaparHtml($noticia['autor'] ?? 'Equipe Microblog') ?>
                                </p>
                                <p><?= Utils::escaparHtml($noticia['resumo']) ?></p>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </article>
</div>

<?php
require_once __DIR__ . '/includes/todas.php';
require_once __DIR__ . '/includes/rodape.php';
?>
