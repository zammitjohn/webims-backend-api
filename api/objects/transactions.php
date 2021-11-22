<?php
// include object files
include_once 'base.php';

class Transactions extends base{
 
    // database table name
    protected $table_name = "transactions";
 
    // object properties
    public $isReturn;
 
    // create transaction
    function create(){
        if ($this->isReturn){
            $query = "INSERT INTO
                        ". $this->table_name ." 
                            (`userId`, `isReturn`)
                        VALUES
                            ('".$this->userId."', '1')";
        } else {
            $query = "INSERT INTO
                        ". $this->table_name ." 
                            (`userId`, `isReturn`)
                        VALUES
                            ('".$this->userId."', '0')";            
        }
        
        // prepare and bind query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }
    
}