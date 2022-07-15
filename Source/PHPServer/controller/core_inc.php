<?php

/**
 * Core Function of the API
 * Version 1.0.0.0
 * @author Basura Ratnayake
 */

require_once ("database_inc.php");

class Core {
    protected $qwerty = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    protected $database;
    
    function __construct() {
        $this->database = new Database();
    }            
    
    public function isValidJson($string){
        $result = json_decode($string);
        
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = true; 
                break;
            case JSON_ERROR_DEPTH:
                $error = 'The maximum stack depth has been exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Invalid or malformed JSON.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Control character error, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON.';
                break;
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_RECURSION:
                $error = 'One or more recursive references in the value to be encoded.';
                break;
            case JSON_ERROR_INF_OR_NAN:
                $error = 'One or more NAN or INF values in the value to be encoded.';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error = 'A value of a type that cannot be encoded was given.';
                break;
            default:
                $error = 'Unknown JSON error occured.';
                break;
        }
        
        return $error;
    }
    
    public function cleanInput($input) {//Clean the Inputs from harmful characters
        $input = htmlentities($input);
        $input = mysqli_real_escape_string($this->database->connect(), $input);
        strip_tags($input, "<br>");
        stripslashes($input);
        return $input;
    }

    public function generateUniqueCode($count = 5) {//Generate a specified count unique code
        $code="";
        for ($i = 0; $i < $count; $i++) {
            $code.= $this->qwerty [rand(0, 61)];
        }
        return $code;
    }
    
    public function getDuration($timestamp) {//Shows Time ago
        list($date, $time) = explode(' ', $timestamp);
        list($year, $month, $day) = explode('-', $date);
        list($hour, $minute, $second) = explode(':', $time);
        $timestamp = mktime($hour, $minute, $second, $month, $day, $year);            

        $difference = time() - $timestamp;

        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");            
        $periods = array("sec", "min", "hr", "day", "week", "month", "year", "decade");

        for ($j = 0; $difference >= $lengths[$j]; $j++){
            $difference /= $lengths[$j];        
            $difference = round($difference);
        }
        return "$difference $periods[$j]";
    }
}
