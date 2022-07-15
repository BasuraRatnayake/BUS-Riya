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
        
        if(isset($_POST['licenseNo'], $_POST['fname'], $_POST['lname'], $_POST['addr1'], $_POST['addr2'], $_POST['tel'])){
            $license = $core->cleanInput($_POST['licenseNo']);
            $fname = $core->cleanInput($_POST['fname']);
            $lname = $core->cleanInput($_POST['lname']);
            $add1 = $core->cleanInput($_POST['addr1']);
            $add2 = $core->cleanInput($_POST['addr2']);
            $tel = $core->cleanInput($_POST['tel']);
            
            if($database->insert("busowner", "'$license', '$fname', '$lname', '$add1', '$add2', $tel")){
                $data = array(
                    "status" => true,
                    "response_code" => "200",
                    "message" => "New Bus Owner",
                    "data" => (object)array(
                        "license_no" => "$license",
                        "fname" => "$fname",
                        "lname" => "$lname",
                        "addl_1" => "$add1",
                        "addl_2" => "$add2",
                        "tel" => "$tel"
                    )
                );
                http_response_code(200);
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