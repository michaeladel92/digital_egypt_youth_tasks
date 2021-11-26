<?php
require_once("init.php");

// redirect if didnt come from click
// if(!isset($_SERVER['HTTP_REFERER'])){
//   setMessage('Access Denied!', 'danger');
//   redirectHeader('index.php');
// }



    if(isset($_GET['id']) && $_GET['id'] !== ''){
  
      $id = intval($_GET['id']);
      $sql = "SELECT * FROM `users` WHERE `id` = {$id}";
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
    $user_id     = intval($_POST['user_id']);
    $name        = clean($_POST['name'],'string');
    $email       = clean($_POST['email'],'email');
    $password    = cleanPassword($_POST['password']);
    $conPassword = cleanPassword($_POST['con-password']);
    $linkedin    = clean($_POST['linkdin'],'url');
    $min         = 6;
    $min_10      = 10;
    $messages    = [];
    //validate name
    if(!validate($name,'empty')){
      $messages[] = 'Name cannot be empty!';  
    }
    //validate email
    if(!validate($email,'empty')){
      $messages[] = 'Email cannot be empty!';  
    }
    elseif(!validate($email,'email')){
      $messages[] = 'Email is not valid';  
    }
    //validate password
    if(!validate($password,'empty')){
      $messages[] = 'Password cannot be empty!'; 
    }
    elseif(!validate($password,'min',$min)){
      $messages[] = "Password must be more than $min characters!"; 
    }
    elseif($password !== $conPassword){
      $messages[] = "Password not matched!"; 
    }
  
    // validate url
    if(!validate($linkedin,'empty')){
      $messages[] = "linkdin Url cannot be empty!"; 
    }
    elseif(!validate($linkedin,'max',120)){
      $messages[] = "linkdin Url more than 120 char"; 
    }
    elseif(!validate($linkedin, 'url')){
      $messages[] = "linkdin Url not valid!"; 
    }
    elseif(($pos   = strpos($linkedin, 'www.linkedin')) === false){
      $messages[] = "linkdin Url must be a <b>linkdin URL</b>!"; 
    }

  
    if(count($messages) > 0){
      foreach($messages as $msg){
        $notifications[] = "<div class='alert alert-danger' role='alert'>$msg</div>";
      }
    }else{
      $password = md5($password);
          // db UPDATE
          $sql   = "UPDATE `users` SET 
                                      `name`='{$name}',
                                      `email`='{$email}',
                                      `password`='{$password}',
                                      `linkdin`='{$linkedin}'
                                   WHERE `id` = {$user_id}    
                                ";
          $query = mysqli_query($conn,$sql);  
          if($query){
            setMessage("User Uppdated Successfully!",'success');
            redirectHeader('index.php');
          }else{
            echo die(mysqli_error($conn));
            $notifications[] = "<div class='alert alert-danger' role='alert'>Error try again!</div>";
          }              
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Edit user</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Edit user</h2>
  
  <form action="edit.php?id=<?php echo $row['id'] ?>"  method="POST" enctype="multipart/form-data">
    <input type="hidden" value="<?= $row['id'] ?>" name="user_id">
  <!-- name -->
  <div class="form-group">
    <label>Name</label>
    <input value="<?=isset($_POST['name']) ? $_POST['name'] : $row['name'] ?>"  type="text" class="form-control" name="name" placeholder="Enter Name">
  </div>
  <!-- email -->
  <div class="form-group">
    <label>Email address</label>
    <input value="<?=isset($_POST['email']) ? $_POST['email'] : $row['email'] ?>" type="text"   class="form-control"  name="email" placeholder="Enter email">
    </div>
  <!-- password -->
  <div class="form-group">
    <label>Password</label>
    <input value="<?=isset($_POST['password']) ? $_POST['password'] : '' ?>" type="password"   class="form-control" name="password">
  </div>
  <!-- confirm password -->
  <div class="form-group">
    <label for="con-exampleInputPassword">confirm Password</label>
    <input value="<?=isset($_POST['con-password']) ? $_POST['con-password'] : '' ?>" type="password"   class="form-control" name="con-password">
  </div>

  <!-- linkdin -->
  <div class="form-group">
    <label>linkdin link</label>
    <input value="<?=isset($_POST['linkdin']) ? $_POST['linkdin'] : $row['linkdin'] ?>" type="text"   class="form-control"  name="linkdin" placeholder="linkdin url">
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
