<?php
/**
 * Database Connection of the API
 * Version 1.0.0.0
 * @author Basura Ratnayake
 */
class Database {
    protected $databaseUsername = "root";
    protected $databasePassword = "";
    protected $databaseHost = "localhost";
    protected $databaseName = "busriya_v1";
    
    protected $databaseQuery = "";
    
    function __construct() {
    }
    
    private $connection = null;
    
    public function connect(){//Establish Connection with the database
        $databaseConnection = new mysqli($this->databaseHost, $this->databaseUsername, $this->databasePassword, $this->databaseName);
        if ($databaseConnection->connect_errno > 0) {
            die('It looks like our server is down at the moment. Please try again later!');
            exit();
        }
        $this->connection = $databaseConnection;
        return $databaseConnection;
    }  
    public function close(){
        mysqli_close($this->connect());
    }
    
    public function makeQuery($query){//Retrieves a Query from the Database	
        $this->databaseQuery = $query;
        return mysqli_query($this->connect(), $query);
    }
    public function getLastExecutedQuery(){
        return $this->databaseQuery;
    }
    
    public function numberOfRecords($column, $table, $where){//Returns Number of Rows	
        return mysqli_num_rows($this->select($column, $table, $where));
    }
    
    public function fetchFullRow($column, $table, $where){//Retrieves data as an array from query
        $data=[];
        if($this->numberOfRecords($column, $table, $where) > 0){
            $row = mysqli_fetch_array($this->select($column, $table, $where));
            foreach($row as $r){
                $data[]=$r;
            }
        }            
        //$this->close();
        return $data;
    }   
    
    private $lastId;
    public function lastInsertedId(){
        return mysqli_insert_id($this->connection);
    }

    public function result($column, $table, $where){//Gets a Single Result from a Table
        $row = $this->fetchFullRow($column, $table, $where);         
        return $row[0];
    }

    public function select($column, $table, $where){//Gains data from table
        $databaseQuery = "SELECT $column from $table $where";
        return $this->makeQuery($databaseQuery);
    }

    public function insert($table, $values, $columns = null){//Insert Data to a Table for specified columns
        $databaseQuery = "INSERT INTO $table ";
        if ($columns != null) {
            $databaseQuery = $databaseQuery . "($columns) ";
        }

        $databaseQuery = $databaseQuery."VALUES ($values)";
        return $this->makeQuery($databaseQuery);
    }

    public function update($table, $column, $value, $where){//Update Data in Table
        $databaseQuery = "UPDATE $table SET $column = $value $where";
        return $this->makeQuery($databaseQuery);
    }
    public function updateRow($table, $data, $where){//Update Multiple Data
        $databaseQuery = "UPDATE $table $data $where";
        return $this->makeQuery($databaseQuery);
    }
    
    public function delete($table, $where){//Delete row from table
        $databaseQuery = "DELETE FROM $table $where";
        return $this->makeQuery($databaseQuery);
    }    
}