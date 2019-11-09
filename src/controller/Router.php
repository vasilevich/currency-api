<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../api/Converter.php";

class Router
{
    private $app;

    public function __construct()
    {
        $this->app = AppFactory::create();
    }

    public function initRoutes()
    {
        $this->app->get('/api/convert', function (Request $request, Response $response, array $args) {
            $queryParams = $request->getQueryParams();
            $from = isset($queryParams['from']) ? strtoupper(trim($queryParams['from'])) : "ILS";
            $to = isset($queryParams['to']) ? strtoupper(trim($queryParams['to'])) : "USD";
            $amount = isset($queryParams['amount']) ? $queryParams['amount'] : 1;
            $source = isset($queryParams['source']) ? strtolower(trim($queryParams['source'])) : Converter::ISRAEL_SOURCE;
            $response
                ->getBody()
                ->write(json_encode([
                    "from" => $from,
                    "to" => $to,
                    "source" => $source,
                    "amount" => $amount, "result" => Converter::convert($from, $to, $amount, $source)]));
            return $response->withHeader('content-type', 'application/json');
        });

        $this->app->get('/api/rates', function (Request $request, Response $response, array $args) {
            $queryParams = $request->getQueryParams();
            $source = $queryParams['source'];
            $response
                ->getBody()
                ->write(Converter::getSource($source)->getCurrencyList()->serialize());
            return $response->withHeader('Content-type', 'application/json');
        });

        $this->app->get('/api/sources', function (Request $request, Response $response, array $args) {
            $queryParams = $request->getQueryParams();
            $response
                ->getBody()
                ->write(json_encode(Converter::getAvailableConverters()));
            return $response->withHeader('Content-type', 'application/json');
        });
        $this->app->run();
    }
}
