<?php
// include object files
include_once 'base.php';

class log extends base{
 
    // database table name
    protected $table_name = "log";
 
    // object properties
    public $object;
    public $propertiesBefore;
    public $propertiesAfter;
    public $userId;
 
    // new log
    function new(){
        // query to insert record
        $query = "INSERT INTO  
                    ". $this->table_name ."
                SET
                    object=:object, propertiesBefore=:propertiesBefore, propertiesAfter=:propertiesAfter, userId=:userId";
    
        // prepare and bind query
        $stmt = $this->conn->prepare($query);
        $stmt = $this->bindValues($stmt);
        
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    private function bindValues($stmt){
        if ($this->object == ""){
            $stmt->bindValue(':object', $this->object, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':object', $this->object);
        }
        if ($this->propertiesBefore == "" or $this->propertiesBefore == "false"){
            $stmt->bindValue(':propertiesBefore', $this->propertiesBefore, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':propertiesBefore', $this->propertiesBefore);
        }
        if ($this->propertiesAfter == "" or $this->propertiesAfter == "false"){
            $stmt->bindValue(':propertiesAfter', $this->propertiesAfter, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':propertiesAfter', $this->propertiesAfter);
        }
        if ($this->userId == ""){
            $stmt->bindValue(':userId', $this->userId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':userId', $this->userId);
        }     
        return $stmt;
    }   
}