<?php
 
// include database and object files
include_once '../api/config/database.php';
include_once '../api/objects/inventory.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 

 if(isset($_POST["Import"])){
		
        $filename=$_FILES["file"]["tmp_name"];

		 if($_FILES["file"]["size"] > 0)
		 {
            $file = fopen($filename, "r");
            while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
                {

                    if ((strtoupper($getData[1]) == "VNETWORK") or (strtoupper($getData[1]) == "VNETWORKS") or (strtoupper($getData[1]) == "INDOOR_REPEATER") or (strtoupper($getData[1]) == "VNETWORKS_SPARE") or (strtoupper($getData[1]) == "VNETWORKS_RETURNS")){
                    echo $getData[0];
                    echo " ";
                    echo $getData[1];
                    echo " ";
                    echo $getData[2];
                    echo " ";
                    echo $getData[3];
                    echo " ";
                    echo $getData[4];
                    echo "<br/>";
                    }
                    //$result = mysqli_query($con, $sql);



                }
			
	         fclose($file);	
		 }
	}	 
 ?>