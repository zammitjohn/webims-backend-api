<?php
// include object files
include_once 'base.php';

class warehouse_category extends base{
 
    // database table name
    protected $table_name = "warehouse_category";
 
    // object properties
    public $warehouseId;

    // read types
    function read(){
        
        // select query
        $query = "SELECT 
            warehouse_category.id, warehouse_category.warehouseId, warehouse.name AS warehouse_name, 
            warehouse_category.name, warehouse_category.importName
            
            FROM
                " . $this->table_name . "

                JOIN
                    warehouse
                ON
                    warehouse_category.warehouseId = warehouse.id";

        // different SQL query according to API call
        if ($this->id) {
            // select query for particular id
            $query .= "
                    WHERE
                        warehouse_category.id = '".$this->id."'";
        } elseif ($this->warehouseId) {
            // select query for particular warehouseId
            $query .= "
                    WHERE
                        warehouse_category.warehouseId= '".$this->warehouseId."'";
        }

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    // load all categories importName for particular warehouse
    function loadCategories($warehouseId){
        $this->warehouseId = $warehouseId;
        $warehouse_category_stmt  = $this->read();
        while ($warehouse_category_row = $warehouse_category_stmt->fetch(PDO::FETCH_ASSOC)) { // ...then loop types and add to array
          extract($warehouse_category_row);
          $warehouse_category[$id] = strtoupper($importName);
        }
        return $warehouse_category;
    }
}