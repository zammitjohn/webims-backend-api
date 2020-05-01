<?php
class Registry{
 
    // database connection and table name
    private $conn;
    private $table_name = "registry";
 
    // object properties
    public $id;
    public $inventoryId;
    public $serialNumber;
    public $datePurchased;
    public $sessionId;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // list registry items with particular 'inventoryId'
    function read(){

        if(!($this->keyExists())){
            return false;
        }             
    
        // select all query
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . " 
                WHERE
                    inventoryId= '".$this->inventoryId."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    // create item
    function create(){     
        
        if(!($this->keyExists())){
            return false;
        }             

        if($this->isAlreadyExist()){
            return false;
        }

        // query to insert record
        $query = "INSERT INTO  ". $this->table_name ." 
                        (`inventoryId`, `serialNumber`, `datePurchased`)
                  VALUES
                        ('".$this->inventoryId."', '".$this->serialNumber."', '".$this->datePurchased."')";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    // delete item
    function delete(){

        if(!($this->keyExists())){
            return false;
        }             
        
        // query to delete record
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                    id= '".$this->id."'";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    // check item with same inventoryId and serialNumber already exist
    function isAlreadyExist(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                inventoryId='".$this->inventoryId."' AND serialNumber='".$this->serialNumber."'"; 

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        if($stmt->rowCount() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    function keyExists(){
        $query = "SELECT *
            FROM
                users 
            WHERE
                sessionId='".$this->sessionId."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return true;
        }
        else{
            return false;
        }        
    }
}