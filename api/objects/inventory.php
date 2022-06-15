<?php
// include object files
include_once 'base.php';

class inventory extends base{
 
    // database table name
    protected $table_name = "inventory";
 
    // object properties
    public $SKU;
    public $warehouse_categoryId;
    public $tag;
    public $description;
    public $qty;
    public $qtyIn;
    public $qtyOut;
    public $supplier;
    public $notes;
    public $importDate;

    public $warehouseId;
    public $search_term;
 
    // read all inventory
    function read(){

        // select query
        $query = "SELECT 
            inventory.id, inventory.SKU, warehouse_category.id AS warehouse_categoryId, inventory.tag, 
            warehouse_category.name AS warehouse_category_name, warehouse_category.importName AS warehouse_category_importName, 
            warehouse.id AS warehouseId, warehouse.name AS warehouse_name,
            inventory.description, inventory.qty, inventory.qtyIn, inventory.qtyOut,
            inventory.supplier, inventory.importDate, SUM(project_item.qty) AS qty_project_item_allocated
        FROM 
            " . $this->table_name . "
            JOIN 
                warehouse_category
            ON 
                inventory.warehouse_categoryId = warehouse_category.id
            JOIN 
                warehouse
            ON 
                warehouse_category.warehouseId = warehouse.id

            LEFT JOIN 
                project_item ON inventory.id = project_item.inventoryId";

        // different SQL query according to API call
        if ($this->warehouse_categoryId){
           // concatenate select query for particular warehouse_categoryId
            $query .= "
            WHERE
                inventory.warehouse_categoryId = '".$this->warehouse_categoryId."'
            GROUP BY 
                inventory.id
            ORDER BY 
                `inventory`.`id`  DESC";
        
        } elseif ($this->tag){
            // concatenate select query for particular tag
            $query .= "
            WHERE
                inventory.tag = '".$this->tag."'
            GROUP BY 
                inventory.id                
            ORDER BY 
                `inventory`.`id`  DESC"; 

        } elseif ($this->warehouseId){
           // concatenate select query for particular warehouseId
            $query .= "
            WHERE
                warehouse_category.warehouseId = '".$this->warehouseId."'
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

    // get unique tags
    function read_tags(){
        $query = "SELECT DISTINCT tag AS name
            FROM
                " . $this->table_name . "
            WHERE 
                tag IS NOT NULL";

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
                inventory.id, inventory.SKU, warehouse.id AS warehouseId, inventory.warehouse_categoryId, inventory.tag, inventory.description, inventory.qty, inventory.qtyIn, 
                inventory.qtyOut, inventory.supplier, inventory.notes, inventory.importDate, inventory.lastChange
                FROM
                    " . $this->table_name . " 
                    JOIN 
                        warehouse_category
                    ON 
                        inventory.warehouse_categoryId = warehouse_category.id
                    JOIN 
                        warehouse
                    ON 
                        warehouse_category.warehouseId = warehouse.id
                WHERE
                    inventory.id= '".$this->id."'";
    
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
                            (`SKU`, `warehouse_categoryId`, `description`, `qty`, `qtyIn`, `qtyOut`, `supplier`, `importDate`)
                    VALUES
                            ('".$this->SKU."', '".$this->warehouse_categoryId."', '".$this->description."','".$this->qty."', 
                            '".$this->qtyIn."', '".$this->qtyOut."', '".$this->supplier."', '".$this->importDate."')";

            // prepare query
            $stmt = $this->conn->prepare($query);

        } else { // method called from API service
            $query = "INSERT INTO
                        ". $this->table_name ." 
                    SET
                        SKU=:SKU, warehouse_categoryId=:warehouse_categoryId, tag=:tag, description=:description, qty=:qty, qtyIn=:qtyIn, 
                        qtyOut=:qtyOut, supplier=:supplier, notes=:notes";
            
            // prepare and bind query
            $stmt = $this->conn->prepare($query);
            $stmt = $this->bindValues($stmt);                        
        }
          
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $this->id = $this->conn->lastInsertId();
            $this->logging(null);
            return true;
        }
        return false;
    }

    // update item 
    function update(bool $fromImport){
        $old_row = $this->selectRow();
        // query to insert record      
        if ($fromImport) { // method called from import function
            // query to insert record
            $query = "UPDATE
                        " . $this->table_name . "
                    SET
                        qty='".$this->qty."', qtyIn='".$this->qtyIn."', 
                        qtyOut='".$this->qtyOut."', importDate='".$this->importDate."'
                    WHERE
                        id='".$this->id."'"; 

            // prepare query
            $stmt = $this->conn->prepare($query);
                        
        } else { // method called from API service
            $query = "UPDATE
                        " . $this->table_name . "
                    SET
                        SKU=:SKU, warehouse_categoryId=:warehouse_categoryId, tag=:tag, description=:description, qty=:qty, qtyIn=:qtyIn, 
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
            $this->logging($old_row);
            return true;
        }
        return false;
    }

    // update item quantities
    function updateQuantities(){  // method called from import function and order object 
        $old_row = $this->selectRow();
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
        $stmt->execute();
        if($stmt->rowCount() > 0){   
            $this->logging($old_row);
            return true;
        }
        return false;
    }

    function isAlreadyExist(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                SKU='".$this->SKU."' AND warehouse_categoryId='".$this->warehouse_categoryId."'"; 

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

    function checkQuantities(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                id='".$this->id."' AND qty>='".$this->qty."'"; 

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

    // clean inventory
    function inventorySweep(){
        // list OLD inventory items which aren't referenced by project_item, registry and report 
        $query = "SELECT inventory.id, warehouse.id AS warehouseId, inventory.warehouse_categoryId
                    FROM
                        " . $this->table_name . " 
                        JOIN 
                            warehouse_category
                        ON 
                            inventory.warehouse_categoryId = warehouse_category.id
                        JOIN 
                            warehouse
                        ON 
                            warehouse_category.warehouseId = warehouse.id

                    WHERE (importDate < '" . $this->importDate . "') AND (warehouseId = '" . $this->warehouseId . "') 
        
                        AND inventory.id IN (
                            SELECT id FROM (
                                SELECT inventory.id FROM inventory
                                LEFT JOIN project_item
                                    ON inventory.id = project_item.inventoryId
                                LEFT JOIN registry
                                    ON inventory.id = registry.inventoryId
                                LEFT JOIN report
                                    ON inventory.id = report.inventoryId  
                                WHERE (registry.inventoryId IS NULL) AND (project_item.inventoryId IS NULL) AND (report.inventoryId IS NULL)
                            ) AS inventory_old
                        )";
        // prepare query
        $stmt_delete = $this->conn->prepare($query);
        // execute query
        $stmt_delete->execute();

        // delete items
        while ($row = $stmt_delete->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $this->id = $id;
            $this->delete();
        }

        // list the rest... (referenced items)

        $query = "SELECT inventory.id, warehouse.id AS warehouseId, inventory.warehouse_categoryId, qty, qtyIn, qtyOut
                    FROM 
                    " . $this->table_name . " 
                        JOIN 
                            warehouse_category
                        ON 
                            inventory.warehouse_categoryId = warehouse_category.id
                        JOIN 
                            warehouse
                        ON 
                            warehouse_category.warehouseId = warehouse.id
                    WHERE (importDate < '" . $this->importDate . "') AND (warehouseId = '" . $this->warehouseId . "')";  
                    
        // prepare query
        $stmt_clear = $this->conn->prepare($query);
        // execute query
        $stmt_clear->execute();

        // clear item quantities
        while ($row = $stmt_clear->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $this->id = $id;
            $this->qty = -($qty);
            $this->qtyIn = -($qtyIn);
            $this->qtyOut = -($qtyOut);
            $this->updateQuantities();
        }
        return $stmt_delete->rowCount();
    }

    private function bindValues($stmt){
        if ($this->SKU == ""){
            $stmt->bindValue(':SKU', $this->SKU, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':SKU', $this->SKU);
        }
        if ($this->warehouse_categoryId == ""){
            $stmt->bindValue(':warehouse_categoryId', $this->warehouse_categoryId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':warehouse_categoryId', $this->warehouse_categoryId);
        }   
        if ($this->tag == ""){
            $stmt->bindValue(':tag', $this->tag, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':tag', $this->tag);
        }   
        if ($this->description == ""){
            $stmt->bindValue(':description', $this->description, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':description', $this->description);
        }
        if ($this->qty == ""){
            $stmt->bindValue(':qty', 0);
        } else {
            $stmt->bindValue(':qty', $this->qty);
        }
        if ($this->qtyIn == ""){
            $stmt->bindValue(':qtyIn', 0);
        } else {
            $stmt->bindValue(':qtyIn', $this->qtyIn);
        }
        if ($this->qtyOut == ""){
            $stmt->bindValue(':qtyOut', 0);
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