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
        
        if(isset($_POST['bus_no'], $_POST['licenseNo'], $_POST['routeId'], $_POST['bus_type'], $_POST['cur_seat_cap'], $_POST['seat_cap'])){
            $busno = $core->cleanInput($_POST['bus_no']);
            $license = $core->cleanInput($_POST['licenseNo']);
            $routeId = $core->cleanInput($_POST['routeId']);
            $busType = $core->cleanInput($_POST['bus_type']);
            $cu_seat = $core->cleanInput($_POST['cur_seat_cap']);
            $seat_cap = $core->cleanInput($_POST['seat_cap']);
            
            if($database->insert("bus", "'$busno', '$license', $routeId, '$busType', '$cu_seat', '$seat_cap'")){
                $data = array(
                    "status" => true,
                    "response_code" => "200",
                    "message" => "New Bus",
                    "data" => (object)array(
                        "bus_no" => "$busno",
                        "owner_license" => "$license",
                        "route_id" => $routeId,
                        "bus_type" => "$busType",
                        "current_seats" => "$cu_seat",
                        "seats_max" => "$seat_cap",
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