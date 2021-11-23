<?php
// include object files
include_once 'base.php';

class Logs extends base{
 
    // database table name
    protected $table_name = "logs";
 
    // object properties
    public $object;
    public $properties_before;
    public $properties_after;
    public $userId;
 
    // new log
    function new(){
        // query to insert record
        $query = "INSERT INTO  
                    ". $this->table_name ."
                SET
                    object=:object, properties_before=:properties_before, properties_after=:properties_after, userId=:userId";
    
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
        if ($this->properties_before == "" or $this->properties_before == "false"){
            $stmt->bindValue(':properties_before', $this->properties_before, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':properties_before', $this->properties_before);
        }
        if ($this->properties_after == "" or $this->properties_after == "false"){
            $stmt->bindValue(':properties_after', $this->properties_after, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':properties_after', $this->properties_after);
        }
        if ($this->userId == ""){
            $stmt->bindValue(':userId', $this->userId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':userId', $this->userId);
        }     
        return $stmt;
    }   
}