<?php

require_once ("../controller/core_inc.php");
require_once ("../controller/database_inc.php");
require_once ("../controller/clientToken.php");

$core = new Core();
$database = new Database();
$tokens = new ClientTokens();

header("Content-Type: text/json; charset=UTF-8"); 
header("Access-Control-Allow-Origin: *");
header("Connection: close"); 

if(isset($_POST['bus_no'], $_POST['cur_lon'], $_POST['cur_lat'], $_POST['returnData'])){
    $bus_Id = "";
    $bus_No = $core->cleanInput($_POST['bus_no']);
    $start_loc = $core->cleanInput($_POST['cur_lon']);
    $end_loc = $core->cleanInput($_POST['cur_lat']);
    $returnD = $core->cleanInput($_POST['returnData']);

    if($database->numberOfRecords("bus_no", "location", "WHERE bus_no = '$bus_No'") > 0){
        //$database->delete("location", "WHERE bus_no = '$bus_No'");
    }
    if($database->updateRow("location", "SET cur_lon = '$start_loc', cur_lat = '$end_loc', returnData = '$returnD'", "WHERE bus_no = '$bus_No'")){
            $bus_Id = $database->lastInsertedId();
            $data = array(
                "status" => true,
                "response_code" => "200",
                "message" => "New Location",
                "token" => (object)array(
                    "location_id" => $bus_Id,
                    "bus_no" => "$bus_No",
                    "start_loc" => "$start_loc",
                    "end_loc" => "$end_loc",
                    "return_data" => "$returnD"
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
echo json_encode($data);