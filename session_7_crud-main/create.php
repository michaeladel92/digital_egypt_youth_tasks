<?php
require_once("init.php");

$notifications = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  if(isset($_POST['submit'])){

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
          // db INSERT
          $sql   = "INSERT INTO `users` 
                                      (`name`,
                                       `email`,
                                       `password`,
                                       `linkdin`) 
                                VALUES (
                                      '{$name}',
                                      '{$email}',
                                      '{$password}',
                                      '{$linkedin}')";
          $query = mysqli_query($conn,$sql);  
          if($query){
            setMessage("User Added Successfully!",'success');
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
  <title>Add new user</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Add new user</h2>
  
  
  <form   action="<?php echo $_SERVER['PHP_SELF'];?>"  method="POST" enctype="multipart/form-data">

  <!-- name -->
  <div class="form-group">
    <label>Name</label>
    <input value="<?=isset($_POST['name']) ? $_POST['name'] : '' ?>"  type="text" class="form-control" name="name" placeholder="Enter Name">
  </div>
  <!-- email -->
  <div class="form-group">
    <label>Email address</label>
    <input value="<?=isset($_POST['email']) ? $_POST['email'] : '' ?>" type="text"   class="form-control"  name="email" placeholder="Enter email">
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
    <input value="<?=isset($_POST['linkdin']) ? $_POST['linkdin'] : '' ?>" type="text"   class="form-control"  name="linkdin" placeholder="linkdin url">
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
