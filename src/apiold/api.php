<?php
require_once('RequestDispatcher.php');
class ApiHandler{

    public function __construct(){
        
        $this->onInitialize();
        $this->dispatchRequest();
    }

    private function onInitialize(){
        $this->initStores();
        
    }

    private function initStores(){}
    private function dispatchRequest(){
        return RequestDispatcher::dispatch();
    }

}

$handler = new ApiHandler();
