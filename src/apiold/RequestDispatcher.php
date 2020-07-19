<?php

require_once('AccountDispatcher.php');
require_once('UserDispatcher.php');

require_once('../db/DatabaseHandler.php');
require_once('OauthHandler.php');

class RequestDispatcher{

    public static function dispatch(){
        $db = DatabaseHandler::getInstance();
        $method = $_SERVER['REQUEST_METHOD'];
        $request = explode("/",substr(@$_SERVER['PATH_INFO'],1));
        $dispatcher = null;
        switch($request[0]){
            case 'accounts':
                $dispatcher= new AccountDispatcher($db);
            break;
            case 'categories':
            break;
            case 'bookingitems':
            break;
            case 'oauth':
                $dispatcher = new OAuthDispatcher($db);
            break;
            break;
            case 'users':
                $dispatcher = new UserDispatcher($db);
            break;
            default:
                header("HTTP/1.0 404 Not Found");
                return;
        }


        $dispatcher->dispatch($method,$request);

    }
}