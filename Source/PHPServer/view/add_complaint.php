<?php

require_once ("../controller/core_inc.php");
require_once ("../controller/database_inc.php");
require_once ("../controller/clientToken.php");

$core = new Core();
$database = new Database();
$tokens = new ClientTokens();

header("Content-Type: text/json; charset=UTF-8"); 
header("Connection: close"); 

header("Content-Type: text/json; charset=UTF-8"); 
header("Connection: close"); 

if(isset($_POST['auth_token'])){    
    $authToken = $core->cleanInput($_POST['auth_token']);
    $tokenData = $tokens->validateAuthToken($authToken);    
    $tokenData = json_decode($tokenData);        
    
    if($tokenData->response_code == "200"){
        $username = $core->cleanInput($tokenData->token->username);   
        if(isset($_POST['client_id'], $_POST['bus_no'], $_POST['date_time'], $_POST['complain_text'])){
            $complain_id = "";
            $clientId = $core->cleanInput($_POST['client_id']);
            $busNo = $core->cleanInput($_POST['bus_no']);
            $dateTime = $core->cleanInput($_POST['date_time']);
            $complain_text = $core->cleanInput($_POST['complain_text']);

            if($database->insert("complaint", "NULL, $clientId, '$busNo', '$dateTime', '$complain_text'")){
                $complain_id = $database->lastInsertedId();

                $data = array(
                    "status" => true,
                    "response_code" => "200",
                    "message" => "A New Client Added",
                    "token" => (object)array(
                        "complain_id" => $complain_id,
                        "client_id" => $clientId,
                        "bus_no" => "$busNo",
                        "datetime" => "$dateTime",
                        "complain" => "$complain_text"
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
    }
}
echo json_encode($data);