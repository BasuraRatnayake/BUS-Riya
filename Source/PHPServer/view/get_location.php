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
        
        if(isset($_GET['bus_no'])){
            $bus_no = $core->cleanInput($_GET['bus_no']);
            if($database->numberOfRecords("bus_no", "bus", "WHERE bus_no = '$bus_no'")){
                $result = $database->select("cur_lon, cur_lat, returnData", "location", "WHERE bus_no = '$bus_no'");
                $data=array();
                while($row = $result->fetch_assoc()) {
                    $cur_lat = $row["cur_lat"];   
                    $cur_lon = $row["cur_lon"];
                    $return_data = $row["returnData"];

                    $data[] = array(
                        "bus_no" => "$bus_no",
                        "cur_lat" => $cur_lat,
                        "cur_lon" => "$cur_lon",
                        "return_data" => "$return_data"
                    );
                }
                
                $data = array(
                    "status" => true,
                    "response_code" => "200",
                    "message" => "Bus Location",
                    "data" => $data
                );
                http_response_code(200);
            }else{
                $data = array(
                    "status" => false,
                    "response_code" => "404",
                    "message" => "No Bus with the number found."
                );
                http_response_code(404);
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