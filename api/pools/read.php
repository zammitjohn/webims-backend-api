<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/pools.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare pools item object
$item = new Pools($db);
 
// set type property of pools item tech and pool to be shown 
$item->tech = isset($_GET['tech']) ? $_GET['tech'] : die();
$item->pool = isset($_GET['pool']) ? $_GET['pool'] : die();

// API Key - sessionId
$item->sessionId = isset($_SERVER['HTTP_AUTH_KEY']) ? $_SERVER['HTTP_AUTH_KEY'] : die();

// query pools item
$stmt = $item->read();

if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // pools item array
        $item_arr=array();
        $item_arr["Pools"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $pools_item=array(
                "id" => $id,
                "tech" => $tech,
                "pool" => $pool,
                "name" => $name,
                "description" => $description,
                "qtyOrdered" => $qtyOrdered,
                "qtyStock" => $qtyStock,
                "notes" => $notes
            );
            array_push($item_arr["Pools"], $pools_item);
        }
    
        echo json_encode($item_arr["Pools"]);
    }
    else{
        echo json_encode(array());
    }
}