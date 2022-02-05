<?php
 
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/inventory_transaction_item.php';
include_once '../../objects/inventory_transaction.php';
include_once '../../objects/inventory.php';
include_once '../../objects/user.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// json data input
$data = json_decode(file_get_contents('php://input'), true);
isset($data['return']) ? : die();
isset($data['items']) ? : die();

// output flags
$status = false;
$message = '';
$requested_counter = 0;
$returned_counter = 0;
$inventory_transactionId = NULL;
$isInStock = true;

// prepare objects
$transaction = new inventory_transaction($db);
$item = new inventory_transaction_item($db);
$inventory_item = new Inventory($db);
 
// AUTH check
$user = new user($db); // prepare user object
if (isset($_COOKIE['UserSession'])){ // Cookie authentication
    $user->action_isUpdate = true;
    $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'});
    $transaction->userId = $user->getUserId();
    $inventory_item->userId = $user->getUserId();
}
if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isUpdate = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $transaction->userId = $user->getUserId();
    $inventory_item->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// data from JSON
$transaction->isReturn = $data['return']; //isReturn flag
$transaction_items = $data['items']; // items

if (sizeOf($transaction_items)){ 
    // create new transaction with items array is populated
    if($transaction->create()){
        $item->inventory_transactionId = $transaction->id; // get transaction id of newly created transaction
        $inventory_transactionId = $transaction->id;

        // check inventory item qtys if issuing items
        if (!($transaction->isReturn)){
            foreach($transaction_items as $transaction_item){
                $inventory_item->id = $transaction_item['item_id'];
                $inventory_item->qty = $transaction_item['item_qty'];
                if(!($inventory_item->checkQuantities())){
                    $status = false;
                    $message = "Not enough stock for one or more items!";
                    $isInStock = false;
                }
            }
        }

        if ($isInStock){
            foreach($transaction_items as $transaction_item){
                $item->inventoryId = $transaction_item['item_id'];
                $item->qty = $transaction_item['item_qty'];

                if ($item->create()){ // reduce qtys from stock
                    $status = true;
                    $message = "Transaction successful!";
                    
                    $inventory_item->id = $item->inventoryId;
                    if (!($transaction->isReturn)){
                        $inventory_item->qty = -($item->qty);
                        $inventory_item->qtyIn = 0;
                        $inventory_item->qtyOut = ($item->qty);
                        $requested_counter++;
                    } else {
                        $inventory_item->qty = ($item->qty);
                        $inventory_item->qtyIn = ($item->qty);
                        $inventory_item->qtyOut = 0;
                        $returned_counter++;
                    }
                    $inventory_item->updateQuantities();
                }
            }
        }
    } 
} else {
    $status = false;
    $message = "No items transacted!";
}

$result_arr=array(
    "status" => $status,
    "message" => $message,
    "id" => $inventory_transactionId,
    "requested_count" => $requested_counter,
    "returned_count" => $returned_counter
);
print_r(json_encode($result_arr));