<?php

use Microblog\Helpers\Utils;
use Microblog\Services\NoticiaServico;

require_once __DIR__ . '/vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8');

$termo = Utils::normalizarEntrada($_POST['busca'] ?? '', 'texto');

if ($termo === '') {
    echo json_encode([
        'total' => 0,
        'resultados' => [],
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

try {
    $noticiaServico = new NoticiaServico();
    $resultados = $noticiaServico->pesquisarPorTermo($termo);

    echo json_encode([
        'total' => count($resultados),
        'resultados' => $resultados,
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
} catch (Throwable $e) {
    Utils::registrarLog($e);
    http_response_code(500);
    echo json_encode([
        'erro' => 'Não foi possível realizar a pesquisa. Tente novamente.',
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
}
