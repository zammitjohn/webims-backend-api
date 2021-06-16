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
    public $notes;
    public $inventoryDate;
    public $search_term;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read all inventory
    function read(){

        // select query
        $query = "SELECT 
            inventory.id, inventory.SKU, inventory_types.id AS type_id, 
            inventory_types.name AS type_name, inventory_types.import_name AS type_altname, 
            inventory_categories.id AS category_id, inventory_categories.name AS category_name,
            inventory.description, inventory.qty, inventory.qtyIn, inventory.qtyOut,
            inventory.supplier, inventory.inventoryDate, SUM(projects.qty) AS qty_projects_allocated
        FROM 
            " . $this->table_name . "
            JOIN 
                inventory_types
            ON 
                inventory.type = inventory_types.id
            JOIN 
                inventory_categories
            ON 
                inventory.category = inventory_categories.id

            LEFT JOIN 
                projects ON inventory.id = projects.inventoryId";

        // different SQL query according to API call
        if ($this->type){
           // concatenate select query for particular type
            $query .= "
            WHERE
                inventory.type = '".$this->type."'
            GROUP BY 
                inventory.id
            ORDER BY 
                `inventory`.`id`  DESC";            

        } elseif ($this->category){
           // concatenate select query for particular category
            $query .= "
            WHERE
                inventory.category = '".$this->category."'
            GROUP BY 
                inventory.id                
            ORDER BY 
                `inventory`.`id`  DESC"; 
                
        } elseif ($this->search_term){
           // search for a specified pattern in a SKU and descriptions column.
            $query .= "
            WHERE
                inventory.SKU LIKE '%".$this->search_term."%' OR inventory.description LIKE '%".$this->search_term."%'
            GROUP BY 
                inventory.id
            ORDER BY 
                `inventory`.`id`  DESC";            

        } else {
            // select query
            $query .= "
            GROUP BY 
                inventory.id            
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
                        qtyOut=:qtyOut, supplier=:supplier, notes=:notes";
            
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
                        qtyOut='".$this->qtyOut."', inventoryDate='".$this->inventoryDate."'
                    WHERE
                        id='".$this->id."'"; 

            // prepare query
            $stmt = $this->conn->prepare($query);
                        
        } else { // method called from API service
            $query = "UPDATE
                        " . $this->table_name . "
                    SET
                        SKU=:SKU, type=:type, category=:category, description=:description, qty=:qty, qtyIn=:qtyIn, 
                        qtyOut=:qtyOut, supplier=:supplier, notes=:notes
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

    // clean inventory
    function inventorySweep(){
        // delete OLD inventory items which aren't referenced by projects, registry and reports 
        $query = "DELETE FROM " . $this->table_name . "  WHERE (inventoryDate < '" . $this->inventoryDate . "') AND (category = '" . $this->category . "') AND id IN (
                    SELECT id FROM (
                        SELECT inventory.id FROM inventory
                        LEFT JOIN projects
                            ON inventory.id = projects.inventoryId
                        LEFT JOIN registry
                            ON inventory.id = registry.inventoryId
                        LEFT JOIN reports
                            ON inventory.id = reports.inventoryId  
                        WHERE (registry.inventoryId IS NULL) AND (projects.inventoryId IS NULL) AND (reports.inventoryId IS NULL)
                    ) AS inventory_old
                )";
        // prepare query
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();

        // clear quantities for the rest... (referenced items)
        $query = "UPDATE " . $this->table_name . " 
                    SET qty='0' 
                    WHERE (inventoryDate < '" . $this->inventoryDate . "') AND (category = '" . $this->category . "')";    
        // prepare query
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
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
        if ($this->notes == ""){
            $stmt->bindValue(':notes', $this->notes, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':notes', $this->notes);
        }
        return $stmt;
    }    

}