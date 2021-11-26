<?php
require_once("init.php");



if(isset($_GET['id']) && $_GET['id'] !== ''){
  
  $id = intval($_GET['id']);
  $sql = "SELECT * FROM `blog` WHERE `id` = {$id}";
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
 

    $title        = clean($_POST['title'],'string');
    $description  = clean($_POST['description'],'string');

    $messages     = [];
    //title
    if(!validate($title,'empty')){
      $messages[] = 'Title cannot be empty!';  
    }
    //description 
    if(!validate($description,'empty')){
      $messages[] = 'Description cannot be empty!';  
    }
    elseif(!validate($description,'min',100)){
      $messages[] = 'Description Min char is 100!';  
    }
 
    // image Progress
    $img_name = $_FILES['image']['name'];
    $img_tmp  = $_FILES['image']['tmp_name'];
    $img_err  = $_FILES['image']['error'];
    $img_size = $_FILES['image']['size'];

  
    if(!validate($img_name,'empty')){
      $messages[] = "Image cannot be empty!"; 
    }
    else{
      $extension = explode('.',$img_name);
      $extension = strtolower(end($extension));
      $allowed   = ['png','jpg','jpeg'];
        // size
      if($img_size > 5000000) {
        $messages[] = "Image cannot be more than 5mb!"; 
      //error
      }elseif($img_err !== 0){
        $messages[] = "Oops, error accured in image, please try again!"; 
      }else{

          if(!in_array($extension,$allowed)){
              $messages[] = "Accepted Extensions png / jpg / jpeg!"; 
          }
      }
    }
  

  
    if(count($messages) > 0){
      foreach($messages as $msg){
        $notifications[] = "<div class='alert alert-danger' role='alert'>$msg</div>";
      }
    }else{

      $newName_db = md5(uniqid().'_'.time()).'.'.$extension;
      $newName    = './'.$newName_db;
      // upload img
      if(!move_uploaded_file($img_tmp,$newName)){
        $notifications[] = "<div class='alert alert-danger' role='alert'>Oops, image didnt upload properly, please try again!</div>"; 
      }else{
        // unlink image
          $sql   = "SELECT `image` FROM `blog` WHERE `id` = {$id} LIMIT 1";
          $query = mysqli_query($conn,$sql);
          $image = mysqli_fetch_assoc($query);
          $path  = "./".$image['image']; 
          unlink($path);
          // db INSERT
          $sql   = "UPDATE `blog` SET 
                                       `title`= '{$title}',
                                       `content`= '{$description}',
                                       `image` = '{$newName_db}'
                                        ";
          $query = mysqli_query($conn,$sql);  
          if($query){

            setMessage("Content Updated Successfully!",'success');
            redirectHeader('index.php');
          }else{
            $notifications[] = "<div class='alert alert-danger' role='alert'>Error try again!</div>";
          }              
    }
      }
          
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>blog</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>UPDATE blog</h2>
  
  
  <form action="edit.php?id=<?=$row['id']?>"  method="POST" enctype="multipart/form-data">

  <!-- title -->
  <div class="form-group">
    <label>title</label>
    <input value="<?=isset($_POST['title']) ? $_POST['title'] : $row['title'] ?>"  type="text" class="form-control" name="title" placeholder="Enter Title">
  </div>
  <!-- description -->
  <div class="form-group">
    <label>description</label>
    <textarea class="form-control"  name="description" placeholder="Enter description"><?=isset($_POST['description']) ? $_POST['description'] : $row['content'] ?>
    </textarea>
    </div>
  <!-- start date -->
  <div class="form-group">
    <label>Image</label>
    <input type="file" name="image"  class="form-control" >
  </div>


  <?php 
  if(count($notifications) > 0){
    foreach($notifications as $notification){
      echo $notification;
    }
  }
  ?>
<button type="submit" name="submit" class="btn btn-primary">Submit</button>
</form>
</div>

</body>
</html>

<?php closeConn();?>
