<?php

namespace jsomhorst\slim\routes;

use Slim\App;

interface RoutingInterface
{
    public static function provideRoutes(App $app);

}