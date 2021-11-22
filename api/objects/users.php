<?php
// include object files
include_once 'base.php';

class Users extends base{
 
    // database table name
    protected $table_name = "users";
 
    // object properties
    public $username;
    public $firstname;
    public $lastname;
    public $sessionId;
    // actions
    public $action_isCreate;
    public $action_isUpdate;
    public $action_isDelete;
    public $action_isImport;

    // log out user
    function logout(){

        // query to update record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    sessionId=NULL
                WHERE
                    sessionId='".$this->sessionId."'"; 
        
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
                    `id`, `firstname`, `lastname`, `lastAvailable`
                FROM
                    " . $this->table_name . "
                WHERE    
                    lastAvailable >= DATE_SUB(NOW(),INTERVAL 1 YEAR)
                ORDER BY
                    lastAvailable DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // validate session parameters
    function validateSession(){

        // update lastAvailable
        $query = "UPDATE
                    ".$this->table_name." 
                SET
                    lastAvailable = now() 
                WHERE
                    sessionId='".$this->sessionId."'";   

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();   
    
        // select query
        $query = "SELECT 
                    `firstname`, `lastname`, `canUpdate`, `canCreate`, `canImport`, `canDelete`
                FROM
                    " . $this->table_name . " 
                WHERE
                    sessionId='".$this->sessionId."'";
    
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
        $this->createSession();
        
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

    private function createSession(){
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

    // action check
    function validAction(){
        if (isset($this->action_isCreate)) {
            $query = "SELECT *
                    FROM
                        " . $this->table_name . " 
                    WHERE
                        sessionId='".$this->sessionId."'
                    AND 
                        canCreate='".$this->action_isCreate."'";

        } elseif (isset($this->action_isUpdate)) {
            $query = "SELECT *
                    FROM
                        " . $this->table_name . " 
                    WHERE
                        sessionId='".$this->sessionId."'
                    AND 
                        canUpdate='".$this->action_isUpdate."'";

        } elseif (isset($this->action_isDelete)) {
            $query = "SELECT *
                    FROM
                        " . $this->table_name . " 
                    WHERE
                        sessionId='".$this->sessionId."'
                    AND 
                        canDelete='".$this->action_isDelete."'";

        } elseif (isset($this->action_isImport)) {
            $query = "SELECT *
                    FROM
                        " . $this->table_name . " 
                    WHERE
                        sessionId='".$this->sessionId."'
                    AND 
                        canImport='".$this->action_isImport."'";          

        } else { //read action, no check required
            $query = "SELECT *
                    FROM
                        " . $this->table_name . " 
                    WHERE
                        sessionId='".$this->sessionId."'";
        }
    
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

    private function checkifUserExists(){
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

    function getUserId(){
        $query = "SELECT id
        FROM
            " . $this->table_name . " 
        WHERE
            sessionId='".$this->sessionId."'";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id'] ?? null; // return item id of matching user, null if undefined
    }

}