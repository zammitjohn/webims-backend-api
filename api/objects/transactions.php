<?php
class Transactions{
 
    // database connection and table name
    private $conn;
    private $table_name = "transactions";
 
    // object properties
    public $userId;
    public $isReturn;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read all transactions
    function read(){
        // select query
        $query = "SELECT 
            transactions.id, transactions.date, IF(isReturn = '1', 'Return of equipment', 'Issue of equipment') AS description, CONCAT(users.firstname, ' ', users.lastname) AS user_fullname
        FROM 
            " . $this->table_name . " 

            LEFT JOIN 
                users
            ON 
                transactions.userId = users.id               
            ORDER BY 
                transactions.id DESC";             

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    // create transaction
    function create(){
        if ($this->isReturn){
            $query = "INSERT INTO
                        ". $this->table_name ." 
                            (`userId`, `isReturn`)
                        VALUES
                            ('".$this->userId."', '1')";
        } else {
            $query = "INSERT INTO
                        ". $this->table_name ." 
                            (`userId`, `isReturn`)
                        VALUES
                            ('".$this->userId."', '0')";            
        }
        
        // prepare and bind query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }
    
}