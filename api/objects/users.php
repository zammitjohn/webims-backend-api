<?php
class Users{
 
    // database connection and table name
    private $conn;
    private $table_name = "users";
 
    // object properties
    public $id;
    public $email;
    public $firstname;
    public $lastname;
    public $password;
    public $sessionId;
    public $created;
    public $password_new;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    // signup user
    function signup(){
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    email=:email, firstname=:firstname, lastname=:lastname, password=:password, created=:created";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->password=htmlspecialchars(strip_tags(password_hash($this->password, PASSWORD_DEFAULT)));
        $this->created=htmlspecialchars(strip_tags($this->created));
    
        // bind values
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":firstname", $this->firstname);
        $stmt->bindParam(":lastname", $this->lastname);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":created", $this->created);
    
        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
    
        return false;
        
    }

    // update user details
    function update_profile(){

        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    email=:email, firstname=:firstname, lastname=:lastname 
                WHERE
                    sessionId='".$this->sessionId."'";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));

        // bind values
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":firstname", $this->firstname);
        $stmt->bindParam(":lastname", $this->lastname);

        // execute query
        if($stmt->execute()){
            return true;
        }
        return false;
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


    // update password
    function update_password(){

        if(!($this->verifyPassword())){
            return false;
        }
        
        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    password=:password_new
                WHERE
                    email='".$this->email."'";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->password_new=htmlspecialchars(strip_tags(password_hash($this->password_new, PASSWORD_DEFAULT)));

        // bind values
        $stmt->bindParam(":password_new", $this->password_new);

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

        if(!($this->keyExists())){
            return false;
        }
    
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

        if(!($this->verifyPassword())){
            return false;
        }
        
        $this->createSessionId();

        $query = "SELECT
                    `id`, `email`, `firstname`, `lastname`, `password`, `created` , `sessionId`
                FROM
                    " . $this->table_name . " 
                WHERE
                    email='".$this->email."'";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
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
                    email = '".$this->email."'";       

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();                    
    }

    function keyExists(){
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


    function verifyPassword(){
        // query to extract password for the given email
        $query = "SELECT *
        FROM
            " . $this->table_name . " 
        WHERE
            email='".$this->email."'";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        if($stmt->rowCount() > 0){
        // fetch result
            $user = $stmt->fetch();
            if (password_verify($this->password, $user['password'])) {
                // password is correct, return true
                return true;
            } else {
                // incorrect password
                return false;
            }
        }
        // email is incorrect
        return false;
    }
}