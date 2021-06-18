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

// set ID property of transaction item to be dumped
$item->transactionId = isset($_GET['id']) ? $_GET['id'] : die();

// AUTH check 
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){ $user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'}); }
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// query transactions item
$stmt = $item->dumpTransactionItems();
if ($stmt != false){
    $num = $stmt->rowCount();

    // open the file "transaction.csv" for writing
    $file = fopen('php://memory', 'w'); 

    // save the column headers
    fputcsv($file, array('Company', 'Date', 'stk_code', 'stk_desc', 'qty', 'userid', 'CAT', 'DELPERSON'));

    // transaction item array
    $transaction_arr=array();
    $transaction_arr["csv_data"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $row_data = array($type_altname, $date, $SKU, $description, $qty, $user_fullname, $category, '');
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
    header('Content-Disposition: attachment; filename="transaction_'. $item->transactionId . '.csv";');
    // make php send the generated csv lines to the browser
    fpassthru($file);
}
