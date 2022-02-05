<?php
// include object files
include_once 'base.php';

class registry extends base{
 
    // database table name
    protected $table_name = "registry";
 
    // object properties
    public $inventoryId;
    public $serialNumber;
    public $datePurchased;
 
    // list registry items with particular 'inventoryId'
    function read(){
    
        // select query
        $query = "SELECT * FROM (SELECT registry.id, registry.inventoryId, registry.serialNumber, registry.datePurchased,
                    CASE WHEN registry.id = report.faulty_registryId THEN 'Faulty'
                        WHEN registry.id = report.replacement_registryId THEN 'Replacement'
                    ELSE 'New' END AS state
                    FROM ".$this->table_name."
                    LEFT JOIN report
                        ON report.faulty_registryId = registry.id
                        OR report.replacement_registryId = registry.id
                    WHERE registry.inventoryId = ".$this->inventoryId."
                    ORDER BY state ASC LIMIT 1000) AS report_registry
                    GROUP BY report_registry.id";                    
        //** Documented behavior: order by inside subquery ignored. MariaDB recommends LIMIT as a workaround. https://mariadb.com/kb/en/mariadb/faq/general-faq/why-is-order-by-in-a-from-subquery-ignored/**//

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    // create item
    function create(){     

        if($this->isAlreadyExist()){
            return false;
        }

        // query to insert record
        $query = "INSERT INTO
                    ". $this->table_name ." 
                SET
                    inventoryId=:inventoryId, serialNumber=:serialNumber, datePurchased=:datePurchased"; 
    
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

    // check item with same inventoryId and serialNumber already exist
    private function isAlreadyExist(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                inventoryId='".$this->inventoryId."' AND serialNumber='".$this->serialNumber."'"; 

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

    private function bindValues($stmt){
        if ($this->inventoryId == ""){
            $stmt->bindValue(':inventoryId', $this->inventoryId, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':inventoryId', $this->inventoryId);
        }
        if ($this->serialNumber == ""){
            $stmt->bindValue(':serialNumber', $this->serialNumber, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':serialNumber', $this->serialNumber);
        }
        if ($this->datePurchased == ""){
            $stmt->bindValue(':datePurchased', $this->datePurchased, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':datePurchased', $this->datePurchased);
        }
        return $stmt;
    }    

}