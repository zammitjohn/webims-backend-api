<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/pools.php';
include_once '../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare pools item object
$item = new Pools($db);
 
// set type property of pools item tech and pool to be shown 
$item->tech = isset($_GET['tech']) ? $_GET['tech'] : die();
$item->pool = isset($_GET['pool']) ? $_GET['pool'] : die();

// API AUTH Key check
$user = new Users($db); // prepare users object
if (isset($_SERVER['HTTP_AUTH_KEY'])){ $user->sessionId = $_SERVER['HTTP_AUTH_KEY']; }
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// query pools item
$stmt = $item->read();

if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // pools item array
        $output_arr=array();
        $output_arr["Pools"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $pools_item=array(
                "id" => $id,
                "tech_id" => $tech_id,
                "tech_name" => $tech_name,
                "pool" => $pool,
                "name" => $name,
                "description" => $description,
                "qtyOrdered" => $qtyOrdered,
                "qtyStock" => $qtyStock,
                "notes" => $notes
            );
            array_push($output_arr["Pools"], $pools_item);
        }
    
        echo json_encode($output_arr["Pools"]);
    }
    else{
        echo json_encode(array());
    }
}