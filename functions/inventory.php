<?php
// include database and object files
include_once '../api/config/database.php';
include_once '../api/objects/inventory.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 

if(isset($_POST["Import"])) {

    $created_counter = 0;
    $updated_counter = 0;

    $filename=$_FILES["file"]["tmp_name"];

    if($_FILES["file"]["size"] > 0) {
        $file = fopen($filename, "r");
        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {

            if ((strtoupper($getData[1]) == "VNETWORK") or (strtoupper($getData[1]) == "VNETWORKS") or (strtoupper($getData[1]) == "INDOOR_REPEATER") or (strtoupper($getData[1]) == "VNETWORKS_SPARE") or (strtoupper($getData[1]) == "VNETWORKS_RETURN")) {

                // Get Data from CSV
                if (!empty($getData[1])) {               
                    $data_Type = strtoupper($getData[1]);
                } else {
                    $data_Type = "";
                }

                if (!empty($getData[2])) {
                    $data_SKU = $getData[2]; 
                } else {
                    $data_SKU = ""; 
                }

                if (!empty($getData[3])) {
                    $data_Description = $getData[3];  
                } else {
                    $data_Description = ""; 
                }

                if (!empty($getData[4])) {
                    $data_Quantity = $getData[4];  
                } else {
                    $data_Quantity = ""; 
                }

                
                // Overwrite var data_Type with correct terminology
                if ($data_Type == "VNETWORK" or $data_Type == "VNETWORKS") {
                    $data_Type = 1; // General
                } else if ($data_Type == "VNETWORKS_SPARE") {
                    $data_Type = 2; // Spares
                } else if ($data_Type == "INDOOR_REPEATER") {
                    $data_Type = 3; // Repeaters
                } else {
                    $data_Type = 4; // Returns
                }


                // prepare inventory item object
                $item = new Inventory($db);
                $item->SKU = $data_SKU;
                $item->type = $data_Type;
                $item->description = $data_Description;
                $item->qty = $data_Quantity;
                $item->sessionId = "d10e64bf7296c7b771f4"; // TODO INCLUDE sessionID in AJAX POST REQUEST


                // check if SKU already exists
                if($existingId = $item->isAlreadyExist()){
                    $item->id = $existingId;
                    if($item->update()){ // update inventory item
                        $updated_counter++;
                    }
                } else {
                    if($item->create()){ // create inventory item
                        $created_counter++;
                    }
                }


            
            }
            // check success
        }
        fclose($file);	
    }
    echo "<br> Number of created items: ";
    echo $created_counter;
    echo "<br> Number of updated items: ";
    echo $updated_counter;
}