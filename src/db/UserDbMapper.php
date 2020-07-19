<?php
require_once('../apiold/TokenUtil.php');;
require_once('AbstractDbMapper.php');
class UserDbMapper extends AbstractDbMapper{
    
    public function get($username){
        $rows = $this->executeQuery("SELECT * FROM users where username = ? and deleted = 0",[$username]);
        return $rows[0];
    }

    public function getById($id){
        $rows = $this->executeQuery("SELECT * FROM users where id = ? and deleted = 0",[$id]);
        return $rows[0];
    }

    public function exists($username){
        $user = $this->get($username);
        return empty($user);
    }

    public function createUser($username,$password){
        $this->executeQuery("INSERT INTO users(id,username,password,deleted) values(?,?,?,?)",array(TokenUtils::guidv4(),$username,password_hash($password,PASSWORD_BCRYPT),0));
    }

    public function updateUser($username,$password){
        $this->execute('UPDATE users set password = ? where username = ?',array(password_hash($password,PASSWORD_BCRYPT),$username));
    }

    public function deleteUser($username){
        $this->execute('UPDATE users set deleted = 1 where username = ?',array($username));
    }
}