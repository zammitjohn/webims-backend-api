<?php
class Projects_Types{
 
    // database connection and table name
    private $conn;
    private $table_name = "projects_types";
 
    // object properties
    public $id;
    public $name;
    public $userId;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

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
            // select all query for particular userid
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
                    name=:name, userid=:userId";                        

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

    function bindValues($stmt){
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