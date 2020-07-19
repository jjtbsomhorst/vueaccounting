<?php
require_once('AbstractDbMapper.php');
class CategoriesDbMapper extends AbstractDbMapper{

    public function getCategories($account){
        $result = $this->connection->prepare("SELECT * from categories where deleted = 0")->execute([]);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function addCategory($account,$id,$description){
        $this->connection->prepare("INSERT INTO categories(id,account,name,deleted) values(?,?,?,?)")->execute([$id,$account,$description,0]);
    }
    public function deleteCategory($account,$id){
        $this->connection->prepare("UPDATE categories set deleted = 1 where id = ?)")->execute([$id]);
    }

}