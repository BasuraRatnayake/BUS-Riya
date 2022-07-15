<?php

require_once ("../controller/core_inc.php");
require_once ("../controller/database_inc.php");
require_once ("../controller/clientToken.php");

$core = new Core();
$database = new Database();
$tokens = new ClientTokens();

header("Content-Type: text/json; charset=UTF-8"); 
header("Connection: close"); 

if(isset($_POST['fname'], $_POST['lname'], $_POST['tel'], $_POST['username'], $_POST['password'], $_POST['email'])){
    $client_id = "";
    $fname = $core->cleanInput($_POST['fname']);
    $lname = $core->cleanInput($_POST['lname']);
    $tel = $core->cleanInput($_POST['tel']);
    $username = $core->cleanInput($_POST['username']);
    $password = $core->cleanInput($_POST['password']);
    $email = $core->cleanInput($_POST['email']);

    if($database->insert("client", "NULL, '$fname', '$lname', '$tel'")){
        $client_id = $database->lastInsertedId();
        
        if($database->insert("client_login", "'$username', $client_id, '$password', '$email'")){
            $password = md5($password);
            $data = array(
                "status" => true,
                "response_code" => "200",
                "message" => "A New Client Added",
                "token" => (object)array(
                    "client_id" => $client_id,
                    "fname" => "$fname",
                    "lname" => "$lname",
                    "tel" => "$tel",
                    "username" => "$username",
                    "password" => "$password",
                    "email" => "$email"
                )
            );
            http_response_code(200);
        }else{
            $database->delete("client", "WHERE client_id = $client_id");
            $data = array(
                "status" => false,
                "response_code" => "500",
                "message" => "Internal Server Error Occurred"
            );
            http_response_code(500);
        }
    }else{                
        $data = array(
            "status" => false,
            "response_code" => "500",
            "message" => "Internal Server Error Occurred"
        );
        http_response_code(500);
    }
}else{
    $data = array(
        "status" => false,
        "response_code" => "400",
        "message" => "Bad Request"
    );
    http_response_code(400);
} 
echo json_encode($data);