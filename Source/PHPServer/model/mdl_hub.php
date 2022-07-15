<?php

/**
 * Hub Class
 * Handles the Data of tbl_hubs and tbl_loginDetails
 * @author Basura
 */
class Hub {
    private $hubId;
    private $versionId;
    private $serialNumber;
    private $assigned;
    private $username;
    private $dateMan;
    private $devVersion;
    private $devType;
    
    private $filterRecords;
    
    private $where;
    private $data;
    
    function __construct($username, $filter) {
        $database = new Database();
        
        if($database->numberOfRecords("*", "tbl_hubs", "WHERE username = '$username' and assigned = 'YES'") > 0){
            $data = $database->fetchFullRow("*", "tbl_DeviceVersions dv, tbl_hubs th", "WHERE dv.versionId = th.versionId and th.username = '$username'");
            $this->versionId = $data[0];
            $this->dateMan = $data[2];
            $this->devVersion = $data[4];
            $this->devType = $data[6];
            $this->hubId = $data[8];
            $this->serialNumber = $data[12];
            $this->assigned = $data[14];
            $this->username = $data[16];
            
            $this->filterRecords = strtolower($filter);   
            $this->where = "WHERE userId = $data[0]";
        }        
    }
    
    public function exportAsJSON(){
        if($this->username == null){
            $this->data = (object)array();
        }else{
            $dateJ = explode(" ", $this->dateMan)[0];
            if($this->filterRecords == "all"){
                $this->data = (object)array(
                    "hubid" => "$this->hubId",
                    "serialnumber" => "$this->serialNumber",
                    "dateman" => "$dateJ",
                    "username" => "$this->username",
                    "devversion" => "$this->devVersion"
                );  
            }else{
                $filters = explode(",", $this->filterRecords);
                $this->data = array();
                $dataRequest = "";
                for($i=0;$i<sizeof($filters);$i++){
                    switch($filters[$i]){
                        case "hubid":
                            $dataRequest = $this->hubId;
                            break;
                        case "serialnumber":
                            $dataRequest = $this->serialNumber;
                            break;
                        case "dateman":
                            $dataRequest = explode(" ", $this->dateMan)[0];
                            break;
                        case "username":
                            $dataRequest = $this->lastname;
                            break;
                        case "devversion":
                            $dataRequest = $this->devVersion;
                            break;
                    }
                    $this->data[$filters[$i]] = $dataRequest;
                }
            }       
        }        
        return $this->data;
    }
}
