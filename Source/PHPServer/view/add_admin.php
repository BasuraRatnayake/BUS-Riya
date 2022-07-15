<?php

require_once ("../controller/core_inc.php");
require_once ("../controller/database_inc.php");
require_once ("../controller/adminTokens.php");

$core = new Core();
$database = new Database();
$tokens = new AdminTokens();

header("Content-Type: text/json; charset=UTF-8"); 
header("Connection: close"); 

if(isset($_POST['auth_token'])){    
    $authToken = $core->cleanInput($_POST['auth_token']);
    $tokenData = $tokens->validateAuthToken($authToken);    
    $tokenData = json_decode($tokenData);        
    
    if($tokenData->response_code == "200"){
        $username = $core->cleanInput($tokenData->token->username); 
        
        if(isset($_POST['nic'], $_POST['first'], $_POST['last'], $_POST['tel'], 
                $_POST['add1'], $_POST['add2'], $_POST['username'], 
                $_POST['email'], $_POST['password'])){
            $nic = $core->cleanInput($_POST['nic']);  
            $first = $core->cleanInput($_POST['first']);  
            $last = $core->cleanInput($_POST['last']); 
            $tel = $core->cleanInput($_POST['tel']); 
            $add1 = $core->cleanInput($_POST['add1']); 
            $add2 = $core->cleanInput($_POST['add2']); 
            $user = $core->cleanInput($_POST['username']); 
            $email = $core->cleanInput($_POST['email']); 
            $password = $core->cleanInput($_POST['password']);    
            
            if($database->insert("tbl_administrator", "'$nic', '$first', '$last', $tel, '$add1', '$add2'")){
                $client_id = $database->lastInsertedId();

                if($database->insert("admin_login", "'$user', '$nic', '$password', '$email'")){
                    $password = md5($password);
                    $data = array(
                        "status" => true,
                        "response_code" => "200",
                        "message" => "A New Administrator Added",
                        "data" => (object)array(
                            "admin_id" => $client_id,
                            "nic" => "$nic",
                            "fisrt" => "$first",
                            "last" => "$last",
                            "tel" => "$tel",
                            "add1" => "$add1",
                            "add2" => "$add2",
                            "username" => "$user",
                            "password" => "$password",
                            "email" => "$email"
                        )
                    );
                    http_response_code(200);
                }else{
                    $database->delete("tbl_administrator", "WHERE nic = $client_id");
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
        }
    }else{
        $data = array(
            "status" => false,
            "response_code" => "$tokenData->response_code",
            "message" => "$tokenData->message"
        ); 
        http_response_code($tokenData->response_code);
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