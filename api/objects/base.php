<?php
// include object files
include_once 'logs.php';

class base {
    // database connection and table name
    protected $conn;
    protected $table_name;

    // object properties
    public $id;
    public $userId;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // destructor
    public function __destruct(){
    }

    // object logging
    protected function logging($old_row){
        $log = new Logs($this->conn);
        $log->object = get_class($this);
        $log->properties_before = $old_row;
        $log->properties_after = $this->selectRow();
        $log->userId = $this->userId;
        $log->new();
    }

    // delete item
    public function delete(){
        $old_row = $this->selectRow();
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
            $this->logging($old_row);
            return true;
        }
        return false;
    }

    public function selectRow(){
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . "
                WHERE
                    id= '".$this->id."'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($row);  
    }
}
?>