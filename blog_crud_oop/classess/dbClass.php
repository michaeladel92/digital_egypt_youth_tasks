<?php
ob_start();
session_start();

class Database{

  private   $server = "localhost";
  private   $dbName = "group8";
  private   $dbUser = "root";
  private   $dbPassword = "";
  protected $conn    = null;
  
  
    public function __construct(){
        
       $this->conn =  mysqli_connect($this->server,$this->dbUser,$this->dbPassword,$this->dbName);

       if(!$this->conn){
           echo mysqli_connect_error();
        }
     }

     public function escape_string($input){
      $clean = mysqli_real_escape_string($this->conn,$input);
      return $clean;
     }

     public function doQuery($sql){
       $result =   mysqli_query($this->conn,$sql);
       return $result; 
   }


   function __destruct(){
       mysqli_close($this->conn);
   }



}
