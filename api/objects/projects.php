<?php
// include object files
include_once 'base.php';

class Projects extends base{
 
    // database table name
    protected $table_name = "projects";
 
    // object properties
    public $inventoryId;
    public $type;
    public $description;
    public $qty;
    public $notes;

    // read project allocations
    function read_allocations(){
        // select query for particular inventoryId
        $query = "SELECT 
        projects.inventoryId, projects_types.id AS type_id, projects_types.name AS type_name, 
        SUM(projects.qty) AS total_qty

        FROM 
        " . $this->table_name . " 
        JOIN 
            projects_types
        ON 
            projects.type = projects_types.id
    

        WHERE
        projects.inventoryId= '".$this->inventoryId."'

        GROUP BY 
        projects.type";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }

    // read projects
    function read(){
        // select query
        $query = "SELECT 
            projects.id, projects.inventoryId, inventory.SKU AS inventory_SKU, inventory_categories.name AS inventory_category, projects_types.id AS type_id,
            projects_types.name AS type_name, projects.description, projects.qty, projects.notes, CONCAT(users.firstname, ' ', users.lastname) AS user_fullname

        FROM 
            " . $this->table_name . " 
            JOIN 
                projects_types
            ON 
                projects.type = projects_types.id

            JOIN 
                inventory
            ON 
                projects.inventoryId = inventory.id

            LEFT JOIN 
                users
            ON 
                projects.userId = users.id                

            JOIN 
                inventory_categories
            ON 
                inventory.category = inventory_categories.id";               

        // different SQL query according to API call
        if ($this->type) {
            // select query for particular type
            $query .= "
            WHERE
                projects_types.id= '".$this->type."'                        
            ORDER BY 
                `projects`.`id`  DESC";   

        } else {
            // select query
            $query .= "
            ORDER BY 
                `projects`.`id`  DESC";   
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
                    inventoryId=:inventoryId, type=:type, description=:description, qty=:qty, 
                    notes=:notes, userId=:userId";

        // prepare and bind query
        $stmt = $this->conn->prepare($query);
        $stmt = $this->bindValues($stmt);
    
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $this->id = $this->conn->lastInsertId();
            $this->logging('Create');
            return true;
        }

        return false;
    }

    // update item 
    function update(){
        // query to update record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    inventoryId=:inventoryId, type=:type, description=:description, qty=:qty, 
                    notes=:notes, userId=:userId                      
                WHERE
                    id= '".$this->id."'";            

        // prepare and bind query
        $stmt = $this->conn->prepare($query);
        $stmt = $this->bindValues($stmt);

        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $this->logging('Update');
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
        if ($this->type == ""){
            $stmt->bindValue(':type', $this->type, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':type', $this->type);
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