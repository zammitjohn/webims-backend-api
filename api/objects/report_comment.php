<?php
// include object files
include_once 'base.php';

class report_comment extends base{
 
    // database table name
    protected $table_name = "report_comment";
 
    // object properties
    public $reportId;
    public $text;
 
    // read comments
    function read(){
    
        // select query for particular reportId
        $query = "SELECT 
                    report_comment.id, report_comment.reportId, report_comment.userId, 
                    user.firstName, user.lastName, report_comment.text, report_comment.timestamp
                
                FROM 
                    " . $this->table_name . "
                
                    JOIN 
                        user
                    ON 
                        report_comment.userId = user.id
                        
                WHERE report_comment.reportId = '".$this->reportId."'
                
                ORDER BY 
                    `report_comment`.`timestamp`  DESC";                 
        

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    // create
    function create(){ 
        
        // query to insert record
        $query = "INSERT INTO  
                    ". $this->table_name ."
                SET
                    reportId=:reportId, userId=:userId, text=:text";                        

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

    private function bindValues($stmt){
        if ($this->reportId == ""){
            $stmt->bindValue(':reportId', $this->reportId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':reportId', $this->reportId);
        }
        if ($this->userId == ""){
            $stmt->bindValue(':userId', $this->userId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':userId', $this->userId);
        }
        if ($this->text == ""){
            $stmt->bindValue(':text', $this->text, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':text', $this->text);
        }
        return $stmt;
    }
}