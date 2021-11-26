<?php
/*
Create a form with the following inputs (name, email, password, address, linkedin url) Validate inputs then return message to user . 
* validation rules ... 
name  = [required ]
email = [required,email]
password = [required,min length = 6]
address = [required,length = 10 chars]
linkedin url = [required | url]
*/ 


//Remove all HTML tags from a string
function clean($variable){
  $clean = trim($variable);
  $clean = strtolower($clean);
  $clean = filter_var($clean, FILTER_SANITIZE_STRING);
  return $clean;
}

//Remove all illegal characters from an email address:
function cleanEmail($variable){
  $clean = trim($variable);
  $clean = strtolower($clean);
  $clean = filter_var($clean, FILTER_SANITIZE_EMAIL);
  return $clean;
}

//removes all illegal URL characters from a string
function cleanUrl($variable){
  $clean = trim($variable);
  $clean = strtolower($clean);
  $clean = filter_var($clean, FILTER_SANITIZE_URL);
  return $clean;
}


// 
$notifications = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  if(isset($_POST['submit'])){
    $name        = clean($_POST['name']);
    $email       = cleanEmail($_POST['email']);
    $password    = trim($_POST['password']);
    $password    = str_replace(' ', '', $password);
    $conPassword = trim($_POST['con-password']);
    $conPassword = str_replace(' ', '', $conPassword);
    $ipAddress   = clean($_POST['address']);
    $linkedin    = cleanUrl($_POST['linkdin']);
    $min         = 6;
    $max         = 10;
    $messages    = [];
    //validate name
    if(empty($name)){
      $messages[] = 'Name cannot be empty!';  
    }
    //validate email
    if(empty($email)){
      $messages[] = 'Email cannot be empty!';  
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $messages[] = 'Email is not valid';  
    }
    //validate password
    if(empty($password)){
      $messages[] = 'Password cannot be empty!'; 
    }
    if(strlen($password) <= 6){
      $messages[] = "Password must be more than $min characters!"; 
    }
    if($password !== $conPassword){
      $messages[] = "Password not matched!"; 
    }
    //validate ip address
    if(empty($ipAddress)){
      $messages[] = "IP address cannot be empty!"; 
    }
    if(strlen($ipAddress) > 10){
      $messages[] = "IP Address must be less than $max characters!"; 
    }
    if(!filter_var($ipAddress, FILTER_VALIDATE_IP)){
      $messages[] = "IP address is not valid!"; 
    }
    // validate url
    if(empty($linkedin)){
      $messages[] = "linkdin Url cannot be empty!"; 
    }
    if(!filter_var($linkedin, FILTER_VALIDATE_URL)){
      $messages[] = "linkdin Url not valid!"; 
    }
    if(($pos   = strpos($linkedin, 'linkdin')) === false){
      $messages[] = "linkdin Url must be a <b>linkdin URL</b>!"; 
    }


    if(count($messages) > 0){
      foreach($messages as $msg){
        $notifications[] = "<div class='alert alert-danger' role='alert'>$msg</div>";
      }
    }else{
      $notifications[] = <<<DELIMETER
      <div class='alert alert-success' role='alert'>Message Sent successfully with the following data!<br>
      <p><b>name:<b> $name</p>
      <p><b>email:<b> $email</p>
      <p><b>password:<b> $password</p>
      <p><b>conPassword:<b> $conPassword</p>
      <p><b>ipAddress:<b> $ipAddress</p>
      <p><b>linkedin:<b> $linkedin</p>      
      </div>
      DELIMETER;

    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>validation form</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>validation form</h2>
  
  
  <form   action="<?php echo $_SERVER['PHP_SELF'];?>"  method="POST">

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
  <!-- address -->
  <div class="form-group">
    <label>IP Address</label>
    <input value="<?=isset($_POST['address']) ? $_POST['address'] : '' ?>" type="text"   class="form-control"  name="address" placeholder="ip address">
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
