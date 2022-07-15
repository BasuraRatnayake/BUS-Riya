<?php

require_once ("../controller/core_inc.php");
require_once ("../controller/database_inc.php");
require_once ("../controller/tokens_inc.php");

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
        
        if(isset($_POST['route_no'], $_POST['start_loc'], $_POST['end_loc'])){
            $route_Id = "";
            $route_No = $core->cleanInput($_POST['route_no']);
            $start_loc = $core->cleanInput($_POST['start_loc']);
            $end_loc = $core->cleanInput($_POST['end_loc']);
            
            if($this->database->insert("tbl_route", "NULL, '$route_No', '$start_loc', '$end_loc'")){
                $routeId = $database->lastInsertedId();
                $data = array(
                    "status" => true,
                    "response_code" => "200",
                    "message" => "New Route",
                    "token" => (object)array(
                        "route_id" => $routeId,
                        "route_no" => "$route_No",
                        "start_loc" => "$start_loc",
                        "end_loc" => "$end_loc"
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