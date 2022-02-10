<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/project_item.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare project_item object
$project_item = new project_item($db);

// set property of project_object to be shown 
if (isset($_GET['id'])) {
    $project_item->projectId = $_GET['id'];
}

// AUTH check
$user = new user($db); // prepare user object

if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// query order item
$stmt = $project_item->read();
if ($stmt != false){
    $num = $stmt->rowCount();
    
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
    header('Content-Disposition: attachment; filename="project_'. $project_item->projectId . '.csv";');
    // make php send the generated csv lines to the browser
    fpassthru($file);
}
