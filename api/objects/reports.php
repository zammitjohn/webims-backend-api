<?php
class reports{
 
    // database connection and table name
    private $conn;
    private $table_name = "reports";
 
    // object properties
    public $id;
    public $inventoryId;
    public $ticketNo;
    public $name;
    public $description;
    public $reportNo;
    public $userId;
    public $faultySN;
    public $replacementSN;
    public $dateRequested;
    public $dateLeavingRBS;
    public $dateDispatched;
    public $dateReturned;
    public $AWB;
    public $AWBreturn;
    public $RMA;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read reports
    function read(){

        // filter by user
        if ($this->userId) {
            $query = "SELECT
                        *
                    FROM
                        " . $this->table_name . " 
                    WHERE
                        userId = '".$this->userId."' AND isClosed = '0'
                    ORDER BY 
                        `id`  DESC";     
        } else {    
            // select all query
            $query = "SELECT
                        *
                    FROM
                        " . $this->table_name . " 
                    ORDER BY
                        id DESC";
        }
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    // get single report data
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

    // create report
    function create(){
        
        // query to insert record
        $query = "INSERT INTO  
                    ". $this->table_name ."
                SET
                    inventoryId=:inventoryId, ticketNo=:ticketNo, name=:name, description=:description, reportNo=:reportNo, userId=:userId, 
                    faultySN=:faultySN, replacementSN=:replacementSN, dateRequested=:dateRequested, 
                    dateLeavingRBS=:dateLeavingRBS, dateDispatched=:dateDispatched, 
                    dateReturned=:dateReturned, AWB=:AWB, AWBreturn=:AWBreturn, RMA=:RMA";
    
        // prepare and bind query
        $stmt = $this->conn->prepare($query);
        $stmt = $this->bindValues($stmt);

        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }
      
    // update report 
    function update(){ 
    
        // query to update record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    inventoryId=:inventoryId, ticketNo=:ticketNo, name=:name, description=:description, reportNo=:reportNo, userId=:userId, 
                    faultySN=:faultySN, replacementSN=:replacementSN, dateRequested=:dateRequested, 
                    dateLeavingRBS=:dateLeavingRBS, dateDispatched=:dateDispatched, 
                    dateReturned=:dateReturned, AWB=:AWB, AWBreturn=:AWBreturn, RMA=:RMA                
                
                WHERE
                    id='".$this->id."'";
    
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

    // toggle isClosed bool
    function toggle_status(){
        
        // query to update record
        $query = "UPDATE
                    " . $this->table_name . "

                SET isClosed = !isClosed          
                
                WHERE
                    id='".$this->id."'";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return true;
        }
        return false;
    }

    function bindValues($stmt){
        if ($this->inventoryId == ""){
            $stmt->bindValue(':inventoryId', $this->inventoryId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':inventoryId', $this->inventoryId);
        }
        if ($this->ticketNo == ""){
            $stmt->bindValue(':ticketNo', $this->ticketNo, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':ticketNo', $this->ticketNo);
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
        if ($this->reportNo == ""){
            $stmt->bindValue(':reportNo', $this->reportNo, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':reportNo', $this->reportNo);
        }                   
        if ($this->userId == ""){
            $stmt->bindValue(':userId', $this->userId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':userId', $this->userId);
        }        
        if ($this->faultySN == ""){
            $stmt->bindValue(':faultySN', $this->faultySN, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':faultySN', $this->faultySN);
        }        
        if ($this->replacementSN == ""){
            $stmt->bindValue(':replacementSN', $this->replacementSN, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':replacementSN', $this->replacementSN);
        }        
        if ($this->dateRequested == ""){
            $stmt->bindValue(':dateRequested', $this->dateRequested, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':dateRequested', $this->dateRequested);
        }        
        if ($this->dateLeavingRBS == ""){
            $stmt->bindValue(':dateLeavingRBS', $this->dateLeavingRBS, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':dateLeavingRBS', $this->dateLeavingRBS);
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