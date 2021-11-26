<?php

$db_host ="localhost";
$db_user ="root";
$db_pass ="";
$db_name ="group8";

$conn = mysqli_connect($db_host,$db_user,$db_pass,$db_name);
          
if(!$conn){
  die("error".mysqli_connect_error());
}