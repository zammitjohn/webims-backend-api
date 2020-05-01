<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare user object
$user = new Users($db);

// set user property values
$user->email = $_POST['email'];
$user->password = $_POST['password'];

// login user
$stmt = $user->login();
if($stmt){
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // create array
    $user_arr=array(
        "status" => true,
        "message" => "Login successful!",
        "id" => $row['id'],
        "email" => $row['email'],
        "firstname" => $row['firstname'],
        "lastname" => $row['lastname'],
        "sessionId" => $row['sessionId'],
        "created" => $row['created']
    );
}
else{
    $user_arr=array(
        "status" => false,
        "message" => "Invalid email or password!"
    );
}
print_r(json_encode($user_arr));
?>






