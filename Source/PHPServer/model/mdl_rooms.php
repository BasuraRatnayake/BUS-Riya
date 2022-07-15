<?php

/**
 * Room Class
 * Handles the Data of tbl_Deviceversion, tbl_loginDetails
 * @author Basura
 */

class Room{
    private $roomid;
    private $floorId;
    private $data;
    
    function __construct($floorId, $roomId = null) {
        $database = new Database();
        $this->floorId = $floorId;
        $this->roomid = $roomId;
        
        if($database->numberOfRecords("*", "tbl_rooms", "WHERE floorId = $floorId") > 0){
            if($roomId == null){
                
            }else{
                if(strtolower($roomId) == "all"){
                    if($database->numberOfRecords("*", "tbl_rooms", "WHERE floorId = $floorId") > 0){
                        $result = $database->select("r.*, ud.userDevices, ud.powerState as devicePower", "tbl_rooms r, tbl_userdevice ud", "where r.roomId = ud.roomId and r.roomId is not null and r.floorId = $floorId and isDoor = 1");
                        $this->data=array();
                        while($row = $result->fetch_assoc()) {
                            $roomId = $row["roomId"];
                            $roomName = $row["roomName"];   
                            $roomDesc = $row["roomDesc"];
                            $locXY = $row["loc_xy"];
                            $sizeHW = $row["size_hw"];
                            $rotateS = $row["rotateState"];
                            $powerS = $row["powerState"];   
                            $doorState = $row["devicePower"]; 
                            $uid = $row["userDevices"]; 
                            
                            $this->data[] = array(
                                "room_id" => "$roomId",
                                "room_name" => $roomName,
                                "room_desc" => "$roomDesc",
                                "room_loc" => "$locXY",
                                "room_size" => "$sizeHW",
                                "rotate_state" => "$rotateS",
                                "power_state" => "$powerS",
                                "door_state" => "$doorState",
                                "device_id" => "$uid"
                            );
                        }
                        
                        $this->data = (object)array(
                            "floor_id" => $floorId,
                            "rooms" => $this->data
                        );
                    }
                }else{
                    if($database->numberOfRecords("*", "tbl_rooms", "WHERE floorId = $floorId AND roomId = $roomId") > 0){
                        $result = $database->select("*", "tbl_rooms", "WHERE floorId = $floorId AND roomId = $roomId");
                        $this->data=array();
                        while($row = $result->fetch_assoc()) {
                            $roomId = $row["roomId"];
                            $roomName = $row["roomName"];   
                            $roomDesc = $row["roomDesc"];
                            $locXY = $row["loc_xy"];
                            $sizeHW = $row["size_hw"];
                            $rotateS = $row["rotateState"];
                            $powerS = $row["powerState"];                             
                            
                            $this->data = (object)array(
                                "floor_id" => $floorId,
                                "rooms" =>array(
                                    "room_id" => "$roomId",
                                    "room_name" => $roomName,
                                    "room_desc" => "$roomDesc",
                                    "room_loc" => "$locXY",
                                    "room_size" => "$sizeHW",
                                    "rotate_state" => "$rotateS",
                                    "power_state" => "$powerS"
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