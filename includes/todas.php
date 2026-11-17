<?php
$noticias = (new \Microblog\Services\NoticiaServico())->listarTodasPublicas();
?>

<hr class="my-5 w-50 mx-auto">

<div class="row my-1">
    <div class="col-12 px-md-1">
        <div class="list-group">
            <h2 class="fs-6 text-center text-muted">Todas as notícias</h2>

            <?php foreach ($noticias as $noticia): ?>
                <a href="noticia.php?id=<?= (int) $noticia['id'] ?>" class="list-group-item list-group-item-action">
                    <h3 class="fs-6">
                        <time datetime="<?= \Microblog\Helpers\Utils::escaparHtml($noticia['data']) ?>">
                            <?= \Microblog\Helpers\Utils::escaparHtml(\Microblog\Helpers\Utils::formataData($noticia['data'])) ?>
                        </time>
                        - <?= \Microblog\Helpers\Utils::escaparHtml($noticia['titulo']) ?>
                    </h3>
                    <p><?= \Microblog\Helpers\Utils::escaparHtml($noticia['resumo']) ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
