<?php
// include object files
include_once 'base.php';

class Inventory extends base{
 
    // database table name
    protected $table_name = "inventory";
 
    // object properties
    public $SKU;
    public $type;
    public $category;
    public $description;
    public $qty;
    public $qtyIn;
    public $qtyOut;
    public $supplier;
    public $notes;
    public $importDate;
    public $search_term;

    // function counters
    public $created_counter = 0;
    public $updated_counter = 0;
    public $conflict_counter = 0;
    public $deleted_counter = 0;
    public $import_status = false;
 
    // read all inventory
    function read(){

        // select query
        $query = "SELECT 
            inventory.id, inventory.SKU, inventory_types.id AS type_id, 
            inventory_types.name AS type_name, inventory_types.import_name AS type_altname, 
            inventory_categories.id AS category_id, inventory_categories.name AS category_name,
            inventory.description, inventory.qty, inventory.qtyIn, inventory.qtyOut,
            inventory.supplier, inventory.importDate, SUM(projects.qty) AS qty_projects_allocated
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
                            (`SKU`, `type`, `category`, `description`, `qty`, `qtyIn`, `qtyOut`, `supplier`, `importDate`)
                    VALUES
                            ('".$this->SKU."', '".$this->type."', '".$this->category."', '".$this->description."','".$this->qty."', 
                            '".$this->qtyIn."', '".$this->qtyOut."', '".$this->supplier."', '".$this->importDate."')";

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
            $this->logging($old_row);
            return true;
        }
        return false;
    }

    // update item quantities
    function updateQuantities(){  // method called from import function and transactions object 
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

    function import($file, $inventory_types, $import_category){
        $modifiedItemIDs = []; // to keep track of modified inventory item IDs
        fgetcsv($file, 10000, ","); // before beginning the while loop, just get the first line and do nothing with it
        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
            if ($getData[0] == NULL) // skip blank lines in file
                continue;
            
            if (($getData[1] != NULL) && $data_type = array_search(strtoupper(trim($getData[1])), $inventory_types)) {
    
                // Get data from CSV and clean values
                if (!empty($getData[0])) {               
                    $data_date = date('Y-m-d', strtotime(str_replace('/', '-', trim($getData[0]))));
                } else {
                    $data_date = "";
                }
    
                if (!empty($getData[2])) {
                    $data_SKU = trim($getData[2]); 
                } else {
                    $data_SKU = ""; 
                }
    
                if (!empty($getData[3])) {
                    $data_description = trim($getData[3]);  
                } else {
                    $data_description = ""; 
                }
    
                if (!empty($getData[4])) {
                    $data_qty = trim($getData[4]);  
                } else {
                    $data_qty = "0"; 
                }
    
                if (!empty($getData[6])) {
                    $data_qtyIn = trim($getData[6]);  
                } else {
                    $data_qtyIn = "0"; 
                }
    
                if (!empty($getData[7])) {
                    $data_qtyOut = trim($getData[7]);  
                } else {
                    $data_qtyOut = "0"; 
                }
    
                if (!empty($getData[8])) {
                    $data_supplier = trim($getData[8]);  
                } else {
                    $data_supplier = ""; 
                }
                            
                // prepare inventory item object
                $this->SKU = $data_SKU;
                $this->category = $import_category;
                $this->type = $data_type;
                $this->description = $data_description;
                $this->qty = $data_qty;
                $this->qtyIn = $data_qtyIn;
                $this->qtyOut = $data_qtyOut;
                $this->supplier = $data_supplier;
                $this->importDate = $data_date;
    
                // check if SKU already exists
                if ($existingId = $this->isAlreadyExist()) { // update existing inventory item
                    $this->id = $existingId;
    
                    // check if item was already modified
                    if (in_array($this->id, $modifiedItemIDs)) {
                        if ($this->updateQuantities()) { // update inventory item with quantities to add up
                            $this->conflict_counter++;
                            $this->import_status = true;
                        }
                    } else if ($this->update(true)) { // update inventory item
                        $this->updated_counter++;
                        $this->import_status = true;
                        array_push($modifiedItemIDs, $this->id); // push ID to modifiedItemIDs
                    }
    
                } else {
                    if ($this->create(true)) { // create inventory item
                        $this->created_counter++;
                        $this->import_status = true;
                        array_push($modifiedItemIDs, $this->id); // push ID to modifiedItemIDs
                    }
                }
    
            }
    
        }
        fclose($file);

        // clean-up operation
        $this->deleted_counter = $this->inventorySweep();
    }

    // clean inventory
    private function inventorySweep(){
        // delete OLD inventory items which aren't referenced by projects, registry and reports 
        $query = "DELETE FROM " . $this->table_name . "  WHERE (importDate < '" . $this->importDate . "') AND (category = '" . $this->category . "') AND id IN (
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
        $stmt_delete = $this->conn->prepare($query);
        // execute query
        $stmt_delete->execute();

        // clear quantities for the rest... (referenced items)
        $query = "UPDATE " . $this->table_name . " 
                    SET qty='0' 
                    WHERE (importDate < '" . $this->importDate . "') AND (category = '" . $this->category . "')";    
        // prepare query
        $stmt_clear = $this->conn->prepare($query);
        // execute query
        $stmt_clear->execute();

        return $stmt_delete->rowCount();
    }

    private function bindValues($stmt){
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