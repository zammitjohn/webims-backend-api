<?php
class Inventory_Types{
 
    // database connection and table name
    private $conn;
    private $table_name = "inventory_types";
 
    // object properties
    public $id;
    public $category;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read spares
    function read(){
        
        // select query
        $query = "SELECT 
            inventory_types.id, inventory_types.type_category, inventory_categories.name AS category_name, 
            inventory_types.name, inventory_types.alt_name
            
            FROM
                " . $this->table_name . "

                JOIN
                    inventory_categories
                ON
                    inventory_types.type_category = inventory_categories.id";

        // different SQL query according to API call
        if ($this->id) {
            // select query for particular type
            $query .= "
                    WHERE
                        inventory_types.id = '".$this->id."'";
        } elseif ($this->category) {
            // select query for particular category
            $query .= "
                    WHERE
                        inventory_types.type_category= '".$this->category."'";
        }

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

   
}