<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/inventory_transaction_item.php';
include_once '../../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare inventory_transaction object
$inventory_transaction_item = new inventory_transaction_item($db);

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// query inventory_transaction item
$stmt = $inventory_transaction_item->read_all();

if ($stmt->rowCount()) {
    // inventory_transaction item array
    $output_arr=array();
    $output_arr["inventory_transaction"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $inventory_transaction_item=array(
            "id" => $id,
            "description" => $description,
            "date" => $date,
            "user_fullName" => $user_fullName
        );
        array_push($output_arr["inventory_transaction"], $inventory_transaction_item);
    }
    echo json_encode($output_arr["inventory_transaction"]);
} else {
    echo json_encode(array());
}