<?php
require_once("init.php");

// redirect if didnt come from click
if(!isset($_SERVER['HTTP_REFERER'])){
  setMessage('Access Denied!', 'danger');
  redirectHeader('index.php');
}



    if(isset($_GET['id']) && $_GET['id'] !== ''){
  
      $id = intval($_GET['id']);
      $sql = "SELECT `id`,`image` FROM `blog` WHERE `id` = {$id} LIMIT 1";
      $query = mysqli_query($conn,$sql);
      if($query){
        $count = mysqli_num_rows($query);
          if($count !== 1){
            setMessage('Something went wrong, please try again', 'danger');
            redirectHeader('index.php');
          }else{
            $image = mysqli_fetch_assoc($query);
            // DELETE
            $path  = "./".$image['image']; 
            unlink($path);
            $sql   = "DELETE FROM `blog` WHERE `id` = {$id} LIMIT 1";
            $query = mysqli_query($conn,$sql);
            if($query){
              setMessage('Row Deleted Successfully!','success');
              redirectHeader('index.php');
            }else{
              setMessage('Something went wrong, please try again', 'danger');
              redirectHeader('index.php');
            }
            closeConn();
          }    
  
      }else{
        setMessage('Something went wrong, please try again', 'danger');
        redirectHeader('index.php');
      }
    }else{
      setMessage('Something went wrong, please try again', 'danger');
      redirectHeader('index.php');
    }

