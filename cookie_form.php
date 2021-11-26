<?php
ob_start();
session_start();
// cridentials that will get in database
$hasedPasswordInDataBase = password_hash("12345", PASSWORD_BCRYPT, ["cost" => 12]);
$databaseArray = ['email'    => 'admin@admin.com','password' => $hasedPasswordInDataBase];
$message ='';

/*===================================================
  logged in form
===================================================*/ 
if(isset($_POST['submit'])){

  $email       = $_POST['email'];
  $password    = $_POST['password'];

 
    if($databaseArray['email'] === $email && password_verify($password, $databaseArray['password'])){
      // check if remember checked
      if(isset($_POST['remember'])){
         $encreptedEmail    = base64_encode($email); 
         $encreptedPassword = base64_encode($password);
        setcookie('email',$encreptedEmail,time()+3600,'/',null,true,true);
        setcookie('password',$encreptedPassword,time()+3600,'/',null,true,true);
      }
      $_SESSION['email'] = $email;
      $message = "<div class='alert alert-warning' role='alert'>You've logged in, you will be redirect!</div>";
      header("Refresh:3; url=cookie_form.php");

    }else{$message = "<div class='alert alert-warning' role='alert'>Invalid Entry</div>";}
    
 
}


/*===================================================
  LOGOUT
===================================================*/ 
if(isset($_SESSION['email']) && isset($_GET['action']) && $_GET['action'] === 'logout' ){
    unset($_SESSION['email']);
    // setcookie('email',$encreptedEmail,time()-8600,'/',null,true,true);
    // setcookie('password',$encreptedPassword,time()-8600,'/',null,true,true);
    header('location:cookie_form.php');
}



?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
      body,html{height:100%}body{display:-ms-flexbox;display:-webkit-box;display:flex;-ms-flex-align:center;-ms-flex-pack:center;-webkit-box-align:center;align-items:center;-webkit-box-pack:center;justify-content:center;padding-top:40px;padding-bottom:40px;background-color:#f5f5f5}.form-signin{width:100%;max-width:330px;padding:15px;margin:0 auto}.form-signin .checkbox{font-weight:400}.form-signin .form-control{position:relative;box-sizing:border-box;height:auto;padding:10px;font-size:16px}.form-signin .form-control:focus{z-index:2}.form-signin input[type=email]{margin-bottom:-1px;border-bottom-right-radius:0;border-bottom-left-radius:0}.form-signin input[type=password]{margin-bottom:10px;border-top-left-radius:0;border-top-right-radius:0}
    </style>
    <title>Hello, world!</title>
  </head>
  <body class="text-center">
    <?php if(!isset($_SESSION['email'])):  ?>
  <form class="form-signin" method="POST" action="<?=$_SERVER['PHP_SELF']?>" >
      <img class="mb-4" src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
      <!-- email -->
      <label for="inputEmail" class="sr-only">Email address</label>
      <input type="text" id="inputEmail" name="email" class="form-control" placeholder="Email address"  autofocus value="<?= isset($_COOKIE['email']) ? base64_decode($_COOKIE['email']) : '' ?>">
      <!-- password -->
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" value="<?= isset($_COOKIE['password']) ? base64_decode($_COOKIE['password']) : '' ?>">
      <div class="checkbox mb-3">
        <label>
          <input type="checkbox" name="remember" value="remember-me"> Remember me
        </label>
      </div>
      <?= isset($message) ? $message : ''?>
      <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit">Sign in</button>
      <p class="mt-5 mb-3 text-muted">&copy; 2017-2018</p>
    </form>

     <?php else: ?> 
    <div class="jumbotron">
        <h1 class="display-4"><?=$_SESSION['email']?></h1>
        <hr class="my-4">
        <p class="lead">
                <a class="btn btn-primary btn-lg" href="<?=$_SERVER['PHP_SELF']?>?action=logout" role="button">logout</a>
        </p>
    </div>
     <?php endif;?> 
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  
  
  
 
 
 
 
 
 
 
 
 
 
  </body>
</html>