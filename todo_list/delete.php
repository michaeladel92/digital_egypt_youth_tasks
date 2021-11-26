<?php
require_once("init.php");

// redirect if didnt come from click
if(!isset($_SERVER['HTTP_REFERER']) || !isset($_SESSION['id'])){
  setMessage('Access Denied!', 'danger');
  redirectHeader('index.php');
}



    if(isset($_GET['id']) && $_GET['id'] !== ''){
  
      $id = intval($_GET['id']);
      $user_id = intval($_SESSION['id']);
      $sql = "SELECT * FROM `tasks` WHERE `id` = {$id} AND `user_id` = {$user_id}";
      $query = mysqli_query($conn,$sql);
      if($query){
        $count = mysqli_num_rows($query);
          if($count !== 1){
            setMessage('Something went wrong, please try again', 'danger');
            redirectHeader('index.php');
          }else{
            $row = mysqli_fetch_assoc($query);
            if(intval($row['end_date']) < strtotime('today')){
              setMessage('passed duedate cannot delete!','danger');
              redirectHeader('index.php');
            }else{
              // DELETE
              $sql   = "DELETE FROM `tasks` WHERE `id` = {$id} AND `user_id` = {$user_id}";
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
          }    
  
      }else{
        setMessage('Something went wrong, please try again', 'danger');
        redirectHeader('index.php');
      }
    }else{
      setMessage('Something went wrong, please try again', 'danger');
      redirectHeader('index.php');
    }

