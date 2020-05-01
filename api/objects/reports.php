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
    public $reportNo;
    public $requestedBy;
    public $faultySN;
    public $replacementSN;
    public $dateRequested;
    public $dateLeavingRBS;
    public $dateDispatched;
    public $dateReturned;
    public $AWB;
    public $AWBreturn;
    public $RMA;
    public $notes;
    public $sessionId;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read reports
    function read(){

        if(!($this->keyExists())){
            return false;
        }    
    
        // select all query
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . " 
                ORDER BY
                    id DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // get single item data
    function read_single(){

        if(!($this->keyExists())){
            return false;
        }    
    
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

        if(!($this->keyExists())){
            return false;
        }    
        
        // query to insert record
        $query = "INSERT INTO  ". $this->table_name ." 
                        (`inventoryId`, `ticketNo`, `name`, `reportNo`, `requestedBy`, `faultySN`, `replacementSN`, `dateRequested`, `dateLeavingRBS`, `dateDispatched`, `dateReturned`, `AWB`, `AWBreturn`, `RMA`, `notes` )
                VALUES
                        ($this->inventoryId, '".$this->ticketNo."', '".$this->name."', '".$this->reportNo."', $this->requestedBy, $this->faultySN, $this->replacementSN, '".$this->dateRequested."', '".$this->dateLeavingRBS."', '".$this->dateDispatched."', '".$this->dateReturned."', '".$this->AWB."', '".$this->AWBreturn."', '".$this->RMA."', '".$this->notes."')";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    // update item 
    function update(){

        if(!($this->keyExists())){
            return false;
        }    
    
        // query to update record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    inventoryId=$this->inventoryId, ticketNo='".$this->ticketNo."', name='".$this->name."', reportNo='".$this->reportNo."',  requestedBy=$this->requestedBy, faultySN=$this->faultySN, replacementSN=$this->replacementSN, dateRequested='".$this->dateRequested."', dateLeavingRBS='".$this->dateLeavingRBS."', dateDispatched='".$this->dateDispatched."', dateReturned='".$this->dateReturned."', AWB='".$this->AWB."', AWBreturn='".$this->AWBreturn."', RMA='".$this->RMA."', notes='".$this->notes."'
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

        if(!($this->keyExists())){
            return false;
        }    
        
        // query to delete record
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                    id= '".$this->id."'";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function keyExists(){
        $query = "SELECT *
            FROM
                users 
            WHERE
                sessionId='".$this->sessionId."'";
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

}