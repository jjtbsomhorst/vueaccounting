<?php

abstract class AbstractDbMapper{
    protected $connection = null;
    public function __construct($pdoConnection){
        $this->connection = $pdoConnection;
    }

    protected function executeQuery($query, $arguments){
        $stmt = $this->connection->prepare($query);
        if($stmt === false){
            $error = $this->connection->errorInfo();
            error_log(print_r($error));
            throw new Exception("Error while creating statement");
        }

        $result = $stmt->execute($arguments);
        if($result === false){
            $error = $this->connection->errorInfo();
            error_log(print_r($error));
            throw new Exception("Error while executing statement");
        }

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }    
}