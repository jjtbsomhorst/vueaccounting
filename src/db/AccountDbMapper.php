<?php
require_once('AbstractDbMapper.php');

class AccountDbMapper extends AbstractDbMapper{

    public function getAccount($id){
        $rows = $this->executeQuery("SELECT * FROM accounts where id = ?",[$id]);
        return $rows[0];
    }
    public function getAccounts($user){
        $rows = $this->executeQuery("SELECT * FROM accounts where userid = ? and deleted = ?",[$user,0]);
        return $rows;        
    }
    

    public function getEntry($entryId){
        $entry = $this->executeQuery("SELECT * FROM entries where id = ? and deleted = 0",[$entryid]);
        return $entry;
    }

    public function createAccount($id,$description,$number,$userid){
        //create the account
        $this->executeQuery("INSERT INTO accounts(id,name,accountnumber,userid,deleted) values(?,?,?,?,?)",[$id,$description,$number,$userid,0]);
        
    }

    public function updateAccount($id,$name,$number){
        $this->executeQuery("UPDATE accounts set name = ?, accountnumber=? where id = ? and deleted = 0",[$name,$number]);
    }

    public function updateEntry($id,$date,$description,$dc,$amount,$period){
        $this->executeQuery("UPDATE entries set description = ?, entrydate=?,amount=?,dc=?,period=? where id = ? and deleted = 0",[$description,$date,$amount,$dc,$period,$id]);
    }

    public function addEntry($accountid, $entryid,$entrydate,$description,$dc,$amount,$period){
        $this->executeQuery("INSERT INTO entries(id,account,entrydate,description,amount,dc,period,deleted) values(?,?,?,?,?,?,?,?)",[$entryid,$accountid,$entrydate,$description,$amount,$dc,$period,0]);
        return $entryid;
    }

    public function deleteAccount($id,$userid){
        // delete account first
        $this->executeQuery("UPDATE accounts set deleted = 1 where id = ? and userid=?",[$id,$userid]);
        // delete entries next.
        $this->executeQuery("UPDATE entries set deleted = 1 where account = ?",[$id]);

        return true;
    }

    public function searchEntries($accountid,$periodStart = null, $periodEnd = null,$dc=null,$bookingPeriod = null){
        $query = "SELECT * FROM entries ";
        $arguments[] = $accountid;
        $query .= "WHERE deleted = 0";
        $query .= " AND account = ?";

        if(!empty($periodStart)){
            $query .= " AND entrydate >= ?";
            $arguments[] = $periodStart;
        }
        if(!empty($periodEnd)){
            $query .=" AND entrydate <= ?";
            $arguments[] = $periodEnd;
        }

        if(!empty($dc)){
            $query .= " AND dc = ?";
            $arguments[] = $dc;
        }

        if(!empty($bookingPeriod)){
            $query .= " AND bookingperiode = ?";
            $arguments[] = $bookingPeriod;
        }
        $rows = $this->executeQuery($query,$arguments);
        return $rows;
    }

    public function deleteEntry($accountid, $entryid){
        $this->executeQuery("UPDATE entries set deleted = 1 where account = ? and id = ?",[$accountid,$entryid]);
    }

}