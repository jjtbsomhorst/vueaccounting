<?php
/**
 * Handles all the apiold calls for accounts.
 * CRUD on accounts and entries /accounts/? , /accounts/?/entries , /accounts/?/entries/?
  * 
 */

require_once('AbstractDispatcher.php');
require_once('TokenUtil.php');
class AccountDispatcher extends AbstractDispatcher{

    private $store = null;

    public function __construct(){
        parent::__construct();
        $this->store = $this->dbHandler->getAccountStore();
    }

    public function doPost($request,$body){
        $decodedBody = json_decode($body,true);
        if($request[0] === 'accounts'){ // create a new account
            if($request[1] === 'create'){
                if(!empty($decodedBody) && array_key_exists('name',$decodedBody) && array_key_exists('number',$decodedBody)){
                    $id = TokenUtils::guidv4();
                    $this->store->createAccount($id,$decodedBody['name'],$decodedBody['number'],$this->getUserId());
                    $account = $this->store->getAccount($id);
                    echo json_encode($account);
                }else{
                    parent::returnError("error","Invalid parameters");
                }  
            }
            //accounts/?/entries/create
            if(count($request) === 4 && $request[0] == 'accounts' && $request[2] == 'entries' && $request[3] == 'create'){
                if($this->hasAccount($request[1]) && $this->isValidEntryBody(array_merge(['account'=>$request[1]],$decodedBody))){
                    $entryId = TokenUtils::guidv4();
                    $this->store->addEntry($request[1],$entryId,$decodedBody['date'],$decodedBody['description'],$decodedBody['dc'],$decodedBody['amount'],$decodedBody['period']);
                    echo json_encode($this->store->getEntry($entryId));
                }
            }
        }
    }

    public function doPut($request,$body){
        $decodedBody = json_decode($body,true);
        
        // update account request
        if($request[0] == 'account' && count($request) == 2 && $this->isValidAccountBody($decodedBody,true) && $request[1] === $body['id']){
            $this->store->updateAccount($body['id'],$body['name'],$body['number']);
            echo json_encode($this->store->getAccount($body['id']));
        }
        // update entry request
        if($request[0] == 'entry' && count($request) == 2 && $this->isValidEntryBody($decodedBody,true)){
            $this->store->updateEntry($body['id'],$body['date'],$body['description'],$body['dc'],$body['amount'],$body['period']);
            echo json_encode($this->store->getEntry($body['id']));
        }
    }

    private function hasAccount($id){
        $accounts = $this->getAccounts();
        foreach($accounts as $acc){
            if($acc["id"] == $id){
                return true;
            }
        }
        return false;
    }

    public function doDelete($request){
        if(count($request) == 2 && $request[0] == 'accounts'){
            $this->store->deleteAccount($request[1],$this->getUserId());    
        } 
        if(count($request) == 4){
            $accounts = $this->getAccounts();
            $found = false;
            foreach($acounts as $account){
                if($account['id'] == $request[2] ){
                    $found =true;
                    return;
                }
            }
            if($found){
                $this->store->deleteEntry($request[2],$request[3]);
            }

        }
    }


    /**
     * This method retrieves entries or accounts for a specific user
     * urls to handle:
     *  /accounts
     *  /accounts/?/entries?....
     * Params to use are : 
     *  ?period = which period the entries should be belong to
     *  ?startDate = from which date on we need to retrieve entries;
     *  ?enddate = until which date we should retrieve entries
     */
    public function doGet($request,$params){
        parent::doGet($request,$params);
        if(count($request) == 1 && $request[0] == 'accounts'){
            echo json_encode($this->getAccounts());
        }else if(count($request)==3){
            
            if($request[2] != 'entries'){
                parent::returnError("error","invalid parameter");
                return;
            }
            
            $accountId = $request[1];
            if(!$this->hasAccount($accountId)){
                return parent::returnError("error","Given account does not exist");
            }

            $periodeStart = $params['periodeStart'] || null;
            $periodeEnd = $params['periodeEnd'] || null;
            $dc = $params['dc'] || null;
            $period = $params['period'] || null;

            $entries = $this->store->searchEntries($accountId,$periodeStart,$periodeEnd,$dc,$period);            
            echo json_encode($entries);
        }
    }

    private function getAccounts(){
        $token = parent::getOauthTokenFromRequest();
        $currentUser = TokenUtils::getInstance()->getUserIdForToken($token);
        $accounts = $this->store->getAccounts($currentUser);
        return $accounts;
    }
    private function isValidAccountBody($args,$isUpdate = false){
        $requiredKeys = ['name','number','userid'];
        if($isUpdate){
            $requiredKeys = array_merge(['id'],$requiredKeys);
        }
        $isValid = (count(array_diff($requiredKeys, array_keys($args))) == 0);
        if(!$isValid){
            error_log('invalid account request');
            error_log(print_r($args));
        }
        return $isValid;
    }
    private function isValidEntryBody($args, $isUpdate = false){
        $requiredKeys = ['date','description','amount','dc','account','period'];
        if($isUpdate){
            $requiredKeys[] = 'id';
        }
        $isValid = (count(array_diff($requiredKeys, array_keys($args))) == 0);
        if(!$isValid){
            error_log('invalid entry stuffs');
            error_log(print_r($args));
        }
        return $isValid;
    }

}

