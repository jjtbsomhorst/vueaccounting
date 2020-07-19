<?php
require_once('AbstractDispatcher.php');
class UserDispatcher extends AbstractDispatcher{

    public function __construct(){
        parent::__construct();
        $this->authenticated = false;
    }

    public function doPost($request,$body){
        if($request[0] == 'users' && $request[1] == 'create'){
            echo $this->createUser($request,json_decode($body,true));
        }
    }

    private function exists($username){
        $userStore = $this->dbHandler->getUserStore();      
        if( $userStore->exists($username)){
            throw new Exception("username already taken");
        }
        return false;
    }

    private function createUser($request,$body){
        $userStore = $this->dbHandler->getUserStore();      
        
        try{
            $this->exists($body['username']);
            $userStore->createUser($body['username'],$body['password']);
            return json_encode(array("success"=>'User succesfully created'));
        }catch(Exception $e){
            return json_encode(array('error'=>'Username already taken'));
        }
    }
    
}