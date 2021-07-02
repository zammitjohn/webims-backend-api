<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/transactions_items.php';
include_once '../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare transactions item object
$item = new Transactions_Items($db);

// AUTH check 
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){ $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'}); }
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// query transactions item
$stmt = $item->read_all();

if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // transactions item array
        $output_arr=array();
        $output_arr["Transactions"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $transactions_item=array(
                "id" => $id,
                "description" => $description,
                "date" => $date,
                "user_fullname" => $user_fullname
            );
            array_push($output_arr["Transactions"], $transactions_item);
        }
    
        echo json_encode($output_arr["Transactions"]);
    }
    else{
        echo json_encode(array());
    }
}