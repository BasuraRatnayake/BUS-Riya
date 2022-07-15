<?php

/* 
 * View Hub PHP File
 * Version 1.0.0.0
 */

require_once ("../controller/core_inc.php");
require_once ("../controller/database_inc.php");
require_once ("../controller/tokens_inc.php");

require_once ("../model/mdl_hub.php");

$core = new Core();
$database = new Database();
$tokens = new Tokens();

$hubs;

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
                $validCommands = array("hubId", "serialnumber", "username", "dateman", "devversion", "all");
                                
                if($filter == "all"){                    
                    $hubs = new Hub($username, $filter);
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
                    $hubs = new Hub($username, $filter);
                }
                $data = array(
                    "status" => true,
                    "response_code" => "$tokenData->response_code",
                    "message" => "Hub Detail(s)",
                    "data" => $hubs->exportAsJSON()
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