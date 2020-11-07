<?php
class Inventory{
 
    // database connection and table name
    private $conn;
    private $table_name = "inventory";
 
    // object properties
    public $id;
    public $SKU;
    public $type;
    public $category;
    public $description;
    public $qty;
    public $qtyIn;
    public $qtyOut;
    public $supplier;
    public $isGSM;
    public $isUMTS;
    public $isLTE;
    public $ancillary;
    public $toCheck;
    public $notes;
    public $inventoryDate;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read all inventory
    function read(){

        // select query
        $query = "SELECT 
            inventory.id, inventory.SKU, inventory_types.id AS type_id, 
            inventory_types.name AS type_name, inventory_types.alt_name AS type_altname, 
            inventory_categories.id AS category_id, inventory_categories.name AS category_name,
            inventory.description, inventory.qty, inventory.qtyIn, inventory.qtyOut,
            inventory.supplier, inventory.inventoryDate
        FROM 
            " . $this->table_name . "
            JOIN 
                inventory_types
            ON 
                inventory.type = inventory_types.id
            JOIN 
                inventory_categories
            ON 
                inventory.category = inventory_categories.id";

        // different SQL query according to API call
        if ($this->type){
           // concatenate select query for particular type
            $query .= "
            WHERE
                inventory.type = '".$this->type."'
            ORDER BY 
                `inventory`.`id`  DESC";            

        } elseif ($this->category){
           // concatenate select query for particular category
            $query .= "
            WHERE
                inventory.category = '".$this->category."'
            ORDER BY 
                `inventory`.`id`  DESC";      

        } else {
            // select query
            $query .= "
            ORDER BY 
                `inventory`.`id`  DESC";
        }
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // get single item data
    function read_single(){

        // select all query
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . " 
                WHERE
                    id= '".$this->id."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    // create item
    function create(bool $fromImport){
    
		// check if SKU already exists
        if($this->isAlreadyExist()){
            return false;
        }
        
        // query to insert record
        if ($fromImport) { // method called from import function
            $query = "INSERT INTO  
                        ". $this->table_name ." 
                            (`SKU`, `type`, `category`, `description`, `qty`, `qtyIn`, `qtyOut`, `supplier`, `inventoryDate`)
                    VALUES
                            ('".$this->SKU."', '".$this->type."', '".$this->category."', '".$this->description."','".$this->qty."', 
                            '".$this->qtyIn."', '".$this->qtyOut."', '".$this->supplier."', '".$this->inventoryDate."')";

            // prepare query
            $stmt = $this->conn->prepare($query);

        } else { // method called from API service
            $query = "INSERT INTO
                        ". $this->table_name ." 
                    SET
                        SKU=:SKU, type=:type, category=:category, description=:description, qty=:qty, qtyIn=:qtyIn, 
                        qtyOut=:qtyOut, supplier=:supplier, isGSM=:isGSM, isUMTS=:isUMTS, isLTE=:isLTE, 
                        ancillary=:ancillary, toCheck=:toCheck, notes=:notes";
            
            // prepare and bind query
            $stmt = $this->conn->prepare($query);
            $stmt = $this->bindValues($stmt);                        
        }
          
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // update item 
    function update(bool $fromImport){

        // query to insert record      
        if ($fromImport) { // method called from import function
            // query to insert record
            $query = "UPDATE
                        " . $this->table_name . "
                    SET
                        qty='".$this->qty."', qtyIn='".$this->qtyIn."', 
                        qtyOut='".$this->qtyOut."', inventoryDate='".$this->inventoryDate."',
                        description='".$this->description."', supplier='".$this->supplier."'
                    WHERE
                        id='".$this->id."'"; 

            // prepare query
            $stmt = $this->conn->prepare($query);
                        
        } else { // method called from API service
            $query = "UPDATE
                        " . $this->table_name . "
                    SET
                        SKU=:SKU, type=:type, category=:category, description=:description, qty=:qty, qtyIn=:qtyIn, 
                        qtyOut=:qtyOut, supplier=:supplier, isGSM=:isGSM, isUMTS=:isUMTS, isLTE=:isLTE,
                        ancillary=:ancillary, toCheck=:toCheck, notes=:notes
                    WHERE
                        id='".$this->id."'";
            
            // prepare and bind query
            $stmt = $this->conn->prepare($query);
            $stmt = $this->bindValues($stmt);
        }  

        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){        
            return true;
        }
        return false;
    }

    // update item quantities
    function updateQuantities(){  // method called from import function
       
        // query to update record quantities
        $query = "UPDATE 
                    " . $this->table_name . "
                SET 
                    qty= qty + '".$this->qty."', qtyIn= qtyIn + '".$this->qtyIn."', 
                    qtyOut= qtyOut + '".$this->qtyOut."'     
                WHERE 
                    id='".$this->id."'";         
    
        // prepare query
        $stmt = $this->conn->prepare($query);

        // execute query
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    // delete item
    function delete(){
        
        // query to delete record
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                    id= '".$this->id."'";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return true;
        }
        return false;
    }

    function isAlreadyExist(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                SKU='".$this->SKU."' AND type='".$this->type."' AND category='".$this->category."'"; 

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['id']; // return item id of matching item
        }
        else{
            return false;
        }
    }

    function bindValues($stmt){
        if ($this->SKU == ""){
            $stmt->bindValue(':SKU', $this->SKU, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':SKU', $this->SKU);
        }
        if ($this->type == ""){
            $stmt->bindValue(':type', $this->type, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':type', $this->type);
        }
        if ($this->category == ""){
            $stmt->bindValue(':category', $this->category, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':category', $this->category);
        }        
        if ($this->description == ""){
            $stmt->bindValue(':description', $this->description, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':description', $this->description);
        }
        if ($this->qty == ""){
            $stmt->bindValue(':qty', $this->qty, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':qty', $this->qty);
        }
        if ($this->qtyIn == ""){
            $stmt->bindValue(':qtyIn', $this->qtyIn, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':qtyIn', $this->qtyIn);
        }
        if ($this->qtyOut == ""){
            $stmt->bindValue(':qtyOut', $this->qtyOut, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':qtyOut', $this->qtyOut);
        }
        if ($this->supplier == ""){
            $stmt->bindValue(':supplier', $this->supplier, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':supplier', $this->supplier);
        }
        if ($this->isGSM == ""){
            $stmt->bindValue(':isGSM', $this->isGSM, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':isGSM', $this->isGSM);
        }
        if ($this->isUMTS == ""){
            $stmt->bindValue(':isUMTS', $this->isUMTS, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':isUMTS', $this->isUMTS);
        }
        if ($this->isLTE == ""){
            $stmt->bindValue(':isLTE', $this->isLTE, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':isLTE', $this->isLTE);
        }
        if ($this->ancillary == ""){
            $stmt->bindValue(':ancillary', $this->ancillary, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':ancillary', $this->ancillary);
        }
        if ($this->toCheck == ""){
            $stmt->bindValue(':toCheck', $this->toCheck, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':toCheck', $this->toCheck);
        }
        if ($this->notes == ""){
            $stmt->bindValue(':notes', $this->notes, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':notes', $this->notes);
        }
        return $stmt;
    }    

}