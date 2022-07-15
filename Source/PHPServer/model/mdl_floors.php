<?php

/**
 * Floor Class
 * Handles the Data of tbl_Deviceversion, tbl_loginDetails
 * @author Basura
 */

class Floor{
    private $floorId;
    private $houseId;
    private $data;
    
    function __construct($username, $floorId = null) {
        $database = new Database();
        $this->houseId = $username;
        $this->floorId = $floorId;
        
        if($database->numberOfRecords("*", "tbl_floors f, tbl_houses h", "WHERE f.houseId = h.houseId and h.username = '$username'") > 0){
            if($floorId == null){
                
            }else{
                if(strtolower($floorId) == "all"){
                    if($database->numberOfRecords("f.*", "tbl_floors f, tbl_houses h", "WHERE f.houseId = h.houseId and h.username = '$username'") > 0){
                        $result = $database->select("f.*", "tbl_floors f, tbl_houses h", "WHERE f.houseId = h.houseId and h.username = '$username'");
                        $this->data=array();
                        while($row = $result->fetch_assoc()) {
                            $floorid = $row["floorId"];
                            $floorname = $row["floorname"];
                            $floorpower = $row["powerState"];     
                            $this->houseId = $row["houseId"];     
                            
                            $this->data[] = array(
                                "floor_id" => $floorid,
                                "floor_name" => "$floorname",
                                "floor_power" => $floorpower
                            );
                        }
                        
                        $this->data = (object)array(
                            "house_id" => $this->houseId,
                            "floors" => $this->data
                        );
                    }
                }else{
                    if($database->numberOfRecords("f.*", "tbl_floors f, tbl_houses h", "WHERE f.houseId = h.houseId and h.username = '$username' AND floorId = $floorId") > 0){
                        $result = $database->select("f.*", "tbl_floors f, tbl_houses h", "WHERE f.houseId = h.houseId and h.username = '$username' AND floorId = $floorId");
                        $this->data=array();
                        while($row = $result->fetch_assoc()) {
                            $floorid = $row["floorId"];
                            $floorname = $row["floorname"];
                            $floorpower = $row["powerState"];                            
                            
                            $this->data = (object)array(
                                "house_id" => $row["houseId"],
                                "floor" =>array(
                                    "floor_id" => $floorid,
                                    "floor_name" => "$floorname",
                                    "floor_power" => $floorpower
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