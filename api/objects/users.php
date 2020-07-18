<?php
class Users{
 
    // database connection and table name
    private $conn;
    private $table_name = "users";
 
    // object properties
    public $id;
    public $username;
    public $firstname;
    public $lastname;
    public $created;
    public $sessionId;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // delete user
    function delete(){

        // query to delete record
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                    id= '".$this->id."' AND sessionId='".$this->sessionId."'";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    // read all users
    function read(){
    
        // select all query
        $query = "SELECT
                    `id`, `firstname`, `lastname`
                FROM
                    " . $this->table_name . " 
                ORDER BY
                    id DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    
    // login user
    function login(){

        if(!($this->checkifUserExists())){  // user does not exist in database

            $query = "INSERT INTO
                        ". $this->table_name ." 
                        (`username`, `firstname`, `lastname`)
                VALUES
                        ('".$this->username."', '".$this->firstname."', '".$this->lastname."')";  

            // prepare query
            $stmt = $this->conn->prepare($query);
            
            // execute query
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $this->id = $this->conn->lastInsertId();
            } else {
                return false; // failed to create new user
            }
        }

        // create sessionId for user
        $this->createSessionId();
        
        $query = "SELECT
                    `id`, `username`, `firstname`, `lastname`, `created` , `sessionId`
                FROM
                    " . $this->table_name . " 
                WHERE
                    username='".$this->username."'";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $stmt;
        } else {
            return false; // failed to login user
        }        
    }

    function createSessionId(){
        // generates cryptographically secure pseudo-random UUID hash, used as a sessionId
        $bytes = random_bytes(10);
        $hash = (bin2hex($bytes));

        $query = "UPDATE
                    ".$this->table_name." 
                SET
                    sessionId = '".$hash."'
                WHERE
                    username = '".$this->username."'";       

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();                    
    }

    function validKey(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                sessionId='".$this->sessionId."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return true;
        }
        else{
            return false;
        }        
    }    

    function checkifUserExists(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                username='".$this->username."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return true;
        }
        else{
            return false;
        }        
    }

}