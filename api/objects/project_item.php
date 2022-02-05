<?php
// include object files
include_once 'base.php';

class project_item extends base{
 
    // database table name
    protected $table_name = "project_item";
 
    // object properties
    public $inventoryId;
    public $projectId;
    public $description;
    public $qty;
    public $notes;

    // read project allocations
    function read_allocations(){
        // select query for particular inventoryId
        $query = "SELECT 
        project_item.inventoryId, project.id AS projectId, project.name AS project_name, 
        SUM(project_item.qty) AS total_qty

        FROM 
        " . $this->table_name . " 
        JOIN 
            project
        ON 
            project_item.projectId = project.id
    

        WHERE
        project_item.inventoryId= '".$this->inventoryId."'

        GROUP BY 
        project_item.projectId";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }

    // read project_item
    function read(){
        // select query
        $query = "SELECT 
            project_item.id, project_item.inventoryId, inventory.SKU AS inventory_SKU, project.id AS projectId,
            project.name AS project_name, project_item.description, project_item.qty, project_item.notes, CONCAT(user.firstName, ' ', user.lastName) AS user_fullName

        FROM 
            " . $this->table_name . " 
            JOIN 
                project
            ON 
                project_item.projectId = project.id

            JOIN 
                inventory
            ON 
                project_item.inventoryId = inventory.id

            LEFT JOIN 
                user
            ON 
                project_item.userId = user.id";               

        // different SQL query according to API call
        if ($this->projectId) {
            // select query for particular projectId
            $query .= "
            WHERE
                project.id= '".$this->projectId."'                        
            ORDER BY 
                `project_item`.`id`  DESC";   

        } else {
            // select query
            $query .= "
            ORDER BY 
                `project_item`.`id`  DESC";   
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
    function create(){ 
        
        // query to insert record
        $query = "INSERT INTO  
                    ". $this->table_name ."
                SET
                    inventoryId=:inventoryId, projectId=:projectId, description=:description, qty=:qty, 
                    notes=:notes, userId=:userId";

        // prepare and bind query
        $stmt = $this->conn->prepare($query);
        $stmt = $this->bindValues($stmt);
    
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
    function update(){
        $old_row = $this->selectRow();
        // query to update record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    inventoryId=:inventoryId, projectId=:projectId, description=:description, qty=:qty, 
                    notes=:notes, userId=:userId                      
                WHERE
                    id= '".$this->id."'";            

        // prepare and bind query
        $stmt = $this->conn->prepare($query);
        $stmt = $this->bindValues($stmt);

        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $this->logging($old_row);
            return true;
        }
        return false;
    }

    private function bindValues($stmt){
        if ($this->inventoryId == ""){
            $stmt->bindValue(':inventoryId', $this->inventoryId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':inventoryId', $this->inventoryId);
        }
        if ($this->projectId == ""){
            $stmt->bindValue(':projectId', $this->projectId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':projectId', $this->projectId);
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
        if ($this->notes == ""){
            $stmt->bindValue(':notes', $this->notes, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':notes', $this->notes);
        }
        if ($this->userId == ""){
            $stmt->bindValue(':userId', $this->userId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':userId', $this->userId);
        }
        return $stmt;
    }
    
    
}