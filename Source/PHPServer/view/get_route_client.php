<?php

require_once ("../controller/core_inc.php");
require_once ("../controller/database_inc.php");
require_once ("../controller/clientToken.php");

$core = new Core();
$database = new Database();
$tokens = new ClientTokens();

header("Content-Type: text/json; charset=UTF-8"); 
header("Connection: close"); 

if(isset($_GET['auth_token'])){    
    $authToken = $core->cleanInput($_GET['auth_token']);
    $tokenData = $tokens->validateAuthToken($authToken);    
    $tokenData = json_decode($tokenData);        
    
    if($tokenData->response_code == "200"){
        $username = $core->cleanInput($tokenData->token->username);  
        if($database->numberOfRecords("routeId", "tbl_route", "")){
            $result = $database->select("*", "tbl_route", "");
            $data=array();
            while($row = $result->fetch_assoc()) {
                $routeId = $row["routeId"];   
                $route_no = $row["route_no"];
                $rStart = $row["start_loc"];
                $rEnd = $row["end_loc"];

                $data[] = array(
                    "route_id" => "$routeId",
                    "route_no" => "$route_no",
                    "route_start" => "$rStart",
                    "route_end" => "$rEnd"
                );
            }

            $data = array(
                "status" => true,
                "response_code" => "200",
                "message" => "Routes",
                "data" => $data
            );
            http_response_code(200);
        }else{
            $data = array(
                "status" => false,
                "response_code" => "404",
                "message" => "No Bus Routes"
            );
            http_response_code(404);
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