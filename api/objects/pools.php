<?php
class Pools{
 
    // database connection and table name
    private $conn;
    private $table_name = "pools";
 
    // object properties
    public $id;
    public $inventoryId;
    public $type;
    public $pool;
    public $name;
    public $description;
    public $qtyOrdered;
    public $qtyStock;
    public $notes;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read pools
    function read(){
    
        // select query for particular type and pool
        $query = "SELECT 
			        pools.id, pools_types.id AS tech_id, pools_types.name AS tech_name, pools.pool, pools.name, pools.description, pools.qtyOrdered, pools.qtyStock, pools.notes
                FROM 
                    " . $this->table_name . " JOIN pools_types
                ON 
                    pools.type = pools_types.id
                WHERE
                    pools_types.id= '".$this->type."' AND pools.pool= '".$this->pool."'
		        ORDER BY 
			        `pools`.`id`  DESC";          

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
                    inventoryId=:inventoryId, type=:type, pool=:pool, name=:name, description=:description, 
                    qtyOrdered=:qtyOrdered, qtyStock=:qtyStock, notes=:notes";

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
                    inventoryId=:inventoryId, type=:type, pool=:pool, name=:name, description=:description, 
                    qtyOrdered=:qtyOrdered, qtyStock=:qtyStock, notes=:notes            
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
        if ($this->pool == ""){
            $stmt->bindValue(':pool', $this->pool, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':pool', $this->pool);
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
        if ($this->qtyOrdered == ""){
            $stmt->bindValue(':qtyOrdered', $this->qtyOrdered, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':qtyOrdered', $this->qtyOrdered);
        }
        if ($this->qtyStock == ""){
            $stmt->bindValue(':qtyStock', $this->qtyStock, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':qtyStock', $this->qtyStock);
        }
        if ($this->notes == ""){
            $stmt->bindValue(':notes', $this->notes, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':notes', $this->notes);
        }
        return $stmt;
    }
    
}