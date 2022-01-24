<?php
// include database and object files
include_once '../../config/database.php';
include_once '../../objects/projects.php';
include_once '../../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare projects item object
$item = new Projects($db);

// set type property of projects type property to be shown 
if (isset($_GET['id'])) {
    $item->type = $_GET['id'];
}

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

// query transactions item
$stmt = $item->read();
if ($stmt != false){
    $num = $stmt->rowCount();
    
    if (!$num){
        header("HTTP/1.0 404 Not Found");
        die();
    }

    // open the file for writing
    $file = fopen('php://memory', 'w'); 

    // save the column headers
    fputcsv($file, array('SKU','Description','Quantity','Notes'));

    // project item array
    $output_arr=array();
    $output_arr["csv_data"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $row_data = array($inventory_SKU, $description, $qty, $notes);
        array_push($output_arr["csv_data"], $row_data);
    }

    // save each row of the data
    foreach ($output_arr["csv_data"] as $row_data){
        fputcsv($file, $row_data);
    }
            
    // reset the file pointer to the start of the file
    fseek($file, 0);
    // tell the browser it's going to be a csv file
    header('Content-Type: application/csv');
    // tell the browser we want to save it instead of displaying it
    header('Content-Disposition: attachment; filename="project_'. $item->type . '.csv";');
    // make php send the generated csv lines to the browser
    fpassthru($file);
}
