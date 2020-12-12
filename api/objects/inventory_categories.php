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
    
        // different SQL query according to API call
        if (is_null($this->id)){
             // select all query
            $query = "SELECT
                        *
                    FROM
                        " . $this->table_name;
 
        } else {
            // select all query for particular type
            $query = "SELECT
                        *
                    FROM
                        " . $this->table_name . "
                    WHERE
                        id= '".$this->id."'";
        }

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

   
}