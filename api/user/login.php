<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';

// JSON body data
$bodyData = json_decode(file_get_contents('php://input'), true);
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare user object
$user = new user($db);

// set user property values
$username = $bodyData['username'];
$password = $bodyData['password'];

// LAPD connection, modify the following as required!
$ldap = ldap_connect('ldap://mt-wi-dc1.telco.mt:389 ldap://mt-wi-dc2.telco.mt:389');
$ldap_DC_1 = 'telco';
$ldap_DC_2 = 'mt';

ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

// Narrow down search to user
if (!filter_var($username, FILTER_VALIDATE_EMAIL)) { // not an email address
    $filter="(sAMAccountName=$username)";
    $ldaprdn = $ldap_DC_1 . "\\" . $username;  
} else { // email address
    $filter="(userPrincipalName=$username)";    
    $ldaprdn = $username;  
}

// Bind to LDAP directory: establishes the authentication state for the session
$bind = @ldap_bind($ldap, $ldaprdn, $password);

if ($bind) { // user found in directory 
    $result = ldap_search($ldap,"DC=" . $ldap_DC_1  . ",DC=" . $ldap_DC_2 . "",$filter);
    $info = ldap_get_entries($ldap, $result);
    for ($i=0; $i<$info["count"]; $i++) {

        // getting values from result
        //var_dump($info[$i]);
        $user->username = $info[$i]["samaccountname"][0];
        $user->firstName = $info[$i]["givenname"][0];
        $user->lastName = $info[$i]["sn"][0];

        // add user to database
        $stmt = $user->login();
        if($stmt){
            // get retrieved row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // set cookie
            $user_info=array(
                "FullName" => $row['firstName'] . ' ' . $row['lastName'],
                "SessionId" => $row['sessionId']
            );
            
            // create array
            $output_arr=array(
                "status" => true,
                "message" => "Log in successful!",
                "id" => $row['id'],
                "username" => $row['username'],
                "firstName" => $row['firstName'],
                "lastName" => $row['lastName'],
                "sessionId" => $row['sessionId'],
                "created" => $row['created']
            );
        } else {
            $output_arr=array(
                "status" => false,
                "message" => "Log in failed!"
            );
        }

    }
    @ldap_close($ldap);

} else { // user not found in directory
    $output_arr=array(
        "status" => false,
        "message" => "Invalid username or password!"
    );
}

echo json_encode($output_arr);
?>