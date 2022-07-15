<?php

require_once ("../controller/core_inc.php");
require_once ("../controller/database_inc.php");
require_once ("../controller/adminTokens.php");

$core = new Core();
$database = new Database();
$tokens = new AdminTokens();

header("Content-Type: text/json; charset=UTF-8"); 
header("Connection: close"); 

if(isset($_GET['auth_token'])){    
    $authToken = $core->cleanInput($_GET['auth_token']);
    $tokenData = $tokens->validateAuthToken($authToken);    
    $tokenData = json_decode($tokenData);        
    
    if($tokenData->response_code == "200"){
        $username = $core->cleanInput($tokenData->token->username);    
        
        $result = $database->select("*", "busOwner", "");
        $data=array();
        while($row = $result->fetch_assoc()) {
            $licenseNo = $row["licenseNo"];   
            $tel = $row["tel"];
            $fname = $row["fname"]." ".$row["lname"];

            $data[] = array(
                "license_no" => "$licenseNo",
                "tel" => "$tel",
                "fullName" => $fname
            );
        }

        $data = array(
            "status" => true,
            "response_code" => "200",
            "message" => "Bus Owner Data",
            "data" => $data
        );
        http_response_code(200);
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