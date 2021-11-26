<?php
require_once("init.php");
if(isset($_SESSION['id'])){
  setMessage("access denied!",'danger');
  redirectHeader('index.php');
}

if($_SERVER['REQUEST_METHOD'] === "POST"){
  if(isset($_POST['sign_in'])){
    $email       = clean($_POST['email'],'email');
    $password    = cleanPassword($_POST['password']);
    $hashed      = md5($password);
    $sql = "SELECT * FROM `users` WHERE `email` = '{$email}' AND `password` = '{$hashed}'";
    $query = mysqli_query($conn,$sql);
    if($query){
      $count = mysqli_num_rows($query);
      if($count !== 1){
        setMessage("Invalid Entry!",'danger');
      }else{
        $row = mysqli_fetch_assoc($query);
        $_SESSION['id']    = $row['id'];
        $_SESSION['name']  = $row['name'];
        $_SESSION['email'] = $row['email'];
        setMessage("Logged in!",'success');
        redirectHeader('index.php');
      }
    }else{
      setMessage("Something went wrong please try again!",'danger');
    }

  }

}


?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
    <title>Sign in</title>
    <style>
  .bd-placeholder-img{font-size:1.125rem;text-anchor:middle;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}@media (min-width:768px){.bd-placeholder-img-lg{font-size:3.5rem}}body,html{height:100%}body{display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;padding-top:40px;padding-bottom:40px;background-color:#f5f5f5}.form-signin{width:100%;max-width:330px;padding:15px;margin:auto}.form-signin .checkbox{font-weight:400}.form-signin .form-control{position:relative;box-sizing:border-box;height:auto;padding:10px;font-size:16px}.form-signin .form-control:focus{z-index:2}.form-signin input[type=email]{margin-bottom:-1px;border-bottom-right-radius:0;border-bottom-left-radius:0}.form-signin input[type=password]{margin-bottom:10px;border-top-left-radius:0;border-top-right-radius:0}
    </style>
  </head>
  <body class="text-center">
    <!-- sign in -->
    <div class="container">
          <?php
        if(isset($_SESSION['message'])){
          displayMessage();
        }
        ?>
        <form class="form-signin" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
          <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
          <label for="inputEmail" class="sr-only">Email address</label>
          <input type="text" name="email" id="inputEmail" class="form-control" placeholder="Email address" autofocus>
          <label for="inputPassword" class="sr-only">Password</label>
          <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password">

          <button class="btn btn-lg btn-primary btn-block mt-1" name="sign_in" type="submit">Sign in</button>
          <p class="mt-5 mb-3 text-muted">&copy; ToDo List 
            <a href="./register.php">Register</a>
          </p>
      </form>
</div>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>

  </body>
</html>