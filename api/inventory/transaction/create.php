<?php
 
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/inventory_transaction_item.php';
include_once '../../objects/inventory_transaction.php';
include_once '../../objects/inventory.php';
include_once '../../objects/user.php';

// JSON body data
$bodyData = json_decode(file_get_contents('php://input'), true);

// get database connection
$database = new Database();
$db = $database->getConnection();

isset($bodyData['return']) ? : die();
isset($bodyData['items']) ? : die();

// output flags
$status = false;
$message = '';
$requested_counter = 0;
$returned_counter = 0;
$inventory_transactionId = NULL;
$isInStock = true;

// prepare objects
$inventory_transaction = new inventory_transaction($db);
$inventory_transaction_item = new inventory_transaction_item($db);
$inventory = new inventory($db);
 
// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
    $user->action_isUpdate = true;
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
    $inventory_transaction->userId = $user->getUserId();
    $inventory->userId = $user->getUserId();
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// data from JSON
$inventory_transaction->isReturn = $bodyData['return']; //isReturn flag
$transaction_items = $bodyData['items']; // items

if (sizeOf($transaction_items)){ 
    // create new transaction with items array is populated
    if($inventory_transaction->create()){
        $inventory_transaction_item->inventory_transactionId = $inventory_transaction->id; // get transaction id of newly created transaction
        $inventory_transactionId = $inventory_transaction->id;

        // check inventory item qtys if issuing items
        if (!($inventory_transaction->isReturn)){
            foreach($transaction_items as $transaction_item){
                $inventory->id = $transaction_item['item_id'];
                $inventory->qty = $transaction_item['item_qty'];
                if(!($inventory->checkQuantities())){
                    $status = false;
                    $message = "Not enough stock for one or more items!";
                    $isInStock = false;
                }
            }
        }

        if ($isInStock){
            foreach($transaction_items as $transaction_item){
                $inventory_transaction_item->inventoryId = $transaction_item['item_id'];
                $inventory_transaction_item->qty = $transaction_item['item_qty'];

                if ($inventory_transaction_item->create()){ // reduce qtys from stock
                    $status = true;
                    $message = "Transaction successful!";
                    
                    $inventory->id = $inventory_transaction_item->inventoryId;
                    if (!($inventory_transaction->isReturn)){
                        $inventory->qty = -($inventory_transaction_item->qty);
                        $inventory->qtyIn = 0;
                        $inventory->qtyOut = ($inventory_transaction_item->qty);
                        $requested_counter++;
                    } else {
                        $inventory->qty = ($inventory_transaction_item->qty);
                        $inventory->qtyIn = ($inventory_transaction_item->qty);
                        $inventory->qtyOut = 0;
                        $returned_counter++;
                    }
                    $inventory->updateQuantities();
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