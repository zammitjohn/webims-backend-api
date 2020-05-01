<?php
class Spares{
 
    // database connection and table name
    private $conn;
    private $table_name = "spares";
 
    // object properties
    public $id;
    public $inventoryId;
    public $type;
    public $name;
    public $description;
    public $qty;
    public $notes;
    public $sessionId;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read spares
    function read(){

        if(!($this->keyExists())){
            return false;
        }    
    
        // different SQL query according to API call
        if (is_null($this->type)){
             // select all query
            $query = "SELECT
                        *
                    FROM
                        " . $this->table_name . " 
                    ORDER BY
                        id DESC";
 
        } else {
            // select all query for particular type
            $query = "SELECT
                        *
                    FROM
                        " . $this->table_name . "
                    WHERE
                        type= '".$this->type."' 
                    ORDER BY 
                        id DESC";
        }

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
    
		// check if name already exists
        if($this->isAlreadyExist()){
            return false;
        }
        
        // query to insert record
        $query = "INSERT INTO  ". $this->table_name ." 
                        (`inventoryId`, `type`, `name`, `description`, `qty`, `notes`)
                VALUES
                        ($this->inventoryId, '".$this->type."', '".$this->name."', '".$this->description."', '".$this->qty."', '".$this->notes."')";

        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // execute query
        if($stmt->execute()){
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
    
        // query to update record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    inventoryId=$this->inventoryId, type='".$this->type."', name='".$this->name."', description='".$this->description."', qty='".$this->qty."', notes='".$this->notes."'
                WHERE
                    id='".$this->id."'";            

        // prepare query
        $stmt = $this->conn->prepare($query);
        // execute query
        if($stmt->execute()){
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

    function isAlreadyExist(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                name='".$this->name."'";

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