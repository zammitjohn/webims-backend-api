<?php
class Inventory_Categories{
 
    // database connection and table name
    private $conn;
    private $table_name = "inventory_categories";
 
    // object properties
    public $id;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read categories
    function read(){
    
        // select query
        $query = "SELECT inventory_categories.id, inventory_categories.name,
                    IF((SELECT COUNT(*) FROM inventory_types 
                    WHERE (inventory_types.import_name IS NULL) AND (inventory_types.type_category = inventory_categories.id)),'0','1') 
                    AS supportImport
                FROM
                    " . $this->table_name;

        // select query for particular category
        if (!(is_null($this->id))){
            $query .= "
                WHERE
                    inventory_categories.id = '".$this->id."'";                
        }

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

   
}