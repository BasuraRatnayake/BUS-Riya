<?php

require_once ("core_inc.php");
require_once ("database_inc.php");
/**
 * Authenication Token Class
 * Generate, View, Validate Token Data
 * Version 1.0.0.0
 * @author Basura Ratnayake
 */
        
header("Content-Type: text/json; charset=UTF-8"); 
header("Connection: close"); 

class ClientTokens {    
    private $database;
    private $core;
    
    function __construct() {
        $this->core = new Core();
        $this->database = new Database();
    }
    
    public function createAuthToken($username, $refreshToken = null){//Create Token from TimeStamp   
        $username = $this->core->cleanInput($username);
        
        if($refreshToken == null){        
            if($this->database->numberOfRecords("username", "tbl_tokens_client", "WHERE username = '$username'") > 0){
                $this->database->delete("tbl_tokens_client", "WHERE username = '$username'");
            }
            
            $timeStamp = date('Y-m-d H:i:s');
            $hashSalt = $this->core->generateUniqueCode(5);
            $token = hash("md5", $timeStamp.$hashSalt);

            $refreshToken = date('Y-m-d H:i:s');
            $hashSalt = $this->core->generateUniqueCode(5);
            $refreshToken = hash("md5", $refreshToken.$hashSalt);
            
            $timeInSeconds = strtotime($timeStamp);
            if($this->database->insert("tbl_tokens_client", "'$token', '$username', '$timeStamp', '$refreshToken'")){
                $data = array(
                    "status" => true,
                    "response_code" => "200",
                    "message" => "Authentication Success",
                    "token" => (object)array(
                        "auth_token" => "$token",
                        "refresh_token" => "$refreshToken",
                        "username" => "$username"
                    )
                );
            }else{
                $data = array(
                    "status" => false,
                    "response_code" => "500",
                    "message" => "Server Error"
                );
            } 
        }else{
            $token = $this->core->cleanInput($refreshToken);
            
            if($this->database->numberOfRecords("username", "tbl_tokens_client", "WHERE username = '$username' AND refreshToken = '$token'") > 0){                 
                $this->database->delete("tbl_tokens_client", "WHERE username = '$username'");               
                
                $timeStamp = date('Y-m-d H:i:s');
                $hashSalt = $this->core->generateUniqueCode(5);
                $refreshToken = hash("md5", $timeStamp.$hashSalt);
                
                $timeInSeconds = strtotime($timeStamp);
                
                if($this->database->insert("tbl_tokens_client", "'$username', '$timeStamp', '$token', '$hashSalt', '$refreshToken'")){
                    $data = array(
                        "status" => true,
                        "response_code" => "200",
                        "message" => "Authentication Success",
                        "token" => (object)array(
                            "auth_token" => "$token",
                            "auth_expire" => $timeInSeconds,
                            "refresh_token" => "$refreshToken",
                            "username" => "$username"
                        )
                    );
                }else{
                    $data = array(
                        "status" => false,
                        "response_code" => "500",
                        "message" => "Server Error"
                    );
                } 
            }else{
                $data = array(
                    "status" => false,
                    "response_code" => "500",
                    "message" => "Server Error"
                );
            } 
        }  
        
        return json_encode($data);
    }
    
    public function validateAuthToken($token){//Validate Token
        $token = $this->core->cleanInput($token);
        if($this->database->numberOfRecords("username", "tbl_tokens_client", "WHERE token = '$token'") == 1){
            $dataDB = $this->database->fetchFullRow("timeCreated, token, username, refreshToken", "tbl_tokens_client", "WHERE token = '$token'");
            $timeStamp = $this->core->getDuration($dataDB[0]);
            $timeStamp = explode(" ", $timeStamp);
            
            if($timeStamp[1] == "sec" || $timeStamp[1] == "min"){
                if($timeStamp[0] <= 30){
                    $data = array(
                        "status" => true,
                        "response_code" => "200",
                        "message" => "Valid Token",
                        "token" => (object)array(
                            "auth_token" => "$token",
                            "auth_expire" => strtotime($dataDB[0]),
                            "refresh_token" => "$dataDB[5]",
                            "username" => "$dataDB[4]",
                        )
                    ); 
                }else{
                    if($timeStamp[1] == "sec"){
                        $data = array(
                            "status" => true,
                            "response_code" => "200",
                            "message" => "Valid Token",
                            "token" => (object)array(
                                "auth_token" => "$token",
                                "auth_expire" => strtotime($dataDB[0]),
                                "refresh_token" => "$dataDB[5]",
                                "username" => "$dataDB[4]"
                            )
                        );  
                    }else{
                        $this->database->delete("tbl_tokens_client", "WHERE token = '$token'");
                        $data = array(
                            "status" => false,
                            "response_code" => "410",
                            "message" => "Expired Token"
                        );  
                    }
                }
            }else{
                $this->database->delete("tbl_tokens_client", "WHERE token = '$token'");
                $data = array(
                    "status" => false,
                    "response_code" => "410",
                    "message" => "Expired Token"
                );         
            }
        }else{
            $data = array(
                "status" => false,
                "response_code" => "404",
                "message" => "Invalid Token"
            );       
        }
        return json_encode($data);
    }
}