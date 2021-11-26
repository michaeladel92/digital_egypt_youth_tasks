<?php
/* 
Create a form with the following inputs (name, email, password, address, gender, linkedin url, profile pic) Validate inputs then return message to user. 
* validation rules ... 
name  = [required , string] ok
email = [required,email] ok
password = [required,min = 6] ok
address = [required,length = 10 chars] ok
gender = [required]
linkedin url = [reuired | url]
Profile pic =[required|image]. 
Then create a profile page to read data that user inserted.
*/
ob_start();
session_start();
require_once('functions.php');


$notifications = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  if(isset($_POST['submit'])){

    $name        = clean($_POST['name']);
    $email       = cleanEmail($_POST['email']);
    $password    = trim($_POST['password']);
    $password    = str_replace(' ', '', $password);
    $conPassword = trim($_POST['con-password']);
    $conPassword = str_replace(' ', '', $conPassword);
    $address     = clean($_POST['address']);
    $linkedin    = cleanUrl($_POST['linkdin']);
    $gender      = clean($_POST['gender']);
    $min         = 6;
    $min_10      = 10;
    $messages    = [];
    //validate name
    if(empty($name)){
      $messages[] = 'Name cannot be empty!';  
    }
    //validate email
    if(empty($email)){
      $messages[] = 'Email cannot be empty!';  
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $messages[] = 'Email is not valid';  
    }
    //validate password
    if(empty($password)){
      $messages[] = 'Password cannot be empty!'; 
    }
    elseif(strlen($password) <= 6){
      $messages[] = "Password must be more than $min characters!"; 
    }
    elseif($password !== $conPassword){
      $messages[] = "Password not matched!"; 
    }
    //validate ip address
    if(empty($address)){
      $messages[] = "Address cannot be empty!"; 
    }
    elseif(strlen($address) < 10){
      $messages[] = "Address must be at less $min_10 characters!"; 
    }

    // validate url
    if(empty($linkedin)){
      $messages[] = "linkdin Url cannot be empty!"; 
    }
    elseif(!filter_var($linkedin, FILTER_VALIDATE_URL)){
      $messages[] = "linkdin Url not valid!"; 
    }
    elseif(($pos   = strpos($linkedin, 'www.linkdin')) === false){
      $messages[] = "linkdin Url must be a <b>linkdin URL</b>!"; 
    }

    // gender
    if(empty($gender)){
      $messages[] = "Please choose gender"; 
    }
    //if someone entered diffrent value in inspect
    elseif($gender !== 'male' && $gender !== 'female'){  
      $messages[] = "Please choose rather male or female"; 
    }

    // image Progress
    $img_name = $_FILES['image']['name'];
    $img_tmp  = $_FILES['image']['tmp_name'];
    $img_err  = $_FILES['image']['error'];
    $img_size = $_FILES['image']['size'];
    if(empty($img_name)){
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


      $newName = './'.md5(uniqid().'_'.time()).'.'.$extension;
      // upload img
      if(!move_uploaded_file($img_tmp,$newName)){
        $notifications[] = "<div class='alert alert-danger' role='alert'>Oops, image didnt upload properly, please try again!</div>"; 
      }else{
            // Save value to Session
            $_SESSION['name']     = $name;
            $_SESSION['email']    = $email;
            $_SESSION['address']  = $address;
            $_SESSION['linkedin'] = $linkedin;
            $_SESSION['gender']   = $gender;
            $_SESSION['newName']  = $newName;
            $_SESSION['notification'] = "<div class='alert alert-success' role='alert'>Message Sent successfully with the following data!    
               </div>";
            header('location:profile.php');   
      }



     

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
  <!-- address -->
  <div class="form-group">
    <label>Address</label>
    <input value="<?=isset($_POST['address']) ? $_POST['address'] : '' ?>" type="text"   class="form-control"  name="address" placeholder=" address">
  </div>
  <!-- linkdin -->
  <div class="form-group">
    <label>linkdin link</label>
    <input value="<?=isset($_POST['linkdin']) ? $_POST['linkdin'] : '' ?>" type="text"   class="form-control"  name="linkdin" placeholder="linkdin url">
  </div>
  <!-- gender -->
  <div class="form-group">
     <label>Gender</label>
    <select name="gender" class="form-control">
        <option value="">select gender</option>
        <?php $sessionGender = isset($_POST['gender']) ? $_POST['gender'] : '';?>
        <option value="male" <?php echo selectedGender($sessionGender,'male')?> >Male</option>
        <option value="female" <?php echo selectedGender($sessionGender,'female')?> >Female</option>
    </select>
</div>

<!-- profile -->
<div class="form-group">
  <label>Profile picture</label>
  <input class="form-control" type="file" name="image">
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
