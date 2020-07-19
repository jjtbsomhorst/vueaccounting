<?php
require_once('../db/DatabaseHandler.php');

class TokenUtils{

    private static $instance = null;
    private $store = null;

    private function __construct(){
        $db = DatabaseHandler::getInstance();
        $this->store = $db->getTokenStore();
    }

    public static function getInstance(){
        if(self::$instance == null){
            self::$instance = new TokenUtils();
        }
        return self::$instance;
    }

    public function getUserIdForToken($token){
        $dbValue = $this->store->getToken($token);
        if($dbValue != null){
            return $dbValue['user'];
        }
        return null;
    }

    public function invalidateTokens($user){
        $this->store->deleteTokens($user);
    }

    public function createToken($user){
        $token = array(self::guidv4(),$user,time(),time()+3600,self::guidv4());
        $this->store->createToken($token);     
        $token = $this->store->getTokenByUserId($user);
        
        return array('token'=>$token['token'],'expires_in'=>$token['valid_until']);
    }
    
    public static function guidv4()
    {
        $data = openssl_random_pseudo_bytes(16);
        assert(strlen($data) == 16);
    
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function isValidToken($token){
        $token = $this->store->getToken($token);
        if(empty($token)){
            error_log('token is invalid.');
           return false; 
        }
        
        if($token['valid_until'] > time()){
            return true;
        }
        return false;
        
    }

}