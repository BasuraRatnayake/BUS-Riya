<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once ("../controller/core_inc.php");
require_once ("../controller/database_inc.php");
require_once ("../controller/tokens_inc.php");

$core = new Core();
$database = new Database();
$tokens = new Tokens();

header("Content-Type: text/json; charset=UTF-8"); 
header("Connection: close"); 

if(isset($_POST['username'], $_POST['password'])){
    $username = $core->cleanInput($_POST['username']);
    $password = $core->cleanInput($_POST['password']);
    $noOfRows = $database->numberOfRecords("username, password", "tbl_loginDetails", "WHERE username = '$username' AND password = '$password'");
    
    if($noOfRows > 0){
        $data = $tokens->createAuthToken($username);
        http_response_code(200);
        //$data = $tokens->validateAuthToken("5e6b03a1edbc9029b504813beb797e52");
    }else{        
        $data = array(
            "status" => false,
            "response_code" => "401",
            "message" => "Authentication Failed"
        );        
        $data = json_encode($data);
        http_response_code(401);
    }   
}else if(isset($_POST['refresh_token'], $_POST['username'])){
    $refreshToken = $core->cleanInput($_POST['refresh_token']);
    $username = $core->cleanInput($_POST['username']);
    $noOfRows = $database->numberOfRecords("username, refreshToken", "tbl_tokens", "WHERE username = '$username' AND refreshToken = '$refreshToken'");
    
    if($noOfRows > 0){
        $data = $tokens->createAuthToken($username, $refreshToken);
        http_response_code(200);
    }else{        
        $data = array(
            "status" => false,
            "response_code" => "410",
            "message" => "Expired Token"
        );        
        $data = json_encode($data);
        http_response_code(410);
    }    
}else{
    $data = array(
        "status" => false,
        "response_code" => "400",
        "message" => "Bad Request"
    );
    $data = json_encode($data);
    http_response_code(100);
}
echo $data;