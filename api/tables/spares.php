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
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read spares
    function read(){
    
        // different SQL query according to API call
        if (is_null($this->type)){
             // select query
            $query = "SELECT 
                        spares.id, spares.name, spares_types.id AS type_id, spares_types.name AS type_name, spares.description, spares.qty, spares.notes
                    FROM 
                    " . $this->table_name . " JOIN spares_types
                    ON 
                        spares.type = spares_types.id
                    ORDER BY 
                        `spares`.`id`  DESC";                          

        } else {
            // select query for particular type
            $query = "SELECT 
                        spares.id, spares.name, spares_types.id AS type_id, spares_types.name AS type_name, spares.description, spares.qty, spares.notes
                    FROM 
                    " . $this->table_name . " JOIN spares_types
                    ON 
                        spares.type = spares_types.id
                    WHERE
			            spares_types.id= '".$this->type."'                        
                    ORDER BY 
                        `spares`.`id`  DESC";   
        }

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    // get single item data
    function read_single(){ 
    
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
        
        // query to insert record
        $query = "INSERT INTO  
                    ". $this->table_name ."
                SET
                    inventoryId=:inventoryId, type=:type, name=:name, description=:description, qty=:qty, 
                    notes=:notes";                        

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

    // update item 
    function update(){
    
        // query to update record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    inventoryId=:inventoryId, type=:type, name=:name, description=:description, qty=:qty, 
                    notes=:notes                          
                WHERE
                    id='".$this->id."'";            

        // prepare and bind query
        $stmt = $this->conn->prepare($query);
        $stmt = $this->bindValues($stmt);

        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
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

    function bindValues($stmt){
        if ($this->inventoryId == ""){
            $stmt->bindValue(':inventoryId', $this->inventoryId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':inventoryId', $this->inventoryId);
        }
        if ($this->type == ""){
            $stmt->bindValue(':type', $this->type, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':type', $this->type);
        }
        if ($this->name == ""){
            $stmt->bindValue(':name', $this->name, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':name', $this->name);
        }
        if ($this->description == ""){
            $stmt->bindValue(':description', $this->description, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':description', $this->description);
        }                   
        if ($this->qty == ""){
            $stmt->bindValue(':qty', $this->qty, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':qty', $this->qty);
        }        
        if ($this->notes == ""){
            $stmt->bindValue(':notes', $this->notes, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':notes', $this->notes);
        }
        return $stmt;
    }
    
}