<?php

require_once('AbstractDispatcher.php');
require_once('TokenUtil.php');
class OAuthDispatcher extends AbstractDispatcher{
    
    public function __construct(){
        parent::__construct();
        $this->authenticated = false;
    }

    public function doPost($request,$body){
        if(($request[0] == 'oauth' && $request[1] == 'token')){
            return $this->createToken($request,$body);
            
        }
        
    }

    private function createToken($request, $body){
        $arr = json_decode($body,true);
        if(!$this->isValidInput($arr)){
            error_log("Password does not match..");
            header("HTTP//1.0 403 Unauthorized");
            return;
        }
        error_log('valid input. now create a token');
        $user = $this->dbHandler->getUserStore()->get($arr['username']);
        // invalidate all tokens for current user
        TokenUtils::getInstance()->invalidateTokens($user['id']);
        $token = TokenUtils::getInstance()->createToken($user['id']);
        echo json_encode($token);
    }


    private function isValidInput($body){
        
        if(empty($body)){
            error_log('No data send..');
            return false;
        }
        if(empty(array_diff_key(['username','password'],$body))){
            error_log('Username and or password specified');
            return false;
        }

        if($this->dbHandler->getUserStore()->exists($body['username'])){
            $user = $this->dbHandler->getUserStore()->get($body['username']);       
            return password_verify($body['password'],$user['password']); 
        };

        return false;
    }
}