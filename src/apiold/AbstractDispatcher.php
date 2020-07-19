<?php
require_once('DispatcherInterface.php');
require_once('TokenUtil.php');
abstract class AbstractDispatcher implements IDispatcher{

    protected $dbHandler = null;
    protected $authenticated = true;

    public function __construct(){
        $this->dbHandler =  DatabaseHandler::getInstance();
    }

    public function getOauthTokenFromRequest(){
        if(array_key_exists('HTTP_OAUTH_TOKEN',$_SERVER)){
            return $_SERVER['HTTP_OAUTH_TOKEN'];
        }
        return null;
    }

    public function returnError($type = "error",$msg = ""){
        header("HTTP/1.0 400 Bad Request");
        echo json_encode([$type=>$msg]);
    }

    public function dispatch($method = "GET", $request = array()){

        header("Cache-Control: no-cache, must-revalidate");
        header("Content-Type: application/json");

        if($this->authenticated && !$this->isAuthorized()){
            header("HTTP/1.0 403 No access");
            return;
        }

        switch($method){
            case 'GET':
                $this->doGet($request,$_GET);
            break;
            case 'POST':
                $this->doPost($request, file_get_contents("php://input"));
            break;
            case 'PUT':
                $this->doPut($request, file_get_contents("php://input"));
            break;
            case 'DELETE':
                $this->doDelete($request);
            break;
        }
    }

    protected function getUserId(){
        $token = $this->getOauthTokenFromRequest();
        if(!empty($token)){
            return TokenUtils::getInstance()->getUserIdForToken($token);
        }

        return null;
    }

    public function doGet($request,$params){
        header('Content-Type: application/json');
    }
    public function doPost($request,$body){
        header('Content-Type: application/json');
    }
    public function doPut($request,$body){header('Content-Type: application/json');}
    public function doDelete($request){}

    public function isAuthorized(){
        $token = $_SERVER['HTTP_OAUTH_TOKEN'];
        if(!empty($token) && TokenUtils::getInstance()->isValidToken($token)){
            return true;
        }
        return false;
    }
}