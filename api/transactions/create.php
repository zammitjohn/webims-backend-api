<?php
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/transactions_items.php';
include_once '../objects/transactions.php';
include_once '../objects/inventory.php';
include_once '../objects/users.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// json data input
$data = json_decode(file_get_contents('php://input'), true);

// output flags
$status = false;
$requested_counter = 0;
$returned_counter = 0;

// prepare item objects
$transaction = new Transactions($db);
$item = new Transactions_Items($db);

 
// AUTH check 
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){
    $user->action_isCreate = true;
    $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'});
    $transaction->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}


// create new transaction
if($transaction->create()){
    $item->transactionId = $transaction->id; // get transaction id

    // parse json
    $transaction_items = $data['items'];
    foreach($transaction_items as $transaction_item){
        $item->inventoryId = $transaction_item['item_id'];
        $item->qty = $transaction_item['item_qty'];

        if ($item->create()){ // reduce qtys from stock
            $status = true;

            $inventory_item = new Inventory($db);
            $inventory_item->id = $item->inventoryId;
            if ($item->qty < 0){
                $inventory_item->qty = ($item->qty);
                $inventory_item->qtyIn = 0;
                $inventory_item->qtyOut = -($item->qty);
                $requested_counter++;
            } else {
                $inventory_item->qty = ($item->qty);
                $inventory_item->qtyIn = +($item->qty);
                $inventory_item->qtyOut = 0;
                $returned_counter++;
            }
            $inventory_item->updateQuantities();
        }
    }
}

$result_arr=array(
    "status" => $status,
    "requested_count" => $requested_counter,
    "returned_count" => $returned_counter
);

print_r(json_encode($result_arr));