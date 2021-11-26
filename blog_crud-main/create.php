<?php
require_once("init.php");


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
          // db INSERT
          $sql   = "INSERT INTO `blog` 
                                      (`title`,
                                       `content`,
                                       `image`) 
                                VALUES (
                                      '{$title}',
                                      '{$description}',
                                      '{$newName_db}'
                                      )";
          $query = mysqli_query($conn,$sql);  
          if($query){

            setMessage("Content Added Successfully!",'success');
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
  <h2>ADD blog</h2>
  
  
  <form action="<?php echo $_SERVER['PHP_SELF'];?>"  method="POST" enctype="multipart/form-data">

  <!-- title -->
  <div class="form-group">
    <label>title</label>
    <input value="<?=isset($_POST['title']) ? $_POST['title'] : '' ?>"  type="text" class="form-control" name="title" placeholder="Enter Title">
  </div>
  <!-- description -->
  <div class="form-group">
    <label>description</label>
    <textarea class="form-control"  name="description" placeholder="Enter description"><?=isset($_POST['description']) ? $_POST['description'] : '' ?>
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
