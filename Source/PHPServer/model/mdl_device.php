<?php

/**
 * Device Class
 * Handles the Data of tbl_Deviceversion, tbl_loginDetails
 * @author Basura
 */
class Device {
    private $username;
    private $assigned;
    private $deviceId;
    private $data;
    
    function __construct($username, $deviceId, $assigned = null) {
        $database = new Database();
        $this->username = $username;
        $this->assigned = $assigned;
        $this->deviceId = $deviceId;
        $this->data=array();
        
        if($database->numberOfRecords("*", "tbl_devices", "WHERE username = '$username'") > 0){
            if($deviceId == null){
                
            }else{
                if(strtolower($deviceId) == "all"){
                    $query = "WHERE td.username = '$username' AND tdv.versionId = td.versionId";
                    if($assigned != null){
                        $query = $query." AND td.assigned = '$assigned'";
                    }   
                    
                    if($database->numberOfRecords("*", "tbl_devices td, tbl_DeviceVersions tdv", $query) > 0){
                        $result = $database->select("*", "tbl_devices td, tbl_DeviceVersions tdv", $query);
                        $this->data=array();
                        while($row = $result->fetch_assoc()) {
                            $deviceId = $row["deviceId"];
                            $version = $row["versionId"];   
                            $serialNumber = $row["serialNumber"];
                            $assigned = $row["assigned"];
                            $devType = $row["devType"];
                            $username = $row["username"];    
                            $versionData = $row["devVersion"];  
                            
                            $this->data[] = array(
                                "device_id" => $deviceId,
                                "version_id" => $version,
                                "device_serial" => "$serialNumber",
                                "device_type" => "$devType",
                                "device_assigned" => "$assigned",
                                "version" => "$versionData"
                            );
                        }
                        
                        $this->data = (object)array(
                            "devices" => $this->data
                        );
                    }
                }else{
                    if($database->numberOfRecords("*", "tbl_devices td, tbl_DeviceVersions tdv", "WHERE td.username = '$username' AND tdv.versionId = td.versionId AND deviceId = $deviceId") > 0){
                        $result = $database->select("*", "tbl_devices td, tbl_DeviceVersions tdv", "WHERE td.username = '$username' AND tdv.versionId = td.versionId AND deviceId = $deviceId");
                        $this->data=array();
                        while($row = $result->fetch_assoc()) {
                            $deviceId = $row["deviceId"];
                            $version = $row["versionId"];   
                            $serialNumber = $row["serialNumber"];
                            $assigned = $row["assigned"];
                            $username = $row["username"];    
                            $versionData = $row["devVersion"];                           
                            
                            $this->data = (object)array(
                                "devices" =>array(
                                    "device_id" => $deviceId,
                                    "version_id" => $version,
                                    "device_serial" => "$serialNumber",
                                    "device_assigned" => "$assigned",
                                    "version" => "$versionData"
                                )                                
                            );
                            break;
                        }
                    }                    
                }
            }  
        }
    }
    
    public function exportAsJSON(){
        return $this->data;
    }
}