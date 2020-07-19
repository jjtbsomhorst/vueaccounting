<?php

interface IDispatcher{
    public function dispatch($method = "GET", $request = array());
    public function doGet($request,$params);
    public function doPost($request,$body);
    public function doPut($request,$body);
    public function doDelete($request);
}