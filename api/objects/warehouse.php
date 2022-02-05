<?php
// include object files
include_once 'base.php';

class warehouse extends base{
 
    // database table name
    protected $table_name = "warehouse";

    // read warehouse
    function read(){
    
        // select query
        $query = "SELECT warehouse.id, warehouse.name,
                    IF((SELECT COUNT(*) FROM warehouse_category 
                    WHERE (warehouse_category.importName IS NULL) AND (warehouse_category.warehouseId = warehouse.id)),'0','1') 
                    AS supportImport
                FROM
                    " . $this->table_name;

        // select query for particular category
        if (!(is_null($this->id))){
            $query .= "
                WHERE
                    warehouse.id = '".$this->id."'";                
        }

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

   
}