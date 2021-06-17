<?php
class Transactions_Items{
 
    // database connection and table name
    private $conn;
    private $table_name = "transactions_items";
 
    // object properties
    public $transactionId;
    public $inventoryId;
    public $qty;
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // create new transaction item
    function create(){     
        // query to insert record
        $query = "INSERT INTO
                    ". $this->table_name ." 
                SET
                    inventoryId=:inventoryId, transactionId=:transactionId, qty=:qty"; 
    
        // prepare and bind query
        $stmt = $this->conn->prepare($query);
        $stmt = $this->bindValues($stmt);
    
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return true;
        }
        return false;
    }

    // dump all items under transaction
    function dumpTransactionItems(){
        // select query
        $query = "SELECT transactions.date, inventory_types.import_name AS type_altname, inventory.SKU, inventory.description, CONCAT(users.firstname, ' ', users.lastname) AS user_fullname, inventory.category, transactions_items.qty
        FROM ". $this->table_name ."
            JOIN inventory
            ON transactions_items.inventoryId = inventory.id
            JOIN transactions
            ON transactions_items.transactionId = transactions.id
            JOIN users
            ON transactions.userId = users.id
            JOIN inventory_types
            ON inventory.type = inventory_types.id
        ORDER BY transactions_items.id DESC";             

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    function bindValues($stmt){
        if ($this->inventoryId == ""){
            $stmt->bindValue(':inventoryId', $this->inventoryId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':inventoryId', $this->inventoryId);
        }
        if ($this->transactionId == ""){
            $stmt->bindValue(':transactionId', $this->transactionId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':transactionId', $this->transactionId);
        }
        if ($this->qty == ""){
            $stmt->bindValue(':qty', $this->qty, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':qty', $this->qty);
        }
        return $stmt;
    }
}