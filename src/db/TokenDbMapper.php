<?php
require_once("AbstractDbMapper.php");
class TokenDbMapper extends AbstractDbMapper{

    public function getToken($token){
        $tokens = $this->executeQuery("SELECT * FROM tokens where token = ?",[$token]);
        return $tokens[0];
    }

    public function getTokenByUserId($userid){
        $tokens =  $this->executeQuery("SELECT * FROM tokens where user = ?",[$userid]);
        return $tokens[0];
    }

    public function createToken($token){
        $this->executeQuery("INSERT INTO tokens(token,user,request_time,valid_until,refreshtoken) values(?,?,?,?,?)",$token);
        return true;
    }
    
    public function deleteToken($token){
        $this->executeQuery("DELETE FROM tokens where token = ?",[$token]);
        return true;
    }

    public function deleteTokens($username){
        $this->executeQuery("DELETE FROM tokens where user = ?",[$username]);
    }

    public function updateToken($token,$refreshToken){
        $rows = $this->executeQuery("SELECT * FROM tokens where refresh_token = ?",[$refreshToken]);
        
        if(empty($rows)){
            return false;
        }
        
        $user = $tokenRows[0]['user'];
        
        $this->deleteToken($token['token']);
        $this->createToken($user,$token);

    }

}
