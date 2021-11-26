<?php
require_once("init.php");

if(!isset($_SESSION['id'])){
  setMessage("access denied!",'danger');
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
          }
      }else{
        setMessage('Something went wrong, please try again', 'danger');
        redirectHeader('index.php');
      }
    }else{
      setMessage('Something went wrong, please try again', 'danger');
      redirectHeader('index.php');
    }


    $notifications = [];
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      if(isset($_POST['submit'])){
        $list_id      = intval($_POST['list_id']);
        $title        = clean($_POST['title'],'string');
        $description  = clean($_POST['description'],'string');
        $start_date   = strtotime($_POST['start_date']);
        $end_date     = strtotime($_POST['end_date']);
        $messages     = [];
        //title
        if(!validate($title,'empty')){
          $messages[] = 'Title cannot be empty!';  
        }
        //description 
        if(!validate($description,'empty')){
          $messages[] = 'Description cannot be empty!';  
        }
     
        //start date
        if(!validate($start_date,'empty')){
          $messages[] = 'Start date cannot be empty!'; 
        }
        elseif($start_date < (strtotime('today') - 100) ){
          $messages[] = "Cannot addd passed due date!"; 
        }
        // end date
        if(!validate($end_date,'empty')){
          $messages[] = "End date cannot be empty!"; 
        }
        elseif($end_date < (strtotime('today') - 100) ){
          $messages[] = "Cannot add passed due date!"; 
        }
      
        if(count($messages) > 0){
          foreach($messages as $msg){
            $notifications[] = "<div class='alert alert-danger' role='alert'>$msg</div>";
          }
        }else{
              $id = intval($_SESSION['id']);
              // db INSERT
              $sql   = "UPDATE `tasks` SET 
                                           `title` = '{$title}',
                                           `description` = '{$description}',
                                           `start_date` = {$start_date},
                                           `end_date` = {$end_date}
                                       WHERE 
                                       `id` = {$list_id}
                                       AND    
                                       `user_id` = {$id} 
                                    ";
              $query = mysqli_query($conn,$sql);  
              if($query){
    
                setMessage("List Added Successfully!",'success');
                redirectHeader('index.php');
              }else{
                $notifications[] = "<div class='alert alert-danger' role='alert'>Error try again!</div>";
              }              
        }
      }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>todo list</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Update TO DO LIST</h2>
  
  
  <form action="edit.php?id=<?= $row['id'];?>"  method="POST">
  <input type="hidden" value="<?= $row['id'] ?>" name="list_id">
  <!-- title -->
  <div class="form-group">
    <label>title</label>
    <input value="<?=isset($_POST['title']) ? $_POST['title'] : $row['title'] ?>"  type="text" class="form-control" name="title" placeholder="Enter Title">
  </div>
  <!-- description -->
  <div class="form-group">
    <label>description</label>
    <textarea class="form-control"  name="description" placeholder="Enter description"><?=isset($_POST['description']) ? $_POST['description'] : $row['description'] ?>
    </textarea>
    </div>
  <!-- start date -->
  <div class="form-group">
    <label>start date</label>
    <input type="date" value=""  class="form-control" name="start_date">
  </div>
  <!-- end date  -->
  <div class="form-group">
    <label for="con-exampleInputPassword">end date</label>
    <input type="date"   class="form-control" name="end_date">
  </div>

  <?php 
  if(count($notifications) > 0){
    foreach($notifications as $notification){
      echo $notification;
    }
  }
  ?>
<button type="submit" name="submit" class="btn btn-primary">Update</button>
</form>
</div>

</body>
</html>

<?php closeConn();?>
