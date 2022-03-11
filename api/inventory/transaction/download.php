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

// set ID property of transaction item to be dumped
$inventory_transaction_item->inventory_transactionId = isset($_GET['id']) ? $_GET['id'] : die();

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
$stmt = $inventory_transaction_item->dumpTransactionItems();
if ($stmt->rowCount()) {
    // open the file "transaction.csv" for writing
    $file = fopen('php://memory', 'w'); 

    // save the column headers
    fputcsv($file, array('COMPANY','date','','','','','stk_code','stk_desc','','qty','userid','CAT','DELPERSON'));

    // transaction item array
    $transaction_arr=array();
    $transaction_arr["csv_data"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $row_data = array($warehouse_category_importName, date('Y-m-d', strtotime($date)),'','','','', $SKU, $description,'', $qty, $user_fullName, $warehouse_category_name, 'WebIMS');
        array_push($transaction_arr["csv_data"], $row_data);
    }

    // save each row of the data
    foreach ($transaction_arr["csv_data"] as $row_data){
        fputcsv($file, $row_data);
    }
            
    // reset the file pointer to the start of the file
    fseek($file, 0);
    // tell the browser it's going to be a csv file
    header('Content-Type: application/csv');
    // tell the browser we want to save it instead of displaying it
    header('Content-Disposition: attachment; filename="transaction_'. $inventory_transaction_item->inventory_transactionId . '.csv";');
    // make php send the generated csv lines to the browser
    fpassthru($file);
} else {
    header("HTTP/1.0 404 Not Found");
}
