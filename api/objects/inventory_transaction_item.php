<?php
// include object files
include_once 'base.php';

class inventory_transaction_item extends base{
 
    // database table name
    protected $table_name = "inventory_transaction_item";
 
    // object properties
    public $inventory_transactionId;
    public $inventoryId;
    public $qty;

    // create new transaction item
    function create(){     
        // query to insert record
        $query = "INSERT INTO
                    ". $this->table_name ." 
                SET
                    inventoryId=:inventoryId, inventory_transactionId=:inventory_transactionId, qty=:qty"; 
    
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

    // read all transaction ids based on the items in the inventory_transaction 
    function read_all(){
        // select query
        $query = "SELECT DISTINCT inventory_transaction_item.inventory_transactionId AS id, inventory_transaction.date, IF(isReturn = '1', 'Return of equipment', 'Issue of equipment') AS description, CONCAT(user.firstname, ' ', user.lastname) AS user_fullname
        FROM 
            " . $this->table_name . " 

            LEFT JOIN 
                inventory_transaction
            ON 
                inventory_transaction_item.inventory_transactionId = inventory_transaction.id 
            LEFT JOIN 
                user
            ON 
                inventory_transaction.userId = user.id               
            ORDER BY 
                inventory_transaction_item.inventory_transactionId DESC;";             

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    // dump all items under transaction
    function dumpTransactionItems(){
        // select query
        $query = "SELECT inventory_transaction.date, warehouse_category.importName AS warehouse_category_importName, inventory.SKU, inventory.description, 
        CONCAT(user.firstName, ' ', user.lastName) AS user_fullName, inventory_transaction_item.qty,
        warehouse_category.name AS warehouse_category_name

        FROM ". $this->table_name ."
            JOIN inventory
            ON inventory_transaction_item.inventoryId = inventory.id

            JOIN warehouse_category
            ON inventory.warehouse_categoryId = warehouse_category.id

            JOIN inventory_transaction
            ON inventory_transaction_item.inventory_transactionId = inventory_transaction.id

            JOIN user
            ON inventory_transaction.userId = user.id

        WHERE inventory_transaction_item.inventory_transactionId = ". $this->inventory_transactionId ."
        ORDER BY inventory_transaction_item.id DESC";             

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
        if ($this->inventory_transactionId == ""){
            $stmt->bindValue(':inventory_transactionId', $this->inventory_transactionId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':inventory_transactionId', $this->inventory_transactionId);
        }
        if ($this->qty == ""){
            $stmt->bindValue(':qty', $this->qty, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':qty', $this->qty);
        }
        return $stmt;
    }
}