<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/spares.php';
include_once '../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare spares item object
$item = new Spares($db);
 
// set type property of spares item type to be shown 
if (isset($_GET['type'])) {
    $item->type = $_GET['type'];
}

// API Key check
if (isset($_SERVER['HTTP_AUTH_KEY'])){
    // prepare users object
    $user = new Users($db);
    $user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $user->validKey() ? : die(); // if key is not valid, die!
} else {
    die(); // if key hasn't been specified, die!
}

// query spares item
$stmt = $item->read();
if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // sapres item array
        $output_arr=array();
        $output_arr["Spares"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $spares_item=array(
                "id" => $id,
                "inventoryId" => $inventoryId,
                "type" => $type,
                "name" => $name,
                "description" => $description,
                "qty" => $qty,
                "notes" => $notes
            );
            array_push($output_arr["Spares"], $spares_item);
        }
    
        echo json_encode($output_arr["Spares"]);
    }
    else{
        echo json_encode(array());
    }
}