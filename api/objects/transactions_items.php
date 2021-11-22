<?php
// include object files
include_once 'base.php';

class Transactions_Items extends base{
 
    // database table name
    protected $table_name = "transactions_items";
 
    // object properties
    public $transactionId;
    public $inventoryId;
    public $qty;

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

    // read all transaction ids based on the items in the transactions 
    function read_all(){
        // select query
        $query = "SELECT DISTINCT transactions_items.transactionId AS id, transactions.date, IF(isReturn = '1', 'Return of equipment', 'Issue of equipment') AS description, CONCAT(users.firstname, ' ', users.lastname) AS user_fullname
        FROM 
            " . $this->table_name . " 

            LEFT JOIN 
                transactions
            ON 
                transactions_items.transactionId = transactions.id 
            LEFT JOIN 
                users
            ON 
                transactions.userId = users.id               
            ORDER BY 
                transactions_items.transactionId DESC;";             

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
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
        WHERE transactions_items.transactionId = ". $this->transactionId ."
        ORDER BY transactions_items.id DESC";             

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    private function bindValues($stmt){
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