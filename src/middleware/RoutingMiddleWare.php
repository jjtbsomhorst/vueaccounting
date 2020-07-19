<?php


namespace jsomhorst\slim\Middleware;


use jsomhorst\slim\routes\AccountsRouter;
use jsomhorst\slim\routes\RoutingInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;

class RoutingMiddleWare implements RoutingInterface
{
    public static function addRoutes(App $app){
        self::provideRoutes($app);
        AccountsRouter::provideRoutes($app);
    }

    public static function provideRoutes(App $app)
    {
        $app->get('/',self::class.':get');
    }
    public function get(RequestInterface $request, ResponseInterface $response, array $args){
        $response->getBody()->write('hello');
        return $response;
    }

}