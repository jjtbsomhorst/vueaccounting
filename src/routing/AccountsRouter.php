<?php

namespace jsomhorst\slim\routes;

use Doctrine\ORM\QueryBuilder;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Routing\RouteCollectorProxy;

class AccountsRouter implements RoutingInterface
{

    public static function provideRoutes(\Slim\App $app)
    {
        $app->group('/accounts',function (RouteCollectorProxy $group) {
            $group->map(['GET'],'/[{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}]',self::class.':read');
            $group->map(['PUT','POST'],'/[{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}]',self::class.':cu');
            $group->map(['DELETE'],'/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',self::class.':delete');
        });
    }

    public function read(RequestInterface $request, ResponseInterface $response, array $args){
        if(array_key_exists('id',$args)){
            $response->getBody()->write('retrieve entry');
        }else{
            $response->getBody()->write('retrieve list');
        }
        return $response;
    }
    public function cu(RequestInterface $request, ResponseInterface $response, array $args){
        return $response;
    }
    public function delete(RequestInterface $request, ResponseInterface $response, array $args){

        return $response;
    }


}