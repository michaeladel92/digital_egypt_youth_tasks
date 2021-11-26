<?php
require_once("init.php");

$sql   = "SELECT * FROM `users`";
$query = mysqli_query($conn,$sql);
$count = mysqli_num_rows($query);
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>

  <div class="container col-md-10 mt-5">
  <?php
  if(isset($_SESSION['message'])){
    displayMessage();
  }
  ?>
   <a href="create.php" class="btn btn-primary m-1">Create</a>
  <table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">id</th>
      <th scope="col">name</th>
      <th scope="col">email</th>
      <th scope="col">link</th>
      <th scope="col">options</th>
    </tr>
  </thead>
  <tbody>
    <?php 
      if($count > 0):
         while($row = mysqli_fetch_assoc($query)): 
    ?>
    <tr>
      <th scope="row"><?=$row['id']?></th>
      <td><?=$row['name']?></td>
      <td><?=$row['email']?></td>
      <td><?=$row['linkdin']?></td>
      <td>
          <a href="delete.php?id=<?=$row['id']?>" class="btn btn-danger btn-sm">delete</a>
          <a href="edit.php?id=<?=$row['id']?>" class="btn btn-warning btn-sm">Edit</a>
      </td>
    </tr>
      <?php
        endwhile;
        endif;
      ?>
  </tbody>
</table>
  </div>


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
  </body>
</html>