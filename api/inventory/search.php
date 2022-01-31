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
$item->search_term = isset($_GET['term']) ? $_GET['term'] : die();

// AUTH check
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){ // Cookie authentication 
	$user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'}); 
}
if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
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
            "value" => $id,
            "label" => $SKU . ' ('  . $category_name . ' ' . $type_name . ') ' . $description
        );
        array_push($output_arr["Inventory"], $inventory_item);
    }
  }
  echo json_encode(array('results' => $output_arr["Inventory"])); 
}