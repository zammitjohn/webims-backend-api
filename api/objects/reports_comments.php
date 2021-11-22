<?php
// include object files
include_once 'base.php';

class Reports_Comments extends base{
 
    // database table name
    protected $table_name = "reports_comments";
 
    // object properties
    public $reportId;
    public $text;
 
    // read comments
    function read(){
    
        // select query for particular reportId
        $query = "SELECT 
                    reports_comments.id, reports_comments.reportId, reports_comments.userId, 
                    users.firstname, users.lastname, reports_comments.text, reports_comments.timestamp
                
                FROM 
                    " . $this->table_name . "
                
                    JOIN 
                        users
                    ON 
                        reports_comments.userId = users.id
                        
                WHERE reports_comments.reportId = '".$this->reportId."'
                
                ORDER BY 
                    `reports_comments`.`timestamp`  DESC";                 
        

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