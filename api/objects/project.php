<?php
// include object files
include_once 'base.php';

class project extends base{
 
    // database table name
    protected $table_name = "project";
 
    // object properties
    public $name;

    // read project types
    function read(){
    
        // different SQL query according to API call
        if ($this->id) {
            // select all query for particular id
            $query = "SELECT
                        *
                    FROM
                        " . $this->table_name . "
                    WHERE
                        id= '".$this->id."'";            

        } elseif ($this->userId) {
            // select all query for particular userId
            $query = "SELECT
                        *
                    FROM
                        " . $this->table_name . "
                    WHERE
                        userId= '".$this->userId."'";            
 
        } else {
             // select all query
            $query = "SELECT
                        *
                    FROM
                        " . $this->table_name;
        }

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    // create
    function create(){ 
        
        // query to insert record
        $query = "INSERT INTO  
                    ". $this->table_name ."
                SET
                    name=:name, userId=:userId";                        

        // prepare and bind query
        $stmt = $this->conn->prepare($query);
        $stmt = $this->bindValues($stmt);
    
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $this->id = $this->conn->lastInsertId();
            $this->logging(null);
            return true;
        }

        return false;
    }

    private function bindValues($stmt){
        if ($this->name == ""){
            $stmt->bindValue(':name', $this->name, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':name', $this->name);
        }
        if ($this->userId == ""){
            $stmt->bindValue(':userId', $this->userId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':userId', $this->userId);
        }
        return $stmt;
    }
}