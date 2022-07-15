<?php

/**
 * User Class
 * Handles the Data of tbl_users and tbl_loginDetails
 * @author Basura
 */
class User {
    private $userid;
    private $username;
    private $firstname;
    private $lastname;
    private $mobilenumber;
    private $title;
    private $dateJoined;
    private $email;
    private $password;
    
    private $filterRecords;
    
    private $where;
    private $data;
    
    function __construct($username, $filter) {
        $database = new Database();
        
        if($database->numberOfRecords("*", "tbl_loginDetails", "WHERE username = '$username'") > 0){
            $data = $database->fetchFullRow("*", "tbl_loginDetails tl, tbl_users tu", "WHERE tl.userId = tu.userId and tl.username = '$username'");
            $this->userid = $data[0];
            $this->username = $data[2];
            $this->title = $data[15];
            $this->firstname = $data[11];
            $this->lastname = $data[13];
            $this->email = $data[6];
            $this->mobilenumber = $data[8];
            $this->dateJoined = $data[17];
            $this->password = md5($data[4]);
            
            $this->filterRecords = strtolower($filter);            
            
            $this->where = "WHERE userId = $data[0]";
        }
    }
    
    public function update_lastname($value){
        $database = new Database();
        if($database->update("tbl_users", "lastname", "'$value'", $this->where)){   
            return $value;
        }else{
            return null;
        }
    }
    public function update_firstname($value){
        $database = new Database();
        if($database->update("tbl_users", "firstname", "'$value'", $this->where)){   
            return $value;
        }else{
            return null;
        }
    }
    public function update_title($value){
        $database = new Database();
        if($database->update("tbl_users", "title", "'$value'", $this->where)){   
            return $value;
        }else{
            return null;
        }
    }
    public function update_password($value){
        $database = new Database();
        if($database->update("tbl_loginDetails", "password", "'$value'", $this->where)){      
            $value = md5($value);
            return $value;
        }else{
            return null;
        }
    }    
    public function update_email($value){
        $database = new Database();
        if($database->update("tbl_loginDetails", "email", "'$value'", $this->where)){   
            return $value;
        }else{
            return null;
        }
    }
    public function update_mobile($value){
        $database = new Database();
        if($database->update("tbl_loginDetails", "mobileNumber", "'$value'", $this->where)){   
            return $value;
        }else{
            return null;
        }
    }
    
    public function exportAsJSON(){
        if($this->username == null){
            $this->data = (object)array();
        }else{
            $dateJ = explode(" ", $this->dateJoined)[0];
            if($this->filterRecords == "all"){
                $this->data = (object)array(
                    "userid" => "$this->userid",
                    "title" => "$this->title",
                    "firstname" => "$this->firstname",
                    "lastname" => "$this->lastname",
                    "username" => "$this->username",
                    "password" => "$this->password",
                    "email" => "$this->email",
                    "mobilenumber" => "$this->mobilenumber",
                    "datejoined" => "$dateJ"
                );  
            }else{
                $filters = explode(",", $this->filterRecords);
                $this->data = array();
                $dataRequest = "";
                for($i=0;$i<sizeof($filters);$i++){
                    switch($filters[$i]){
                        case "userid":
                            $dataRequest = $this->userid;
                            break;
                        case "title":
                            $dataRequest = $this->title;
                            break;
                        case "firstname":
                            $dataRequest = $this->firstname;
                            break;
                        case "lastname":
                            $dataRequest = $this->lastname;
                            break;
                        case "username":
                            $dataRequest = $this->username;
                            break;
                        case "password":
                            $dataRequest = $this->password;
                            break;
                        case "email":
                            $dataRequest = $this->email;
                            break;
                        case "mobilenumber":
                            $dataRequest = $this->mobilenumber;
                            break;
                        case "datejoined":                            
                            $dataRequest = explode(" ", $this->dateJoined)[0];
                            break;
                    }
                    $this->data[$filters[$i]] = $dataRequest;
                }
            }       
        }        
        return $this->data;
    }
}