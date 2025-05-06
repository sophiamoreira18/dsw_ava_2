<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();


$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setErrorHandler(HttpNotFoundException::class, function (
    Request $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {
    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(json_encode(["error" => "Recurso não foi encontrado"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
});

$app->get('/uma-api', function (Request $request, Response $response) {
    $data = ["mensagem" => "Uma API (Interface de Programação de Aplicações) permite que sistemas diferentes se comuniquem entre si usando protocolos padronizados, como HTTP."];
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json');
});


$app->get('/codigos', function (Request $request, Response $response) {
    $mensagem = "Os códigos de status de resposta HTTP indicam se uma solicitação foi concluída com êxito. 
Classes:
- 1xx: Informativo
- 2xx: Sucesso
- 3xx: Redirecionamento
- 4xx: Erro do cliente
- 5xx: Erro do servidor
Exemplos: 200 (OK), 404 (Não encontrado), 500 (Erro interno).";

    $response->getBody()->write(json_encode(["mensagem" => $mensagem]));
    return $response->withHeader('Content-Type', 'application/json');
});

// Endpoint /erro
$app->get('/erro', function (Request $request, Response $response) {
    $response->getBody()->write(json_encode(["error" => "Não encontrado"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
});

$app->run();