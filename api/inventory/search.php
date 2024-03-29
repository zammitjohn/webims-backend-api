<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/inventory.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare inventory object
$inventory = new inventory($db);

// set type property of inventory item type to be shown 
$inventory->search_term = isset($_GET['term']) ? $_GET['term'] : die();

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}
 
// query inventory item
$stmt = $inventory->read();
if ($stmt->rowCount()) {
  // inventory item  array
  $output_arr=array();
  $output_arr["inventory"]=array();

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      extract($row);
      if ($tag) {
        $inventory_item=array(
          "value" => $id,
          "label" => $SKU . ' ('  . $warehouse_name . ' ' . $warehouse_category_name . ') ' . $description . ' #' . $tag
        );
      } else {
        $inventory_item=array(
          "value" => $id,
          "label" => $SKU . ' ('  . $warehouse_name . ' ' . $warehouse_category_name . ') ' . $description
        );
      }
      array_push($output_arr["inventory"], $inventory_item);
  }  
  echo json_encode(array('results' => $output_arr["inventory"])); 

} else {
  echo json_encode(array('results' => []));
}