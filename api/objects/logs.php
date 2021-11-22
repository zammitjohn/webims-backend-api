<?php
// include object files
include_once 'base.php';

class Logs extends base{
 
    // database table name
    protected $table_name = "logs";
 
    // object properties
    public $action;
 
    // new log
    function new(){
        // query to insert record
        $query = "INSERT INTO  
                    ". $this->table_name ."
                SET
                    properties=:properties, action=:action";
    
 
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
        if ($this->properties == ""){
            $stmt->bindValue(':properties', $this->properties, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':properties', $this->properties);
        }
        if ($this->action == ""){
            $stmt->bindValue(':action', $this->action, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':action', $this->action);
        }     
        return $stmt;
    }   
}