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

    // object logging
    protected function logging($action){
        $log = new Logs($this->conn);
        $log->properties = json_encode(get_object_vars($this));
        $log->action = $action;
        $log->new();
    }

    // delete item
    public function delete(){

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
            $this->logging('Delete');
            return true;
        }
        return false;
    }

}
?>