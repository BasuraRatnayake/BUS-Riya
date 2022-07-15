<?php

/**
 * House Class
 * Handles the Data of tbl_house and tbl_loginDetails
 * @author Basura
 */

class House {
    private $houseid;
    private $hubid;
    private $username;
    private $housename;
    private $postallane;
    private $town;
    private $city;
    private $landnumber;
    private $powerstate;
    
    private $filterRecords;
    
    private $where;
    private $data;
    
    function __construct($username, $filter) {
        $database = new Database();
        
        if($database->numberOfRecords("*", "tbl_houses", "WHERE username = '$username'") > 0){
            $data = $database->fetchFullRow("*", "tbl_houses", "WHERE username = '$username'");
            $this->houseid = $data[0];
            $this->hubid = $data[2];
            $this->username = $data[4];
            $this->housename = $data[6];
            $this->postallane = $data[8];
            $this->town = $data[10];
            $this->city = $data[12];
            $this->landnumber = $data[14];
            $this->powerstate = $data[16];
            
            $this->filterRecords = strtolower($filter);            
            
            $this->where = "WHERE username = '$username'";          
        }
    }
    
    public function update_housename($value){
        $database = new Database();
        if($database->update("tbl_houses", "houseName", "'$value'", $this->where)){   
            return $value;
        }else{
            return null;
        }
    }
    public function update_postallane($value){
        $database = new Database();
        if($database->update("tbl_houses", "postal_lane", "'$value'", $this->where)){   
            return $value;
        }else{
            return null;
        }
    }
    public function update_town($value){
        $database = new Database();
        if($database->update("tbl_houses", "town", "'$value'", $this->where)){   
            return $value;
        }else{
            return null;
        }
    }
    public function update_city($value){
        $database = new Database();
        if($database->update("tbl_houses", "city", "'$value'", $this->where)){   
            return $value;
        }else{
            return null;
        }
    }
    public function update_landnumber($value){
        $database = new Database();
        if($database->update("tbl_houses", "landNumber", "$value", $this->where)){   
            return $value;
        }else{
            return null;
        }
    }
    public function update_powerstate($value){
        $database = new Database();
        if($database->update("tbl_houses", "powerState", "$value", $this->where)){   
            return $value;
        }else{
            return null;
        }
    }
        
    public function exportAsJSON(){
        if($this->username == null){
            $this->data = (object)array();
        }else{
            if($this->filterRecords == "all"){
                $this->data = (object)array(
                    "houseId" => "$this->houseid",
                    "hubId" => "$this->hubid",
                    "username" => "$this->username",
                    "housename" => "$this->housename",
                    "postallane" => "$this->postallane",
                    "town" => "$this->town",
                    "city" => "$this->city",
                    "landnumber" => "$this->landnumber",
                    "powerState" => $this->powerstate == 1 ? "ON" : "OFF"
                );  
            }else{
                $filters = explode(",", $this->filterRecords);
                $this->data = array();
                $dataRequest = "";
                for($i=0;$i<sizeof($filters);$i++){
                    switch($filters[$i]){
                        case "houseId":
                            $dataRequest = $this->houseid;
                            break;
                        case "hubId":
                            $dataRequest = $this->hubid;
                            break;
                        case "username":
                            $dataRequest = $this->username;
                            break;
                        case "housename":
                            $dataRequest = $this->housename;
                            break;
                        case "postallane":
                            $dataRequest = $this->postallane;
                            break;
                        case "town":
                            $dataRequest = $this->town;
                            break;
                        case "city":
                            $dataRequest = $this->city;
                            break;
                        case "landnumber":
                            $dataRequest = $this->landnumber;
                            break;
                        case "powerState":                            
                            $dataRequest = $this->powerstate == 1 ? "ON" : "OFF";
                            break;
                    }
                    $this->data[$filters[$i]] = $dataRequest;
                }
            }       
        }        
        return $this->data;
    }
}