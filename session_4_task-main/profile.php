<?php
ob_start();
session_start();
// redirect if no session
if(!isset($_SESSION['name'])){
  header('location:index.php');
}

// logout
if($_SERVER['REQUEST_METHOD'] === 'GET'){
  if(isset($_GET['user']) && $_GET['user'] === 'logout'){
    session_destroy();
    header('location:index.php');
  }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=`device-width`, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css	">

  <title><?=isset($_SESSION['name']) ? $_SESSION['name'] : '' ?></title>
</head>
<body>
  <!-- style -->
<style>
  body {
    background: #eee
}

.card {
    border: none;
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    cursor: pointer
}

.card:before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    width: 4px;
    height: 100%;
    background-color: #E1BEE7;
    transform: scaleY(1);
    transition: all 0.5s;
    transform-origin: bottom
}

.card:after {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    width: 4px;
    height: 100%;
    background-color: #8E24AA;
    transform: scaleY(0);
    transition: all 0.5s;
    transform-origin: bottom
}

.card:hover::after {
    transform: scaleY(1)
}

.fonts {
    font-size: 11px
}

.social-list {
    display: flex;
    list-style: none;
    justify-content: center;
    padding: 0
}

.social-list li {
    padding: 10px;
    color: #8E24AA;
    font-size: 19px
}

.buttons button:nth-child(1) {
    border: 1px solid #8E24AA !important;
    color: #8E24AA;
    height: 40px
}

.buttons button:nth-child(1):hover {
    border: 1px solid #8E24AA !important;
    color: #fff;
    height: 40px;
    background-color: #8E24AA
}

.buttons button:nth-child(2) {
    border: 1px solid #8E24AA !important;
    background-color: #8E24AA;
    color: #fff;
    height: 40px
}
</style>


<div class="container mt-5">
  <?php 
    if(isset($_SESSION['notification'])){
      echo $_SESSION['notification'];
      unset($_SESSION['notification']);
    }
  ?>
    <div class="row d-flex justify-content-center">
        <div class="col-md-7">
            <div class="card p-3 py-4">
                <div class="text-center"> <img src="<?=isset($_SESSION['newName']) ? $_SESSION['newName'] : 'https://i.imgur.com/bDLhJiP.jpg' ?>" width="100" class="rounded-circle"> </div>
                <div class="text-center mt-3"> <span class="bg-secondary p-1 px-4 rounded text-white"><?=isset($_SESSION['name']) ? $_SESSION['name'] : '' ?></span>
                    <h5 class="mt-2 mb-0"><?=isset($_SESSION['email']) ? $_SESSION['email'] : '' ?></h5> <span><?=isset($_SESSION['gender']) ? $_SESSION['gender'] : '' ?></span>
                    <div class="px-4 mt-1">
                        <p class="fonts">
                        <?=isset($_SESSION['address']) ? $_SESSION['address'] : '' ?>    
                      </p>
                    </div>
                    <ul class="social-list">
                        <li><i class="fa fa-linkedin"></i>
                        <?=isset($_SESSION['linkedin']) ? $_SESSION['linkedin'] : '' ?>
                      </li>
                    </ul>
                    <div class="buttons"> 
                      <a href="index.php" class="btn btn-outline-primary px-4">form</a>
                      <a href="profile.php?user=logout" class="btn btn-primary px-4 ms-3">logout</a> </div>

                </div>
            </div>
        </div>
    </div>
</div>


</body>
</html>