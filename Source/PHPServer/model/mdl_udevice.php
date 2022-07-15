<?php

/**
 * Device Class
 * Handles the Data of tbl_Deviceversion, tbl_loginDetails
 * @author Basura
 */
class UDevice {
    private $data;
    
    function __construct($username, $floorId, $deviceId = 'null', $roomdId = 'null') {
        $database = new Database();
        $this->data=array();    
        
        $tables = "tbl_userdevice ud, tbl_devices d, tbl_deviceversions v, tbl_floors f, tbl_houses h"; 
        $select = "ud.*, v.devType, v.devVersion, v.dateMan, d.serialNumber, f.floorname";
        
        $query = "where (ud.deviceId = d.deviceId and d.versionId = v.versionId) and (f.floorId = ud.floorId and f.houseId = h.houseId) and ud.floorId = $floorId and h.username = '$username'";
        if($roomdId != "null"){
            $tables = $tables.", tbl_rooms r";
            $query = $query." AND ud.roomId = $roomdId AND ud.roomId = r.roomId AND r.floorId = f.floorId";
            $select = $select.",r.roomName";
        }else{
            $query = $query." AND ud.roomId IS NULL";            
        }
        
        if($deviceId != "null"){
            $query = $query." AND ud.userDevices = $deviceId";
        } 
        
        $floorName = "";
        $roomName = "";
        
        if($database->numberOfRecords("ud.userDevices", $tables, $query) > 0){
            $result = $database->select($select, $tables, $query);
            $this->data=array();
            
            while($row = $result->fetch_assoc()) {
                $userDevices = $row["userDevices"]; 
                $deviceId = $row["deviceId"]; 
                $devName = $row["devName"]; 
                $devDes = $row["devDes"]; 
                $loc_xy = $row["loc_xy"]; 
                $powerState = $row["powerState"]; 
                $isDoor = $row["isDoor"]; 
                $rotateState = $row["rotateState"]; 
                $devType = $row["devType"]; 
                $dateMan = $row["dateMan"]; 
                $version = $row["devVersion"]; 
                $serial = $row["serialNumber"]; 
                
                $floorName = $row["floorname"]; 
                
                if($roomdId != "null"){
                    $roomName = $row["roomName"]; 
                }

                $this->data[] = array(
                    "u_deviceId" => $userDevices,
                    "device_id" => $deviceId,
                    "dev_name" => "$devName",
                    "dev_desc" => "$devDes",
                    "location" => "$loc_xy",
                    "power_state" => $powerState,
                    "is_door" => $isDoor,
                    "rotate_state" => $rotateState,
                    "dev_type" => "$devType",
                    "dev_version" => "$version",
                    "dev_man" => "$dateMan",
                    "serial" => "$serial"
                );
            }

            $this->data = (object)array(
                "floor_id" => $floorId,
                "floor_name" => "$floorName",
                "room_id" => $roomdId,
                "room_name" => "$roomName",
                "devices" => $this->data
            );
        }
    }
    
    public function exportAsJSON(){
        return $this->data;
    }
}