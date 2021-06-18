<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';
include_once '../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare inventory item object
$item = new Inventory($db);

// set type property of inventory item type to be shown 
if (isset($_GET['term'])) {
    $item->search_term = $_GET['term'];
}

// AUTH check 
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){ $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'}); }
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// query inventory item
$stmt = $item->read();
if ($stmt != false){
  $num = $stmt->rowCount();

  // inventory item  array
  $output_arr=array();
  $output_arr["Inventory"]=array();

  // check if more than 0 record found
  if($num>0){
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $inventory_item=array(
            "id" => $id,
            "text" => $SKU,
            "category" => $category_name,
            "type" => $type_name,
            "title" => $description
        );
        array_push($output_arr["Inventory"], $inventory_item);
    }
  }
  echo json_encode(array('results' => $output_arr["Inventory"])); 
}