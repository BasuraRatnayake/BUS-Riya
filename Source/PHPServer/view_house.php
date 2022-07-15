<?php

/* 
 * View House PHP File
 * Version 1.0.0.0
 */

require_once ("../controller/core_inc.php");
require_once ("../controller/database_inc.php");
require_once ("../controller/tokens_inc.php");

require_once ("../model/mdl_house.php");

$core = new Core();
$database = new Database();
$tokens = new Tokens();

$house;

$data = (object)array();
 
header("Content-Type: text/json; charset=UTF-8"); 
header("Connection: close"); 

if(isset($_GET['command'], $_GET['auth_token'])){    
    $command = $core->cleanInput($_GET['command']);
    $authToken = $core->cleanInput($_GET['auth_token']);
    
    $tokenData = $tokens->validateAuthToken($authToken);    
    $tokenData = json_decode($tokenData);        
    
    if($tokenData->response_code == "200"){
        $username = $core->cleanInput($tokenData->token->username);    
        $command = strtoupper($command);
        if($command == "GET"){
            if(isset($_GET['filter'])){
                $filter = strtolower($core->cleanInput($_GET['filter']));
                $validCommands = array("houseId", "hubId", "username", "houseName", "postallane", "town", "city", "landnumber", "powerstate", "all");
                                
                if($filter == "all"){                    
                    $house = new House($username, $filter);
                }else{
                    $filterValid = explode(",", $filter);
                    $filter = "";
                    for($i=0;$i<sizeof($filterValid);$i++){
                        $filterValid[$i] = strtolower($filterValid[$i]);
                        if($filterValid[$i] == "all"){
                            $filter = "all";
                            break;
                        }else{                            
                            if (in_array($filterValid[$i], $validCommands)){
                                $filter .= $filterValid[$i].",";
                            }
                        }
                    }
                    $filter = rtrim($filter, ",");
                    $house = new House($username, $filter);
                }
                $data = array(
                    "status" => true,
                    "response_code" => "$tokenData->response_code",
                    "message" => "House Detail(s)",
                    "data" => $house->exportAsJSON()
                );                
            }
        }else if($command == "UPDATE"){
            if(isset($_POST['filter'], $_POST['data'])){
                $data_fields = strtolower($core->cleanInput($_POST['filter']));
                $data_request = $core->cleanInput($_POST['data']);
                
                $validCommands = array("housename", "postallane", "town", "city", "landnumber", "powerstate");
                if((count(explode(",", $data_fields)) > 0 && count(explode(",", $data_request)) > 0) && count(explode(",", $data_fields)) == count(explode(",", $data_request))){
                    $house = new House($username, "all");
                    $data_fields = explode(",", $data_fields);
                    $data_request = explode(",", $data_request);
                    
                    $data = array();
                    
                    for($i=0;$i<sizeof($data_fields);$i++){
                        $data_fields[$i] = $core->cleanInput($data_fields[$i]);
                        $data_request[$i] = $core->cleanInput($data_request[$i]);
                        $dataU;
                        
                        switch($data_fields[$i]){
                            case "housename":          
                                $dataU = $house->update_housename($data_request[$i]);                      
                                break;
                            case "postallane":
                                $dataU = $house->update_postallane($data_request[$i]);
                                break;
                            case "town":                                
                                $dataU = $house->update_town($data_request[$i]);
                                break;
                            case "city":
                                $dataU = $house->update_city($data_request[$i]);
                                break;
                            case "landnumber":
                                $dataU = $house->update_landnumber($data_request[$i]);
                                break;
                            case "powerstate":
                                $dataU = $house->update_powerstate($data_request[$i]) == 1 ? "ON" : "OFF";
                                break;
                        }
                        
                        if($dataU == null){
                            $data["new_$data_fields[$i]"] = array(
                                "updated" => false,
                                "message" => "Not Updated Due to Incorrect Data"
                            );  
                        }else{
                            $data["new_$data_fields[$i]"] = array(
                                "updated" => true,
                                "message" => "Updated to: $dataU"
                            );  
                        }
                    }
                    $data = array(
                        "status" => true,
                        "response_code" => "$tokenData->response_code",
                        "message" => "House Detail(s) Updated",
                        "data" => $data
                    ); 
                }else{
                    $data = array(
                        "status" => false,
                        "response_code" => "406",
                        "message" => "Insuffient Data"
                    );
                }    
            }else{
                $data = array(
                    "status" => false,
                    "response_code" => "406",
                    "message" => "Insuffient Data"
                );
            }
        }else if($command == "REMOVE"){
            //Implement Here
        }else{
            $data = array(
                "status" => false,
                "response_code" => "406",
                "message" => "Insuffient Data"
            );
        }
    }else{
        $data = array(
            "status" => false,
            "response_code" => "$tokenData->response_code",
            "message" => "$tokenData->message"
        ); 
    }
}else{
    $data = array(
        "status" => false,
        "response_code" => "400",
        "message" => "Bad Request"
    );  
}

echo json_encode($data);