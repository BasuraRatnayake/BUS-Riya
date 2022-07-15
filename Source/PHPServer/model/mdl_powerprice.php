<?php

/**
 * Power Price Class
 * Handles the Data of tbl_house and tbl_loginDetails
 * @author Basura
 */

class PowerPrice {
    private $priceid;
    private $powertype;
    private $price;
    private $dateintro;
    
    private $filterRecords;
    private $data;
    
    function __construct($username, $filter) {
        $database = new Database();
        
        if($database->numberOfRecords("*", "tbl_powerPrices", "ORDER BY dateIntro DESC LIMIT 1") > 0){
            $data = $database->fetchFullRow("*", "tbl_powerPrices", "ORDER BY dateIntro DESC LIMIT 1");
            
            $this->priceid = $data[0];
            $this->powertype = $data[2];
            $this->price = $data[4];
            $this->dateintro = $data[6];
        
            $this->filterRecords = strtolower($filter);             
        } 
    }
    
    public function exportAsJSON(){
        if($this->priceid == null){
            $this->data = (object)array();
        }else{
            $dateJ = explode(" ", $this->dateintro)[0];
            if($this->filterRecords == "all"){
                $this->data = (object)array(
                    "priceid" => "$this->priceid",
                    "powertype" => "$this->powertype",
                    "powerprice" => "$this->price",
                    "dateintro" => "$dateJ"
                );  
            }else{
                $filters = explode(",", $this->filterRecords);
                $this->data = array();
                $dataRequest = "";
                for($i=0;$i<sizeof($filters);$i++){
                    switch($filters[$i]){
                        case "priceid":
                            $dataRequest = $this->priceid;
                            break;
                        case "powertype":
                            $dataRequest = $this->powertype;
                            break;
                        case "powerprice":
                            $dataRequest = $this->price;
                            break;
                        case "dateintro":
                            $dataRequest = explode(" ", $this->dateintro)[0];
                            break;
                    }
                    $this->data[$filters[$i]] = $dataRequest;
                }
            }       
        }        
        return $this->data;
    }
}
