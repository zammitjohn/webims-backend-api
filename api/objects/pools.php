<?php
class Pools{
 
    // database connection and table name
    private $conn;
    private $table_name = "pools";
 
    // object properties
    public $id;
    public $inventoryId;
    public $tech;
    public $pool;
    public $name;
    public $description;
    public $qtyOrdered;
    public $qtyStock;
    public $notes;
    public $sessionId;
	
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read pools
    function read(){

        if(!($this->keyExists())){
            return false;
        }        
    
        // select all query for particular tech and pool
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . "
                WHERE
                    tech= '".$this->tech."' AND pool= '".$this->pool."'
                ORDER BY 
                    id DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    // get single item data
    function read_single(){

        if(!($this->keyExists())){
            return false;
        }        
    
        // select all query
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . "
                WHERE
                    id= '".$this->id."'";
    
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
        
        // query to insert record
        $query = "INSERT INTO  ". $this->table_name ." 
                        (`inventoryId`, `tech`, `pool`, `name`, `description`, `qtyOrdered`, `qtyStock`, `notes`)
                VALUES
                        ($this->inventoryId, '".$this->tech."', '".$this->pool."', '".$this->name."', '".$this->description."', '".$this->qtyOrdered."', '".$this->qtyStock."', '".$this->notes."')";

        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // update item 
    function update(){

        if(!($this->keyExists())){
            return false;
        }        
    
        // query to update record, inventoryId = NULL in query if "NULL"
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    inventoryId=$this->inventoryId, tech='".$this->tech."', pool='".$this->pool."', name='".$this->name."', description='".$this->description."', qtyOrdered='".$this->qtyOrdered."', qtyStock='".$this->qtyStock."', notes='".$this->notes."'
                WHERE
                    id='".$this->id."'";
    
    
        // prepare query
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){        
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
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return true;
        }
        return false;
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