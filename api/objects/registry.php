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
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // list registry items with particular 'inventoryId'
    function read(){
    
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

        if($this->isAlreadyExist()){
            return false;
        }

        // query to insert record
        $query = "INSERT INTO
                    ". $this->table_name ." 
                SET
                    inventoryId=:inventoryId, serialNumber=:serialNumber, datePurchased=:datePurchased"; 
    
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

    // delete item
    function delete(){
        
        // query to delete record
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                    id= '".$this->id."'";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
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

    function bindValues($stmt){
        if ($this->inventoryId == ""){
            $stmt->bindValue(':inventoryId', $this->inventoryId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':inventoryId', $this->inventoryId);
        }
        if ($this->serialNumber == ""){
            $stmt->bindValue(':serialNumber', $this->serialNumber, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':serialNumber', $this->serialNumber);
        }
        if ($this->datePurchased == ""){
            $stmt->bindValue(':datePurchased', $this->datePurchased, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':datePurchased', $this->datePurchased);
        }
        return $stmt;
    }    

}