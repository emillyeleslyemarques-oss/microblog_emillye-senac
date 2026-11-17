<?php
require_once __DIR__ . '/includes/cabecalho.php';
$noticiasDestaque = (new \Microblog\Services\NoticiaServico())->listarDestaques();
?>

<div class="row my-1 mx-md-n1">
    <?php foreach ($noticiasDestaque as $noticia): ?>
        <div class="col-md-6 my-1 px-md-1">
            <article class="card shadow-sm h-100">
                <a href="noticia.php?id=<?= (int) $noticia['id'] ?>" class="card-link">
                    <img
                        src="images/<?= \Microblog\Helpers\Utils::escaparHtml($noticia['imagem']) ?>"
                        class="card-img-top"
                        alt="<?= \Microblog\Helpers\Utils::escaparHtml($noticia['titulo']) ?>"
                    >
                    <div class="card-body">
                        <h2 class="fs-4 card-title"><?= \Microblog\Helpers\Utils::escaparHtml($noticia['titulo']) ?></h2>
                        <p class="card-text"><?= \Microblog\Helpers\Utils::escaparHtml($noticia['resumo']) ?></p>
                    </div>
                </a>
            </article>
        </div>
    <?php endforeach; ?>
</div>

<?php
require_once __DIR__ . '/includes/todas.php';
require_once __DIR__ . '/includes/rodape.php';
?>
