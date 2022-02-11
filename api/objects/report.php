<?php
// include object files
include_once 'base.php';

class report extends base{
 
    // database table name
    protected $table_name = "report";
 
    // object properties
    public $inventoryId;
    public $ticketNumber;
    public $name;
    public $description;
    public $reportNumber;
    public $assignee_userId;
    public $faulty_registryId;
    public $replacement_registryId;
    public $dateRequested;
    public $dateLeaving;
    public $dateDispatched;
    public $dateReturned;
    public $AWB;
    public $AWBreturn;
    public $RMA;

    // read report
    function read(){

        // filter by pending report assigned to user
        if ($this->assignee_userId) {                        
            $query = "SELECT * 
                    FROM " . $this->table_name . " 
                    WHERE assignee_userId = '".$this->assignee_userId."' AND ((replacement_registryId IS NULL) AND (dateReturned IS NULL) AND (isRepairable = 1))
                    ORDER BY `id` DESC";   

        } else {    
            // select all query
            $query = "SELECT *, 
                    CASE WHEN replacement_registryId IS NOT NULL THEN 1
                        WHEN dateReturned IS NOT NULL THEN 1
                        ELSE 0 END AS isClosed
                    FROM " . $this->table_name . " 
                    ORDER BY `id` DESC";                       
        }
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    // get single report data
    function read_single(){

        // select query
        $query = "SELECT *, 
                CASE WHEN replacement_registryId IS NOT NULL THEN '1'
                    WHEN dateReturned IS NOT NULL THEN '1'
                    ELSE '0' END AS isClosed
                FROM " . $this->table_name . " 
                WHERE id= '".$this->id."'";                        
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    // create report
    function create(){
        
        // query to insert record
        $query = "INSERT INTO  
                    ". $this->table_name ."
                SET
                    inventoryId=:inventoryId, ticketNumber=:ticketNumber, name=:name, description=:description, reportNumber=:reportNumber, assignee_userId=:assignee_userId, 
                    faulty_registryId=:faulty_registryId, replacement_registryId=:replacement_registryId, dateRequested=:dateRequested, 
                    dateLeaving=:dateLeaving, dateDispatched=:dateDispatched, 
                    dateReturned=:dateReturned, AWB=:AWB, AWBreturn=:AWBreturn, RMA=:RMA";
    
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
      
    // update report 
    function update(){ 
        $old_row = $this->selectRow();
        // query to update record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    inventoryId=:inventoryId, ticketNumber=:ticketNumber, name=:name, description=:description, reportNumber=:reportNumber, assignee_userId=:assignee_userId, 
                    faulty_registryId=:faulty_registryId, replacement_registryId=:replacement_registryId, dateRequested=:dateRequested, 
                    dateLeaving=:dateLeaving, dateDispatched=:dateDispatched, 
                    dateReturned=:dateReturned, AWB=:AWB, AWBreturn=:AWBreturn, RMA=:RMA                
                
                WHERE
                    id='".$this->id."'";
    
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

    // toggle isRepairable bool
    function toggle_repairable(){
        $old_row = $this->selectRow();
        // query to update record
        $query = "UPDATE
                    " . $this->table_name . "

                SET isRepairable = !isRepairable          
                
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

    private function bindValues($stmt){
        if ($this->inventoryId == ""){
            $stmt->bindValue(':inventoryId', $this->inventoryId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':inventoryId', $this->inventoryId);
        }
        if ($this->ticketNumber == ""){
            $stmt->bindValue(':ticketNumber', $this->ticketNumber, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':ticketNumber', $this->ticketNumber);
        }
        if ($this->name == ""){
            $stmt->bindValue(':name', $this->name, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':name', $this->name);
        }
        if ($this->description == ""){
            $stmt->bindValue(':description', $this->description, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':description', $this->description);
        }
        if ($this->reportNumber == ""){
            $stmt->bindValue(':reportNumber', $this->reportNumber, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':reportNumber', $this->reportNumber);
        }                   
        if ($this->assignee_userId == ""){
            $stmt->bindValue(':assignee_userId', $this->assignee_userId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':assignee_userId', $this->assignee_userId);
        }        
        if ($this->faulty_registryId == ""){
            $stmt->bindValue(':faulty_registryId', $this->faulty_registryId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':faulty_registryId', $this->faulty_registryId);
        }        
        if ($this->replacement_registryId == ""){
            $stmt->bindValue(':replacement_registryId', $this->replacement_registryId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':replacement_registryId', $this->replacement_registryId);
        }        
        if ($this->dateRequested == ""){
            $stmt->bindValue(':dateRequested', $this->dateRequested, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':dateRequested', $this->dateRequested);
        }        
        if ($this->dateLeaving == ""){
            $stmt->bindValue(':dateLeaving', $this->dateLeaving, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':dateLeaving', $this->dateLeaving);
        }        
        if ($this->dateDispatched == ""){
            $stmt->bindValue(':dateDispatched', $this->dateDispatched, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':dateDispatched', $this->dateDispatched);
        }      
        if ($this->dateReturned == ""){
            $stmt->bindValue(':dateReturned', $this->dateReturned, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':dateReturned', $this->dateReturned);
        }
        if ($this->AWB == ""){
            $stmt->bindValue(':AWB', $this->AWB, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':AWB', $this->AWB);
        }
        if ($this->AWBreturn == ""){
            $stmt->bindValue(':AWBreturn', $this->AWBreturn, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':AWBreturn', $this->AWBreturn);
        }
        if ($this->RMA == ""){
            $stmt->bindValue(':RMA', $this->RMA, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':RMA', $this->RMA);
        }
        return $stmt;
    }

}