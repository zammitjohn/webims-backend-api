<?php
// include object files
include_once 'base.php';

class Inventory_Types extends base{
 
    // database table name
    protected $table_name = "inventory_types";
 
    // object properties
    public $category;

    // read types
    function read(){
        
        // select query
        $query = "SELECT 
            inventory_types.id, inventory_types.type_category, inventory_categories.name AS category_name, 
            inventory_types.name, inventory_types.import_name
            
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

    // load types all import_name for particular category
    function loadTypes($import_category){
        $this->category = $import_category;
        $inventory_types_stmt  = $this->read();
        while ($inventory_types_row = $inventory_types_stmt->fetch(PDO::FETCH_ASSOC)) { // ...then loop types and add to array
          extract($inventory_types_row);
          $inventory_types[$id] = strtoupper($import_name);
        }
        return $inventory_types;
    }
}