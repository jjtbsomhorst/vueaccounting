<?php

use DI\ContainerBuilder;
use jsomhorst\slim\Middleware\RoutingMiddleWare;
use Slim\Factory\AppFactory;
use Slim\Handlers\Strategies\RequestResponseArgs;
require __DIR__ . '/../vendor/autoload.php';



$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions('config/container.php');
AppFactory::setContainer($containerBuilder->build());
$app = \Slim\Factory\AppFactory::create();
$app->setBasePath('/vue-accounting/src/api/api.php');
//$app->get('/',function ($request, $response, $name) {
//    $response->getBody()->write('hello');
//    return $response;
//});

RoutingMiddleWare::addRoutes($app);

$app->run();