<?php
require_once('../db/AccountDbMapper.php');
require_once('../db/BookingItemsDbMapper.php');
require_once('../db/CategoriesDbMapper.php');
require_once('../db/TokenDbMapper.php');
require_once('../db/UserDbMapper.php');

class DatabaseHandler{

    private static $instance = null;
    private $connection = null;
    const dbDirectory = "../db/accounting.db";
    private $tokenStore = null;
    private $bookingItemsStore = null;
    private $categoriesStore = null;
    private $userTableHandler = null;
    private $accountStore = null;
    private function __construct(){
        $this->connection = new PDO(sprintf('sqlite:%s',self::dbDirectory));
        if($this->connection == null){
            throw new Exception("unable to create sql file!!!");
        }
        $this->init();
    }
    private function init(){

        $result = $this->connection->query("
        CREATE TABLE IF NOT EXISTS accounts (id VARCHAR(32) PRIMARY KEY NOT NULL,name VARCHAR(50) not null, accountnumber VARCHAR(32),userid VARCHAR(32), deleted TINYINT);
        CREATE TABLE IF NOT EXISTS entries (id VARCHAR(32) PRIMARY KEY NOT NULL, account VARCHAR(32) not null,entrydate INT not null,description TEXT not null,amount FLOAT not null,dc CHARACTER(2), bookingperiode varchar(32), deleted TINYINT );
        CREATE INDEX IF NOT EXISTS accountid ON entries(account);
        CREATE INDEX IF NOT EXISTS dctype on entries(account,dc);
        CREATE TABLE IF NOT EXISTS users (id VARCHAR(32) PRIMARY KEY NOT NULL, username VARCHAR(50), password text, deleted TINYINT);
        CREATE TABLE IF NOT EXISTS tokens (token VARCHAR(32) NOT NULL,user varchar(32), request_time INTEGER not null,valid_until INTEGER, refreshtoken VARCHAR(32));
        CREATE TABLE IF NOT EXISTS categories (id varchar(32), account varchar(32) not null, name varchar(50), deleted tinyint not null);
        CREATE TABLE IF NOT EXISTS bookingitems (id VARCHAR(32) PRIMARY KEY NOT NULL, user VARCHAR(32) not null,periodestart INT not null,periodeend INT,recurence TEXT,description TEXT not null,amount FLOAT not null,dc CHARACTER(2), deleted TINYINT )");
        
        $errors = $this->connection->errorCode();

        if($result === false || (!empty($errors) && $errors != "00000")){
            error_log(sprintf('Error when creating datababase structure %s',$errors));
        }
    }
    public function getAccountStore(){
        if($this->accountStore == null){
            $this->accountStore = new AccountDbMapper($this->connection);
        }
        return $this->accountStore;
    }

    public function getBookingItemsStore(){
        if($this->bookingItemsStore == null){
            $this->bookingItemsStore = new BookingItemsDbMapper($this->connection);
        }
        return $this->bookingItemsStore;
    }

    public function getCategoriesStore(){
        if($this->categoriesStore == null){
            $this->categoriesStore = new CategoriesDbMapper($this->connection);
        }
        return $this->categoriesStore;
    }

    public function getTokenStore(){
        if($this->tokenStore == null){
            $this->tokenStore = new TokenDbMapper($this->connection);
        }
        return $this->tokenStore;
    }

    public function getUserStore(){
        if($this->userTableHandler == null){
            $this->userTableHandler = new UserDbMapper($this->connection);
        }
        return $this->userTableHandler;
    }
    
    public static function getInstance(){
        if(self::$instance == null){
            self::$instance = new DatabaseHandler();
        }
        return self::$instance;
    }

}